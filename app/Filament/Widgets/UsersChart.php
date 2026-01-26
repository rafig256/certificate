<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'روند ثبت‌نام کاربران';

    protected function getData(): array
    {
        $totalUsers = User::count();

        $usersPerDay = User::query()
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
                    'label' => "کاربران ({$totalUsers})",
                    'data' => $usersPerDay->pluck('count')->toArray(),
                    'fill' => false,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $usersPerDay->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
