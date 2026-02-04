<?php
namespace App\Services\Payments;

use App\Models\Certificate;
use App\Models\Payment;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WalletPaymentService
{
    public function payForCertificate(Certificate $certificate, int $payerUserId): void
    {
        if (! $certificate->has_payment_issue) {
            throw ValidationException::withMessages([
                'payment' => 'این گواهینامه قبلاً تسویه شده است.',
            ]);
        }

        $amount = $certificate->event->price_per_person;

        DB::transaction(function () use ($certificate, $payerUserId, $amount) {

            /** @var User $user */
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

            // 1️⃣ ساخت payment
            $payment = Payment::create([
                'payer_user_id' => $user->id,
                'amount'        => $amount,
                'method'        => 'wallet',
                'paid_at'       => now(),
            ]);

            // 2️⃣ ثبت تراکنش کیف پول (debit)
            WalletTransaction::create([
                'wallet_id'          => $user->wallet->id,
                'payment_id'         => $payment->id,
                'amount'             => $amount,
                'type'               => 'withdraw',
                'description'        => 'پرداخت گواهینامه #' . $certificate->id,
            ]);

            // 3️⃣ کسر موجودی
            $user->wallet->decrement('balance', $amount);

            // 4️⃣ اتصال پرداخت به گواهینامه
            $certificate->update([
                'payment_id'        => $payment->id,
                'has_payment_issue' => false,
            ]);
        });
    }
}
