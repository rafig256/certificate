<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateHolder extends Model
{
    /** @use HasFactory<\Database\Factories\CertificateHolderFactory> */
    use HasFactory;

    protected $guarded = [];

    public function certificate()
    {
        return $this->hasMany(Certificate::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
