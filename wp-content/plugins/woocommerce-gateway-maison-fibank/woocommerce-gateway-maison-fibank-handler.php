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

        error_log("Notification received for fibank transaction with id $fibank_transaction_id");
        $fibank_pending_transaction = get_page_by_title($fibank_transaction_id, OBJECT, 'maison_fibank_trans');
        if ($fibank_pending_transaction) {
            wp_delete_post($fibank_pending_transaction->ID, true);
        }
        $orders = wc_get_orders(array('limit' => 1, 'fibank_transaction_id' => $fibank_transaction_id));
        if (empty($orders)) {
            error_log("No order found for fibank transaction with id $fibank_transaction_id");
            wp_die('Maison Fibank Request Failure (No order found)', 'Maison Fibank', array('response' => 500));
            exit;
        }

        $order = $orders[0];
    
        include_once( dirname( __FILE__ ) . '/maison-fibank-client.php' );
        $fibank_client = new Maison_Fibank_Client($this->test_mode, $this->certificate_path, $this->certificate_password);
        $client_ip = get_client_ip();
        maison_fibank_update_order_status($fibank_client, $order, $fibank_transaction_id, $client_ip, 'CALLBACK');

        $order_processing_redirect_url = $order->get_checkout_order_received_url();
        wp_redirect( $order_processing_redirect_url );
        exit;
    }
}