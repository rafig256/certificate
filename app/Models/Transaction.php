<?php

namespace App\Models;

use App\Enums\Pay_status;
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
            'status' => Pay_status::class,
        ];
    }
}
