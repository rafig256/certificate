<?php

namespace App\Rules;

use App\Services\NationalCodeService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidNationalCode implements ValidationRule
{
    public function __construct(
        protected NationalCodeService $service
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->service->isValid((string) $value)) {
            $fail('کد ملی وارد شده معتبر نیست.');
        }
    }
}
