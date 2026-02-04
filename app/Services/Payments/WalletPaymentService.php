<?php
namespace App\Services\Payments;

use App\Models\Certificate;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class WalletPaymentService
{
    public function payForCertificate(Certificate $certificate, int $payerUserId): Payment
    {
        return DB::transaction(function () use ($certificate, $payerUserId) {

            $event = $certificate->event;
            $amount = $event->price_per_person;

            $payment = Payment::query()->create([
                'payer_user_id' => $payerUserId,
                'amount'        => $amount,
                'method'        => 'wallet',
                'paid_at'       => now(),
            ]);

            // TODO: اینجا بعداً ولت واقعی کم می‌شود
            // Wallet::decrement(...)

            $certificate->update([
                'payment_id'        => $payment->id,
                'has_payment_issue' => false,
                'status'            => 'active',
            ]);

            return $payment;
        });
    }
}
