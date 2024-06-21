<?php

namespace ZarulIzham\EcommercePayment\Messages;

use Illuminate\Support\Facades\Validator;
use ZarulIzham\EcommercePayment\Contracts\Message as Contract;
use ZarulIzham\EcommercePayment\Models\EcommerceTransaction;

class AuthorizationRequest implements Contract
{
    private $dataToSign;
    private $reference_id;
    private $transactionable_id;
    private $transactionable_type;
    private $amount;
    private $merchantAccountNo;
    private $responseType;
    private $directUrl;
    private $transactionType;
    private $description;
    private $email;
    private $country_code;
    private $mobile_number;

    /**
     * Message Url
     */
    public $url;

    public function __construct()
    {
        $this->password = config('ecommerce.password');
        $this->merchantAccountNo = config('ecommerce.merchant_account_no');
        $this->paymentWindowUrl = config('ecommerce.payment_window_url');
        $this->directPath = config('ecommerce.direct_path');
        $this->directUrl = config('ecommerce.direct_url');
        $this->callbackPath = config('ecommerce.callback_path');
        $this->callbackUrl = config('ecommerce.callback_url');
        $this->transactionType = config('ecommerce.transaction_type');
        $this->responseType = config('ecommerce.response_type');
    }

    /**
     * handle a message
     *
     * @param array $options
     * @return mixed
     */
    public function handle($options)
    {
        $data = Validator::make(
            $options,
            [
                'TXN_DESC' => 'required',
                'AMOUNT' => 'required|numeric',
                'reference_id' => 'nullable',
                'transactionable_id' => 'nullable|numeric',
                'transactionable_type' => 'nullable|string|max:100',
                'email' => 'nullable|email:rfc,dns',
                'country_code' => 'nullable|numeric|min:1|max:999',
                'mobile_number' => 'nullable|string|min:9|max:12',
            ],
            [
                'AMOUNT.required' => __('Amount is required.'),
                'AMOUNT.numeric' => __('Amount must be numeric.'),
                'TXN_DESC.required' => __('Transaction Description is required.'),
            ],
        )->validate();

        foreach ($data as $index => $value) {
            $this->$index = $value;
        }
        $this->amount = $data['AMOUNT'];
        $this->description = $data['TXN_DESC'];
        $this->signMessage($data);
        $this->saveTransaction();

        return $this;
    }

    /**
     * returns collection of all fields
     *
     * @return \Illuminate\Support\Collection
     */
    public function list()
    {
        return collect($this->dataToSign);
    }

    /**
     * Save request to transaction
     */
    public function saveTransaction()
    {
        $transaction = new EcommerceTransaction();
        $transaction->reference_id = $this->reference_id;
        $transaction->transactionable_id = $this->transactionable_id;
        $transaction->transactionable_type = $this->transactionable_type;
        $transaction->amount = $this->amount;
        $transaction->request_payload = $this->list();
        $transaction->save();
    }

    public function signMessage($data)
    {
        $this->dataToSign = array_merge($data, [
            'AMOUNT' => $this->amount,
            'MERCHANT_ACC_NO' => $this->merchantAccountNo,
            'CARDHOLDER_INFO' => $this->cardHolderInfo(),
            'MERCHANT_TRANID' => $this->reference_id,
            'RESPONSE_TYPE' => $this->responseType,
            'RETURN_URL' => $this->directUrl,
            'TRANSACTION_TYPE' => $this->transactionType,
            'TXN_DESC' => $this->description,
        ]);

        ksort($this->dataToSign);

        unset(
            $this->dataToSign['reference_id'],
            $this->dataToSign['transactionable_id'],
            $this->dataToSign['transactionable_type'],
            $this->dataToSign['mobile_number'],
            $this->dataToSign['country_code'],
            $this->dataToSign['email'],
        );

        $message = config('ecommerce.password') .  implode('', array_values($this->dataToSign));
        $signature = hash('sha512', $message);
        $this->dataToSign['SECURE_SIGNATURE'] = $signature;
    }

    public function cardHolderInfo()
    {
        return json_encode([
            'emailAdd' => $this->email,
            'mobilePhone' => [
                'cc' => $this->country_code,
                'subscriber' => $this->mobile_number,
            ]
        ]);
    }
}
