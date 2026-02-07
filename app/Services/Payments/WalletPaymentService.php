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
            ->sum(fn ($c) => $c->event->price_per_person);

        DB::transaction(function () use ($payableCertificates, $payerUserId, $totalAmount) {

            $user = User::query()
                ->with('wallet')
                ->lockForUpdate()
                ->findOrFail($payerUserId);

            if (! $user->wallet || $user->wallet->balance < $totalAmount) {
                throw ValidationException::withMessages([
                    'wallet' => 'اعتبار کیف پول برای پرداخت گروهی کافی نیست.',
                ]);
            }

            foreach ($payableCertificates as $certificate) {
                $this->payForCertificate($certificate, $payerUserId);
            }
        });

        return new PaymentResult(
            success: true,
            message: 'پرداخت گروهی با موفقیت انجام شد.',
            paidCount: $payableCertificates->count()
        );
    }
}
