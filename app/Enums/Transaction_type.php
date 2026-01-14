<?php

namespace App\Enums;

enum Transaction_type: string
{
    case single = 'single';
    case multi = 'multi';

    public function label(){
        return match ($this){
            self ::single => 'واریز تکی',
            self::multi => 'واریز دسته جمعی',
        };
    }
}
