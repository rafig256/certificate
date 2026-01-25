<?php

namespace App\Rules;

use App\Services\NationalCodeService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidNationalCode implements ValidationRule
{
        public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $service = app(NationalCodeService::class);

        if (! $service->isValid((string) $value)) {
            $fail('کد ملی وارد شده معتبر نیست.');
        }
    }
}
