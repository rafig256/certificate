<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBlock extends Model
{
    protected $casts = [
        'payload' => 'array',
        'is_active' => 'boolean',
    ];
}
