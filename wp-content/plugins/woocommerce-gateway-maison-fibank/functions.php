<?php
function get_client_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) // check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) // to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function maison_fibank_update_order_status($fibank_client, $order, $fibank_transaction_id, $client_ip, $logging_context) {
    $fibank_transaction_status_response = $fibank_client->get_transaction_status($fibank_transaction_id, $client_ip);
    $eol = PHP_EOL;
    $fibank_transaction_status_data = $fibank_transaction_status_response['data'];
    $transaction_logging_message = "${eol}TRANSACTION_ID: $fibank_transaction_id${eol}$fibank_transaction_status_data";
    if ($order->has_status(wc_get_is_paid_statuses())) {
        $order->add_order_note("$logging_context: Fibank order already payed.$transaction_logging_message");
    }
    else {
        if ($fibank_transaction_status_response['success']) {
            $order->add_order_note("$logging_context: Fibank payment completed.$transaction_logging_message");
            $order->payment_complete();
        }
        else {
            $order->update_status('failed', "$logging_context: Fibank payment error.$transaction_logging_message");
        }
    }
}