<?php

namespace App\Filament\Widgets;

use App\Models\Organization;
use App\Models\Signatory;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrganizationSignerStats extends StatsOverviewWidget
{
    protected int | string | array $columnSpan =[
        'default' => 6,
        'md' => 12,
    ];

    protected function getStats(): array
    {
        return [
            Stat::make('سازمان‌ها', Organization::count()),
            Stat::make('امضاکنندگان', Signatory::count()),
            Stat::make('تراکنش ها', Transaction::count())
        ];
    }
}

