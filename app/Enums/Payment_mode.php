<?php

namespace App\Enums;

enum Payment_mode: string {
    case OrganizerPays = 'OrganizerPays';
    case Participant = 'Participant';
    case Free = 'Free';

    public function label():string
    {
        return match ($this){
            self::OrganizerPays => __('fields.payment_organizer'),
            self::Participant => __('fields.payment_participant'),
            self::Free => __('fields.payment_free'),
        };
    }
}
