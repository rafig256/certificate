<?php

namespace App\Enums;

enum Level: string
{
    case Beginner = 'Beginner';
    case Intermediate = 'Intermediate';
    case Advanced = 'Advanced';

    public function label(): string
    {
        return match ($this) {
            self::Beginner => __('fields.level_beginner'),
            self::Intermediate => __('fields.level_intermediate'),
            self::Advanced => __('fields.level_advanced'),
        };
    }
}
