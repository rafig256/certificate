<?php

namespace App\Enums;

enum Payment_mode: string {
    case ParticipantPays = 'ParticipantPays';
    case Free = 'Free';

    public function label():string
    {
        return match ($this){
            self::ParticipantPays => __('fields.payment_by_user'),
            self::Free => __('fields.payment_free'),
        };
    }
}
