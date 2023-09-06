<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $registeredUsers = User::select(['created_at'])->get();
        $totalUsers = $registeredUsers->count();
        $userChart = $registeredUsers->groupBy(function ($user) {
            $intervalMinutes = floor($user->created_at->diffInMinutes(now()) / 5) * 5;

            return sprintf('%02d:%02d', $intervalMinutes / 60, $intervalMinutes % 60);
        })->map(function ($item) {
            return $item->count();
        })->values()->toArray();

        $userChartCount = count($userChart);
        $descriptionIcon = 'heroicon-m-arrow-trending-';
        $userChart[$userChartCount - 1] > $userChart[$userChartCount - 2] ? $descriptionIcon .= 'up' : $descriptionIcon .= 'down';

        return [
            Stat::make('Total Users', $totalUsers)
                ->icon('heroicon-o-user-group')
                ->description('5 minutes interval')
                ->descriptionIcon($descriptionIcon)
                ->chart($userChart)
                ->color('success'),
        ];
    }
}
