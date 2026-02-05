<?php

namespace App\Models;

use App\Trait\jalaliDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use jalaliDate;
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($certificate) {
            do {
                $serial = \Illuminate\Support\Str::random(16);
            } while (static::where('serial', $serial)->exists());

            $certificate->serial = $serial;
            $certificate->issued_at = now();
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function certificateHolder(){
        return $this->belongsTo(CertificateHolder::class,'certificate_holder_id');
    }

    public function scopeVisibleTo($query, User $user)
    {
        // Administrator → همه
        if ($user->hasRole('administrator')) {
            return $query;
        }

        // Organizer → رویدادهای خودش
        if ($user->hasRole('organizer')) {
            return $query->whereHas('event', fn ($q) =>
            $q->where('organizer_id', $user->id)
            );
        }

        // Signer → رویدادهایی که امضا کرده
        if ($user->hasRole('signer')) {
            return $query->whereHas('event.signatories', fn ($q) =>
            $q->where('users.id', $user->id)
            );
        }

        // User → گواهینامه‌های خودش
        if ($user->hasRole('user')) {
            return $query->whereHas('certificateHolder', fn ($q) =>
            $q->where('user_id', $user->id)
            );
        }

        // سایرین → هیچ
        return $query->whereRaw('1 = 0');
    }

    public function transaction():BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function payment(){
        return $this->belongsTo(Payment::class);
    }

    // آیا این گواهینامه نیاز به پرداخت دارد؟
    public function requiresPayment(): bool
    {
        return $this->has_payment_issue === true
            && $this->payment_id === null;
    }

    // آیا پرداخت شده؟
    public function isPaid(): bool
    {
        return $this->has_payment_issue == 0;
    }

    // آیا رایگان است؟
    public function isFree(): bool
    {
        return $this->payment_id === null
            && $this->has_payment_issue === false;
    }

}
