<?php

require_once 'functions.php';

class Gateway_Maison_Fibank_Gateway_Handler {
    public function __construct($test_mode, $certificate_path, $certificate_password) {
        $this->test_mode = $test_mode;
        $this->certificate_path = $certificate_path;
        $this->certificate_password = $certificate_password;

        add_action('woocommerce_api_wc_gateway_maison_fibank', array($this, 'maison_fibank_payment_response'));
    }

    public function maison_fibank_payment_response() {
        if (empty($_POST)) {
            wp_die('Maison Fibank Request Failure (No arguments provided)', 'Maison Fibank', array('response' => 500));
            exit;
        }
    
        $fibank_transaction_id = $_POST['trans_id'];
        if (empty($fibank_transaction_id)) {
            wp_die('Maison Fibank Request Failure (Missing arguments)', 'Maison Fibank', array('response' => 500));
            exit;
        }
    
        $orders = wc_get_orders(array('limit' => 1, 'fibank_transaction_id' => $fibank_transaction_id));
        if (empty($orders)) {
            wp_die('Maison Fibank Request Failure (No order found)', 'Maison Fibank', array('response' => 500));
            exit;
        }

        $order = $orders[0];
    
        include_once( dirname( __FILE__ ) . '/maison-fibank-client.php' );
        $fibank_client = new Maison_Fibank_Client($this->test_mode, $this->certificate_path, $this->certificate_password);
        $client_ip = get_client_ip();
        $fibank_transaction_status_response =  $fibank_client->get_transaction_status($fibank_transaction_id, $client_ip);
        if ($fibank_transaction_status_response['success']) {
            $fibank_transaction_status_success = $fibank_transaction_status_response['data'];
            // Order is not payed yet
            if (!$order->has_status(wc_get_is_paid_statuses())) {
                $order->add_order_note('Fibank payment completed. ' . $fibank_transaction_status_success);
                $order->payment_complete();
            }
            else {
                $order->add_order_note('Fibank order already payed. ' . $fibank_transaction_status_success);
            }
        }
        else {
            $fibank_transaction_status_error = $fibank_transaction_status_response['data'];
            $order->update_status('failed', 'Fibank payment error. ' . $fibank_transaction_status_error);
        }

        $order_processing_redirect_url = $order->get_checkout_order_received_url();
        wp_redirect( $order_processing_redirect_url );
        exit;
    }
}