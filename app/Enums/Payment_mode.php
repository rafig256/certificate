<?php

namespace App\Enums;

enum Payment_mode: string {
    case Participant = 'Participant';
    case Free = 'Free';

    public function label():string
    {
        return match ($this){
            self::Participant => __('fields.payment_by_user'),
            self::Free => __('fields.payment_free'),
        };
    }
}
