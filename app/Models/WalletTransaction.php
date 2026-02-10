<?php

namespace App\Models;

use App\Enums\WalletTransactionType;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{

    protected $guarded = [];

    protected $casts = [
        'type' => WalletTransactionType::class,
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
