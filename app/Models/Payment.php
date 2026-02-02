<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
