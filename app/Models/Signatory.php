<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class)
            ->withTimestamps();
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class)->withTimestamps();
    }
}
