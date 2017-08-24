<?php
/**
 * Plugin Name: WooCommerce Maison Fibank Gateway
 * Description: A payment gateway for First Investment Bank
 * Version: 1.0.0
 * Author: Kristian Kirov
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: woocommerce-gateway-maison-fibank
 */

if (! defined( 'ABSPATH' )) {
    exit; // Exit if accessed directly
}

require_once 'functions.php';

add_action( 'plugins_loaded', 'init_maison_fibank_gateway_class' );
function init_maison_fibank_gateway_class() {
    class WC_Gateway_Maison_Fibank_Gateway extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'maison_fibank_gateway';
            $this->icon = plugin_dir_url( __FILE__ ) . 'assets/accepted-cards.png';
            $this->has_fields = false;
            $this->method_title = 'Fibank';
            $this->method_description = 'Fibank Payment Gateway';

            $this->supports = array( 
				'products'
			);

            $this->payment_notification_url = WC()->api_request_url( 'WC_Gateway_Maison_Fibank' );

            $this->init_form_fields();
            $this->init_settings();

            $this->enabled = $this->get_option( 'enabled', 'no' );
            $this->test_mode = $this->get_option( 'test_mode', 'no' ) == 'yes';
            $this->title = $this->get_option( 'title' );
            $this->description = $this->get_option( 'description' );
            $this->certificate_path = $this->get_option( 'certificate_path' );
            $this->certificate_password = $this->get_option( 'certificate_password' );

            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

            if ($this->enabled == 'yes') {
                include_once( dirname( __FILE__ ) . '/woocommerce-gateway-maison-fibank-handler.php' );
                new Gateway_Maison_Fibank_Gateway_Handler($this->test_mode, $this->certificate_path, $this->certificate_password);
            }
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => 'Enable/Disable',
                    'type' => 'checkbox',
                    'label' => 'Enable Fibank payment gateway',
                    'default' => 'no'
                ),
                'test_mode' => array(
                    'title' => 'Test Mode',
                    'type' => 'checkbox',
                    'label' => 'Enable Test Mode',
                    'default' => 'no'
                ),
                'title' => array(
                    'title' => 'Title',
                    'type' => 'text',
                    'description' => 'This controls the title which the user sees during checkout.',
                    'default' => 'Online Payment'
                ),
                'description' => array(
                    'title' => 'Description',
                    'type' => 'text',
                    'description' => 'This controls the description which the user sees during checkout.',
                    'default' => 'Pay securely using your credit/debit card.'
                ),
                'notification_url' => array(
                    'title' => 'Response Url',
                    'type' => 'text',
                    'description' => 'The URL that gets called on successfull or failed transaction.',
                    'default' => $this->payment_notification_url,
                    'custom_attributes' => array( 'readonly' => 'readonly' )
                ),
                'certificate_path' => array(
                    'title' => 'Certificate Path',
                    'type' => 'text',
                    'description' => 'Path the certificate provided by Fibank.'
                ),
                'certificate_password' => array(
                    'title' => 'Certificate Password',
                    'type' => 'password',
                    'description' => 'The password for the provided certificate.'
                )
            );
        }

        public function process_admin_options() {
            parent::process_admin_options();
        }

        function process_payment( $order_id ) {
            $order = wc_get_order( $order_id );

            include_once( dirname( __FILE__ ) . '/maison-fibank-client.php' );
            $fibank_client = new Maison_Fibank_Client($this->test_mode, $this->certificate_path, $this->certificate_password);
            $order_total = $order->get_total();
            $client_ip = get_client_ip();
            $register_transaction_result = $fibank_client->register_transaction($order_total, Maison_Fibank_Currency::$USD, $client_ip, $order_id);
            if (!$register_transaction_result['success']) {
                $register_transaction_error = $register_transaction_result['data'];
                $error_message = 'Error on registering Fibank transaction: ' . $register_transaction_error;
                $order->add_order_note($error_message);

                wc_add_notice($error_message, 'error');

                return;
            }
            
            $fibank_transaction_id = $register_transaction_result['transaction_id'];
            update_post_meta( $order->get_id(), '_fibank_transaction_id', $fibank_transaction_id );
            
            $fiban_payment_base_url = $this->test_mode ? 'https://mdpay-test.fibank.bg/ecomm/ClientHandler' : 'https://mdpay.fibank.bg/ecomm/ClientHandler';
            $fibank_payment_url = $fiban_payment_base_url . '?trans_id=' . urlencode($fibank_transaction_id);

            return array(
                'result'   => 'success',
                'redirect' => $fibank_payment_url
            );
        }
    }
}

function add_maison_fibank_gateway_class($methods) {
    $methods[] = 'WC_Gateway_Maison_Fibank_Gateway';

    return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_maison_fibank_gateway_class' );
