<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

require_once app_path('Lib/jdf.php');

abstract class BaseModel extends Model
{

    protected function jalaliDate($value): ?string
    {
        if (!$value) return null;
        return jdate('Y/m/d', strtotime($value));
    }

    protected function jalaliDateTime($value): ?string
    {
        if (!$value) return null;
        return jdate('Y/m/d H:i', strtotime($value));
    }

    /* =======================
     |  Accessorهای پیش‌فرض
     ======================= */

    public function getCreatedAtAttribute($value): ?string
    {
        return $this->jalaliDateTime($value);
    }

    public function getUpdatedAtAttribute($value): ?string
    {
        return $this->jalaliDateTime($value);
    }

    /* =======================
     |  Accessor عمومی برای ستون‌های سفارشی
     |  مخصوص Filament
     ======================= */

    public function getJalaliAttribute(): array
    {
        $result = [];

        foreach ($this->getAttributes() as $key => $value) {
            if ($this->isDateField($key)) {
                $result[$key] = $this->jalaliDate($value);
                $result[$key . '_datetime'] = $this->jalaliDateTime($value);
            }
        }

        return $result;
    }

    protected function isDateField(string $field): bool
    {
        return str_ends_with($field, '_at')
            || in_array($field, $this->dates ?? []);
    }
}
