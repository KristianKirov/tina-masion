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

            include_once(dirname(__FILE__) . '/maison-fibank-client.php');

            $order_currency = $order->get_currency();
            $order_total = apply_filters('maison_fibank_cart_total_to_bgn', $order->get_total(), $order_currency);
            if ($order_total === false) {
                $not_supported_currency_error_message = 'Payments in ' . $order_currency . ' are not supported by the ' . $this->title . ' payment processor.';

                $order->add_order_note($not_supported_currency_error_message);
                wc_add_notice($not_supported_currency_error_message, 'error');

                return;
            }

            $client_ip = get_client_ip();
            $fibank_client = new Maison_Fibank_Client($this->test_mode, $this->certificate_path, $this->certificate_password);
            $register_transaction_result = $fibank_client->register_transaction($order_total, Maison_Fibank_Currency::$BGN, $client_ip, $order_id);
            if (!$register_transaction_result['success']) {
                $register_transaction_error = $register_transaction_result['data'];
                $error_message = 'Error on registering Fibank transaction: ' . $register_transaction_error;
                $order->add_order_note($error_message);

                wc_add_notice($error_message, 'error');

                return;
            }
            
            $fibank_transaction_id = $register_transaction_result['transaction_id'];
            add_post_meta( $order->get_id(), '_fibank_transaction_id', $fibank_transaction_id, false );
            wp_insert_post(array('post_type' => 'maison_fibank_trans', 'post_title' => $fibank_transaction_id, 'post_excerpt' => $client_ip));
            $order_update_message = "Registered Fibank transaction with id $fibank_transaction_id for $order_total BGN.";
            if($order->get_status() == 'pending') {
                $order->add_order_note($order_update_message);
            }
            else {
                $order->update_status('pending', $order_update_message);
            }
            
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

function maison_fibank_register_transactions_post_type() {
    register_post_type('maison_fibank_trans', array(
        'labels' => array(
            'name' => __( 'Fibank Transactions', 'woocommerce-gateway-maison-fibank' ),
            'singular_name' => __( 'Fibank Transaction', 'woocommerce-gateway-maison-fibank' )
        ),
        'description' => __('Stores all pending Fibank transactions', 'woocommerce-gateway-maison-fibank'),
        'supports' => array('title', 'excerpt'),
        'public' => true,
        'has_archive' => false,
        'hierarchical' => false,
        'has_archive' => false,
        'exclude_from_search' => true
    ));
}
add_action( 'init', 'maison_fibank_register_transactions_post_type' );

function maison_custom_checkout_field_display_admin_order_meta($order){
	$order_id = $order->get_id();
	$order_payment_method = $order->get_payment_method();
	if ($order_payment_method == 'maison_fibank_gateway') {
		$fibank_transaction_ids = get_post_meta($order_id, '_fibank_transaction_id');
		$fibank_transaction_ids_markup = implode('<br />', $fibank_transaction_ids);
		echo '<p><strong>Fibank Transaction Ids:</strong><br />' . $fibank_transaction_ids_markup . '</p>';
	}
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'maison_custom_checkout_field_display_admin_order_meta', 10, 1 );

function maison_woocommerce_order_data_store_cpt_get_orders_query($query, $query_vars) {
	if (!empty($query_vars['fibank_transaction_id'] ) ) {
		$query['meta_query'][] = array(
			'key' => '_fibank_transaction_id',
			'value' => esc_attr($query_vars['fibank_transaction_id']),
		);
	}

	return $query;
}
add_filter('woocommerce_order_data_store_cpt_get_orders_query', 'maison_woocommerce_order_data_store_cpt_get_orders_query', 10, 2 );

function update_pending_orders_status() {
    try {
        $pending_transactions = get_posts(array(
            'post_type' => 'maison_fibank_trans',
            'posts_per_page' => -1,
            'post_status' => 'any',
            'date_query' => array(
                'before' => '30 minutes ago' 
            )));
        if (empty($pending_transactions)) {
            return;
        }
    
        $fibank_settings = get_option('woocommerce_maison_fibank_gateway_settings');
        $is_fibank_plugin_enabled = $fibank_settings['enabled'] == 'yes';
        $is_fibank_plugin_in_test_mode = $fibank_settings['test_mode'] == 'yes';
        include_once(dirname(__FILE__) . '/maison-fibank-client.php');
        $fibank_client = new Maison_Fibank_Client($is_fibank_plugin_in_test_mode, $fibank_settings['certificate_path'], $fibank_settings['certificate_password']);

        foreach ($pending_transactions as $pending_transaction) {
            $fibank_transaction_id = $pending_transaction->post_title;
            try {
                $orders = wc_get_orders(array('limit' => 1, 'fibank_transaction_id' => $fibank_transaction_id));
                
                if (empty($orders)) {
                    error_log("PENDING ORDERS: No order found for fibank transaction with id $fibank_transaction_id");
                }
                else {
                    $order = $orders[0];
                    $order_client_ip = $pending_transaction->post_excerpt;
                    maison_fibank_update_order_status($fibank_client, $order, $fibank_transaction_id, $order_client_ip, 'PENDING ORDERS');
                }
        
                wp_delete_post($pending_transaction->ID, true);
            }
            catch (Exception $iex) {
                error_log("PENDING ORDERS: Transaction id:$fibank_transaction_id $iex");
            }
        }
    }
    catch (Exception $ex) {
        error_log($ex);
    }
}
add_action('woocommerce_cancel_unpaid_orders', 'update_pending_orders_status', 1);