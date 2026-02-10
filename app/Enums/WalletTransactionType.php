<?php

namespace App\Enums;

enum WalletTransactionType :string
{
    case Deposit = 'deposit';
    case Withdraw = 'withdraw';
    case Refund = 'refund';
    case Adjustment = 'adjustment';

    public function label(): string
    {
        return match ($this) {
            self::Deposit    => 'واریز',
            self::Withdraw   => 'برداشت',
            self::Refund     => 'بازگشت وجه',
            self::Adjustment => 'اصلاح حساب',
        };
    }
}
