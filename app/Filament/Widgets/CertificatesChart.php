<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CertificatesChart extends ChartWidget
{
    protected static ?string $heading = 'روند صدور گواهینامه‌ها';

    protected function getData(): array
    {
        $totalCertificates = Certificate::count();

        $certificatesPerDay = Certificate::query()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => "گواهینامه‌ها ({$totalCertificates})",
                    'data' => $certificatesPerDay->pluck('count')->toArray(),
                    'fill' => false,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $certificatesPerDay->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
