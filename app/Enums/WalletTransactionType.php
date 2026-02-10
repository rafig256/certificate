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
            self::Deposit    => __('fields.deposit'),
            self::Withdraw   => __('fields.withdraw'),
            self::Refund     => __('fields.refund'),
            self::Adjustment => __('fields.adjustment'),
        };
    }

    public function filamentColor(): string
    {
        return match ($this) {
            self::Deposit    => 'success',
            self::Withdraw   => 'danger',
            self::Refund     => 'info',
            self::Adjustment => 'warning',
        };
    }
}
