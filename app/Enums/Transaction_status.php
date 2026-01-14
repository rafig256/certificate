<?php

namespace App\Enums;

enum Transaction_status: string
{
    case draft = 'draft';
    case reject = 'reject';
    case cancel = 'cancel';
    case payed = 'payed';

    public function label()
    {
        return match ($this){
            self::cancel => 'کنسل توسط کاربر',
            self::payed => 'تکمیل پرداخت',
            self::reject => 'پرداخت ناموفق',
            self::draft => 'پرداخت نشده',
        };
    }

    public function getColor()
    {
        return match ($this){
            self::cancel => 'warning',
            self::payed => 'success',
            self::reject => 'danger',
            self::draft => 'warning',
        };
    }

}
