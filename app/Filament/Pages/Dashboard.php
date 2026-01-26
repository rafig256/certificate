<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Widgets\OrganizationSignerStats;
use App\Filament\Widgets\UsersChart;
use App\Filament\Widgets\CertificatesChart;

class Dashboard extends BaseDashboard
{

    public function getColumns(): int | array
    {
        return 12;
    }
    public function getWidgets(): array
    {
        return [
            // ردیف اول
//            AccountWidget::class,
//            FilamentInfoWidget::class,

            // ردیف دوم
            OrganizationSignerStats::class,

            // ردیف سوم
            UsersChart::class,
            CertificatesChart::class,
        ];
    }
}
