<?php

namespace App\Filament\Widgets;

use App\Models\Organization;
use App\Models\Signatory;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrganizationSignerStats extends StatsOverviewWidget
{
    
    protected function getStats(): array
    {
        return [
            Stat::make('سازمان‌ها', Organization::count())
                ->description('تعداد کل سازمان‌های ثبت‌شده'),

            Stat::make('امضاکنندگان', Signatory::count())
                ->description('تعداد کل امضاکنندگان'),
        ];
    }
}
