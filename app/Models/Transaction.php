<?php

namespace App\Models;

use App\Enums\Transaction_status;
use App\Enums\Transaction_type;
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

    protected function casts(): array
    {
        return [
            'status' => Transaction_status::class,
            'type' => Transaction_type::class,
        ];
    }
}
