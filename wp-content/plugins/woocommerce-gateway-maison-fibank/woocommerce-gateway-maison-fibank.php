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

add_action( 'plugins_loaded', 'init_maison_fibank_gateway_class' );
function init_maison_fibank_gateway_class() {
    class WC_Gateway_Maison_Fibank_Gateway extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'maison_fibank_gateway';
            $this->icon = plugin_dir_url( __FILE__ ) . 'assets/3d_secure.gif';
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
                add_action( 'woocommerce_api_wc_gateway_maison_fibank', 'maison_fibank_payment_response' );
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

            // TODO: register transaction with description = order_id

            $fibank_transaction_id = 'asdasd';
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

function maison_fibank_payment_response() {
    if ( empty( $_POST )) {
        wp_die( 'Maison Fibank Request Failure', 'Maison Fibank', array( 'response' => 500 ) );
    }

    $fibank_transaction_id = $_POST['trans_id'];
    $order_id = $_POST['description'];

    $order = wc_get_order($order_id);
    $stored_fibank_transaction_id = get_post_meta( $order->get_id(), '_fibank_transaction_id', true );
    if ($stored_fibank_transaction_id != $fibank_transaction_id) {
        
    }

    // if ok
    // Order is already payed
        if (!$order->has_status( wc_get_is_paid_statuses() )) {
            $order->add_order_note( $note );
            $order->payment_complete( $txn_id );
        }
    // Else mark as failed or canceled

    $order_processing_redirect_url = '';
    if (true) {
        $order_processing_redirect_url = $order->get_checkout_order_received_url();
    }
    else {
        $order_processing_redirect_url = $order->get_cancel_order_url();
    }

    wp_redirect( $order_processing_redirect_url );
    exit;
}

function add_maison_fibank_gateway_class($methods) {
    $methods[] = 'WC_Gateway_Maison_Fibank_Gateway';

    return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_maison_fibank_gateway_class' );
