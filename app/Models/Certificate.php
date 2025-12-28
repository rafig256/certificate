<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
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
}
