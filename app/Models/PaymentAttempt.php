<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAttempt extends Model
{
    protected $fillable = [
        'payer_user_id',
        'amount',
        'gateway',
        'authority',
        'ref_id',
        'status',
        'gateway_payload',
        'payment_id',
    ];

    protected $casts = [
        'gateway_payload' => 'array',
    ];


}
