<?php

namespace ZarulIzham\EcommercePayment\Messages;

use ZarulIzham\EcommercePayment\DataObjects\RedirectData;
use ZarulIzham\EcommercePayment\Models\EcommerceTransaction;
use ZarulIzham\EcommercePayment\Contracts\Message as Contract;

class AuthorizationConfirmation implements Contract
{
    public $responseData;

    /**
     * handle a message
     *
     * @param array $options
     * @return mixed
     */
    public function handle($options)
    {

        $this->responseData = RedirectData::from($options);
        $this->saveTransaction();

        return $this;
    }

    /**
     * Save response to transaction
     *
     * @return \ZarulIzham\EcommercePayment\Models\EcommerceTransaction;
     */
    public function saveTransaction(): EcommerceTransaction
    {
        $data = array_merge($this->responseData->toArray(), [
            'response_payload' => $this->responseData->toArray(),
        ]);

        return EcommerceTransaction::updateOrCreate([
            'reference_id' => $this->responseData->merchant_transaction_id,
        ], $data);
    }
}
