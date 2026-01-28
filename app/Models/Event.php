<?php

namespace App\Models;

use App\Enums\Level;
use App\Enums\Payment_mode;
use App\Trait\jalaliDate;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use jalaliDate;
    protected $guarded = [];

    protected $casts = [
        'level' => Level::class,
        'payment_mode' => Payment_mode::class,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Organization::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function signatories()
    {
        return $this->belongsToMany(
            Signatory::class,
            'event_signatory',      // نام دقیق جدول pivot
            'event_id',             // FK این مدل
            'signatory_id'          // FK مدل مقابل
        )->withTimestamps();
    }

    public function certificates(){
        return $this->hasMany(Certificate::class);
    }

    public function blocks()
    {
        return $this->hasMany(EventBlock::class);
    }
}
