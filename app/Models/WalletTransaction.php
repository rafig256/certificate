<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{

    protected $guarded = [];

    public const TYPE_LABELS = [
        'deposit'     => 'واریز',
        'withdraw'    => 'برداشت',
        'refund'      => 'بازگشت وجه',
        'adjustment'  => 'اصلاح حساب',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? 'نامشخص';
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
