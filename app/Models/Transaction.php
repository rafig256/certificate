<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected static $unguarded = true;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function certificates():HasMany
    {
        return $this->hasMany(Certificate::class);
    }
}
