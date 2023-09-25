<?php

namespace ZarulIzham\EcommercePayment\Enums;

use Spatie\LaravelData\Casts\Cast;
use ZarulIzham\EcommercePayment\Interfaces\HasLabel;

enum TransactionStatus : string implements HasLabel {
    case PENDING_NOT_AUTHORIZED = "N";
    case AUTHORIZED = "A";
    case CAPTURED = "C";
    case SALES_COMPLETED = "S";
    case VOID = "V";
    case CHARGE_BACK = 'CB';
    case EXCEPTION = "E";
    case NOT_APPROVED = 'F';
    case BLACKLISTED = 'BL';
    case BLOCKED =  'B';

    public function label() : string
    {
        return match($this) {
            self::PENDING_NOT_AUTHORIZED => 'Pending/Not Authorized',
            self::AUTHORIZED => 'Authorized',
            self::CAPTURED => 'Captured',
            self::SALES_COMPLETED => 'Sales Completed',
            self::VOID => 'Void',
            self::EXCEPTION => 'Error/Exception Occurred',
            self::NOT_APPROVED => 'Not Approved',
            self::CHARGE_BACK => 'Charge Back',
            self::BLACKLISTED => 'Blacklisted',
            self::BLOCKED => 'Blocked',
            default => 'Unknown',
        };
    }

    public function group(): string
    {
        return match ($this) {
            self::PENDING_NOT_AUTHORIZED, self::AUTHORIZED, self::CAPTURED => 'Pending',
            self::SALES_COMPLETED => 'Paid',
            self::VOID, self::EXCEPTION, self::NOT_APPROVED, self::CHARGE_BACK, self::BLACKLISTED, self::BLOCKED => 'Failed',
            default => $this,
        };
    }
}
