<?php

namespace App\Services\Payments;

use App\Models\Certificate;
use App\Models\Payment;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WalletPaymentService
{
    /**
     * پرداخت تکی یک گواهینامه با کیف پول
     */
    public function payForCertificate(
        Certificate $certificate,
        int $payerUserId
    ): PaymentResult {
        DB::transaction(function () use ($certificate, $payerUserId) {

            $user = User::query()
                ->with('wallet')
                ->lockForUpdate()
                ->findOrFail($payerUserId);

            if (! $user->wallet) {
                throw ValidationException::withMessages([
                    'wallet' => 'کیف پول برای این کاربر وجود ندارد.',
                ]);
            }

            $this->performWalletPayment($certificate, $user);
        });

        return new PaymentResult(
            success: true,
            message: 'پرداخت با موفقیت انجام شد.',
            paidCount: 1
        );
    }

    /**
     * پرداخت دسته‌جمعی گواهینامه‌ها با کیف پول
     */
    public function payForCertificates(
        Collection $certificates,
        int $payerUserId
    ): PaymentResult {
        $payableCertificates = $certificates
            ->filter(fn (Certificate $c) => $c->has_payment_issue);

        if ($payableCertificates->isEmpty()) {
            return new PaymentResult(
                success: false,
                message: 'هیچ گواهینامه‌ی قابل پرداختی انتخاب نشده است.'
            );
        }

        DB::transaction(function () use (
            $payableCertificates,
            $payerUserId,
            &$paidCount,
            &$failedCount
        ) {

            $user = User::query()
                ->with('wallet')
                ->lockForUpdate()
                ->findOrFail($payerUserId);

            if (! $user->wallet) {
                throw ValidationException::withMessages([
                    'wallet' => 'کیف پول برای این کاربر وجود ندارد.',
                ]);
            }

            $totalAmount = $payableCertificates
                ->sum(fn (Certificate $c) => $c->event->price_per_person);

            if ($user->wallet->balance < $totalAmount) {
                throw ValidationException::withMessages([
                    'wallet' => 'موجودی کیف پول برای پرداخت همه گواهینامه‌ها کافی نیست.',
                ]);
            }

            $paidCount   = 0;
            $failedCount = 0;

            foreach ($payableCertificates as $certificate) {
                try {
                    $this->performWalletPayment($certificate, $user);
                    $paidCount++;
                } catch (\Throwable $e) {
                    $failedCount++;
                    report($e);
                    // ادامه بده؛ کل batch نباید fail شود
                }
            }
        });

        return new PaymentResult(
            success: $paidCount > 0,
            message: $failedCount === 0
                ? 'پرداخت همه گواهینامه‌ها با موفقیت انجام شد.'
                : 'پرداخت انجام شد، اما برخی گواهینامه‌ها تسویه نشدند.',
            paidCount: $paidCount,
            failedCount: $failedCount
        );
    }

    /**
     * هسته‌ی پرداخت با کیف پول (بدون transaction و notification)
     * فقط یک گواهینامه را تسویه می‌کند یا exception می‌اندازد
     */
    private function performWalletPayment(
        Certificate $certificate,
        User $user
    ): void {
        if (! $certificate->has_payment_issue) {
            throw ValidationException::withMessages([
                'certificate' => 'این گواهینامه قبلاً تسویه شده است.',
            ]);
        }

        $amount = $certificate->event->price_per_person;

        if ($user->wallet->balance < $amount) {
            throw ValidationException::withMessages([
                'wallet' => 'اعتبار کیف پول کافی نیست.',
            ]);
        }

        $payment = Payment::create([
            'payer_user_id' => $user->id,
            'amount'        => $amount,
            'method'        => 'wallet',
            'paid_at'       => now(),
        ]);

        WalletTransaction::create([
            'wallet_id'   => $user->wallet->id,
            'payment_id'  => $payment->id,
            'amount'      => $amount,
            'type'        => 'withdraw',
            'description' => 'پرداخت گواهینامه #' . $certificate->id,
        ]);

        $user->wallet->decrement('balance', $amount);

        $certificate->update([
            'payment_id'        => $payment->id,
            'has_payment_issue' => false,
        ]);
    }
}
