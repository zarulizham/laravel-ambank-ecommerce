<?php

namespace ZarulIzham\EcommercePayment;

use Illuminate\Support\Facades\Http;

class EcommercePayment
{
    protected static $transactionStatus = [
        'N' => 'Pending/Not Authorized',
        'A' => 'Authorized',
        'C' => 'Captured',
        'S' => 'Sales Completed',
        'V' => 'Void',
        'E' => 'Error/Exception Occurred',
        'F' => 'Not Approved',
        'BL' => 'Blacklisted',
        'B' => 'Blocked',
    ];

    public function query($merchantTransactionId, $amount)
    {
        $body = [
            'AMOUNT' => number_format($amount, 2),
            'MERCHANT_ACC_NO' => config('ecommerce.merchant_account_no'),
            'MERCHANT_TRANID' => $merchantTransactionId,
            'RESPONSE_TYPE' => 'XML',
            'TRANSACTION_TYPE' => '1',
        ];

        $body['SECURE_SIGNATURE'] = $this->signMessage($body);

        $response = Http::withoutVerifying()->asForm()->withOptions([
            'debug' => false,
        ])->post(config('ecommerce.query_url'), $body);

        $xml = simplexml_load_string($response->body());
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $array = self::array_change_key_case_recursive($array);
        $transaction = $array['transaction'];

        $transaction['transaction_status'] = self::$transactionStatus[$transaction['txn_status']];

        return $transaction;
    }

    protected function array_change_key_case_recursive($arr)
    {
        return array_map(function ($item) {
            if (is_array($item))
                $item = self::array_change_key_case_recursive($item);
            return $item;
        }, array_change_key_case($arr));
    }

    public function signMessage($data)
    {
        ksort($data);
        $message = config('ecommerce.password') .  implode('', array_values($data));
        return hash('sha512', $message);
    }
}
