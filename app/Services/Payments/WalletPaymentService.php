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
    public function payForCertificate(Certificate $certificate, int $payerUserId): PaymentResult
    {
        if (! $certificate->has_payment_issue) {
            return new PaymentResult(
                success: false,
                message: 'این گواهینامه قبلاً تسویه شده است.'
            );
        }

        $amount = $certificate->event->price_per_person;

        DB::transaction(function () use ($certificate, $payerUserId, $amount) {

            $user = User::query()
                ->with('wallet')
                ->lockForUpdate()
                ->findOrFail($payerUserId);

            if (! $user->wallet) {
                throw ValidationException::withMessages([
                    'wallet' => 'کیف پول برای این کاربر وجود ندارد.',
                ]);
            }

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
        });

        return new PaymentResult(
            success: true,
            message: 'پرداخت با موفقیت انجام شد.',
            paidCount: 1
        );
    }

    public function payForCertificates(Collection $certificates, int $payerUserId): PaymentResult
    {
        $payableCertificates = $certificates
            ->filter(fn ($c) => $c->has_payment_issue);

        if ($payableCertificates->isEmpty()) {
            return new PaymentResult(
                success: false,
                message: 'هیچ گواهینامه‌ی قابل پرداختی انتخاب نشده است.'
            );
        }

        $totalAmount = $payableCertificates
            ->sum(fn (Certificate $certificate) => $certificate->event->price_per_person);

        $user = User::query()
            ->with('wallet')
            ->lockForUpdate()
            ->findOrFail($payerUserId);

        if (! $user->wallet) {
            throw ValidationException::withMessages([
                'wallet' => 'کیف پول برای این کاربر وجود ندارد.',
            ]);
        }

        if ($user->wallet->balance < $totalAmount) {
            throw ValidationException::withMessages([
                'wallet' => 'موجودی کیف پول برای پرداخت همه گواهینامه‌ها کافی نیست.',
            ]);
        }

        $paidCount   = 0;
        $failedCount = 0;

        DB::transaction(function () use ($payableCertificates, $payerUserId, &$paidCount, &$failedCount) {


            foreach ($payableCertificates as $certificate) {
                try {
                    $this->payForCertificate($certificate, $payerUserId);
                    $paidCount++;
                    }
                catch (\Throwable $e){
                    // این پرداخت خاص شکست خورده
                    $failedCount++;
                    report($e);
                    // ادامه بده، کل batch نباید fail شود
                    continue;
                }
            }
        });

        return new PaymentResult(
            success: $paidCount > 0,
            message: $failedCount === 0
                ? 'پرداخت همه گواهینامه‌ها با موفقیت انجام شد.'
                : 'پرداخت انجام شد، اما برخی گواهینامه‌ها تسویه نشدند.',
            paidCount: $paidCount,
            failedCount: $failedCount,
        );
    }
}
