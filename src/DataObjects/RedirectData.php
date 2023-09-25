<?php

namespace ZarulIzham\EcommercePayment\DataObjects;

use DateTime;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use ZarulIzham\EcommercePayment\Enums\TransactionStatus;

class RedirectData extends Data
{
    public function __construct(
        #[MapInputName('AMOUNT')]
        public float $amount,
        #[MapInputName('AUTH_ID')]
        public ?string $auth_id,
        #[MapInputName('CUSTOMER_ID')]
        public ?string $customer_id,
        #[MapInputName('FR_LEVEL')]
        public ?string $fr_level,
        #[MapInputName('FR_SCORE')]
        public ?string $fr_score,
        #[MapInputName('MERCHANT_TRANID')]
        public string $merchant_transaction_id,
        #[MapInputName('RESPONSE_CODE')]
        public string $response_code,
        #[MapInputName('RESPONSE_DESC')]
        public string $response_description,
        #[MapInputName('TRANSACTION_ID')]
        public string $transaction_id,
        #[
            MapInputName('TRAN_DATE'),
            WithCast(DateTimeInterfaceCast::class, format: 'd-m-Y H:i:s')
        ]
        public ?DateTime $transaction_date,
        #[
            MapInputName('SALES_DATE'),
            WithCast(DateTimeInterfaceCast::class, format: 'd-m-Y H:i:s')
        ]
        public ?DateTime $completed_at,
        #[
            MapInputName('TXN_STATUS'),
            WithCast(EnumCast::class)
        ]
        public TransactionStatus $transaction_status,
        #[MapInputName('SECURE_SIGNATURE')]
        public string $secure_signature,
    ) {
    }
}
