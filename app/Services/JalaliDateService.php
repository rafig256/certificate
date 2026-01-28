<?php

namespace App\Services;

use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class JalaliDateService
{
    /**
     * تبدیل تاریخ میلادی به شمسی
     *
     * @param \DateTime|string|null $date
     * @param bool $withTime
     * @return string|null
     */
    public function toJalali($date, bool $withTime = false): ?string
    {
        if (!$date) {
            return null;
        }

        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);
        $format = $withTime ? 'Y/m/d H:i' : 'Y/m/d';

        return Jalalian::fromDateTime($carbonDate)->format($format);
    }
}
