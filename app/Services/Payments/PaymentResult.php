<?php

namespace App\Services\Payments;

class PaymentResult
{
    public function __construct(
        public bool $success,
        public string $message,
        public int $paidCount = 0,
        public int $failedCount = 0,
    ) {}
}
