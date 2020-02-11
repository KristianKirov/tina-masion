<?php

class Maison_Fibank_Currency {
    public static $EUR = 978;
    public static $BGN = 975;
    public static $USD = 840;
}

class Maison_Fibank_Client {
    public function __construct($test_mode, $cert_path, $cert_pass) {
        if ($test_mode) {
            $this->url = 'https://mdpay-test.fibank.bg:9443/ecomm/MerchantHandler';
        }
        else {
            $this->url = 'https://mdpay.fibank.bg:10443/ecomm_v2/MerchantHandler';
        }

        $this->cert_path = $cert_path;
        $this->cert_pass = $cert_pass;
    }

    public function register_transaction($ammount, $currency, $client_ip, $description) {
        $ammount = sprintf("%0.2f", $ammount);
        $ammount = $ammount * 100;

        $data_to_post = array(
            'command' => 'V',
            'amount' => $ammount,
            'currency' => $currency,
            'client_ip_addr' => $client_ip,
            'description' => $description,
            'msg_type' => 'SMS'
        );

        $response = $this->execute_request($data_to_post);
        if (strlen($response) > 14 && substr($response, 0, 14) == "TRANSACTION_ID") {
            $transaction_id = substr($response, 16, 28);

            return array(
                'success' => true,
                'transaction_id' => $transaction_id
            );
        }
        else {
            return array(
                'success' => false,
                'data' => $response ? $response : 'Could not establish connection to: ' . $this->url
            );
        }
    }

    public function get_transaction_status($transaction_id, $client_ip) {
        $data_to_post = array(
            'command' => 'C',
            'trans_id' => $transaction_id,
            'client_ip_addr' => $client_ip
        );

        $response = $this->execute_request($data_to_post);
        if (strlen($response) >= 14 && substr($response, 0, 10) == "RESULT: OK") {
            return array(
                'success' => true,
                'data' => $response
            );
        }
        else {
            return array(
                'success' => false,
                'data' => $response ? $response : 'Could not establish connection to: ' . $this->url
            );
        }
    }

    private function execute_request($data_to_post) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_to_post));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->cert_path);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->cert_pass);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}