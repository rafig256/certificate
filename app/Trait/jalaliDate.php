<?php

namespace App\Trait;

use App\Services\JalaliDateService;

trait jalaliDate
{
    protected function JalaliService() :JalaliDateService
    {
        return app(JalaliDateService::class);
    }

    public function getCreatedAtJalaliAttribute(): ?string
    {
        return $this->JalaliService()->toJalali($this->created_at);
    }

    public function getUpdatedAtJalaliAttribute(): ?string
    {
        return $this->JalaliService()->toJalali($this->updated_at);
    }

    public function getJalaliAttribute():array
    {
        $result = [];

        foreach ($this->getAttributes() as $field => $value) {
            if(str_ends_with($field , '_at')){
                $result[$field] = $this->JalaliService()->toJalali($value);
                $result[$field."_datetime"] = $this->JalaliService()->toJalali($value, true);
            }
        }
        return $result;
    }
}
