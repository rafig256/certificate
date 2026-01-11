<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected static $unguarded = true;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
