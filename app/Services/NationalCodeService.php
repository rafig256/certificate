<?php

namespace App\Services;

class NationalCodeService
{
    public function isValid(string $code):bool
    {
        // حذف کاراکترهای غیر عددی
        if (!preg_match('/^\d{10}$/', $code)) {
            return false;
        }

        // همه ارقام یکسان نباشند
        if (preg_match('/^(\d)\1{9}$/', $code)) {
            return false;
        }

        $check = (int) $code[9];
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $code[$i]) * (10 - $i);
        }

        $remainder = $sum % 11;

        return
            ($remainder < 2 && $check === $remainder) ||
            ($remainder >= 2 && $check === (11 - $remainder));
    }
}
