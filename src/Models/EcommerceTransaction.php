<?php

namespace ZarulIzham\EcommercePayment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EcommerceTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transactionable_id',
        'transactionable_type',
        'reference_id',
        'transaction_id',
        'amount',
        'response_code',
        'completed_at',
        'response_description',
        'request_payload',
        'response_payload',
    ];

    protected $casts = [
        'request_payload' => 'object',
        'response_payload' => 'object',
    ];

    public function transactionable() : MorphTo
    {
        return $this->morphTo();
    }
}
