<?php

namespace App\Filament\Widgets;

use App\Enums\SystemStatus;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public function __construct()
    {
        self::$pollingInterval = config('core.filament.widget.polling_interval');
    }

    protected function getStats(): array
    {
        return [
            $this->buildAdminsStat(),
            $this->buildUsersStat(),
            $this->buildEmployersStat(),
            $this->buildEmployeesStat(),
        ];
    }

    private function buildAdminsStat(): Stat
    {
        // TODO clear these duplicated codes
        $registeredAdmins = Admin::select(['created_at'])->get();
        $totalAdmins = $registeredAdmins->count();
        $adminChart = $registeredAdmins->groupBy(function ($admin) {
            $intervalMinutes = floor($admin->created_at->diffInMinutes(now()) / 5) * 5;

            return sprintf('%02d:%02d', $intervalMinutes / 60, $intervalMinutes % 60);
        })->map(function ($item) {
            return $item->count();
        })->values()->toArray();

        $adminChartCount = count($adminChart);

        $descriptionIcon = 'heroicon-m-arrow-trending-';
        optional($adminChart)[$adminChartCount - 1] > optional($adminChart)[$adminChartCount - 2] ?? 0 ? $descriptionIcon .= 'up' : $descriptionIcon .= 'down';

        // TODO - change with actual raising or falling icon, also description and color
        return Stat::make('Total Admins', $totalAdmins)
            ->icon('heroicon-o-user-group')
            ->description('TODO')
            ->descriptionIcon($descriptionIcon)
            ->chart($adminChart)
            ->color('success');
    }

    private function buildUsersStat(): Stat
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
        optional($userChart)[$userChartCount - 1] > optional($userChart)[$userChartCount - 2] ?? 0 ? $descriptionIcon .= 'up' : $descriptionIcon .= 'down';

        return Stat::make('Total Users', $totalUsers)
            ->icon('heroicon-o-user-group')
            // TODO - change with actual raising or falling icon, also description and color
            ->description('TODO')
            ->descriptionIcon($descriptionIcon)
            ->chart($userChart)
            ->color('success');
    }

    private function buildEmployersStat(): Stat
    {
        $registeredEmployers = Employer::select(['created_at', 'system_status'])->get();
        $activeEmployers = $registeredEmployers->where('system_status', SystemStatus::ACTIVE->value)->count();
        $employersUnderPunishment = $registeredEmployers->where('system_status', SystemStatus::UNDER_PUNISHMENT->value)->count();

        $totalEmployers = $registeredEmployers->count();
        $employerChart = $registeredEmployers->groupBy(function ($employer) {
            $intervalMinutes = floor($employer->created_at->diffInMinutes(now()) / 5) * 5;

            return sprintf('%02d:%02d', $intervalMinutes / 60, $intervalMinutes % 60);
        })->map(function ($item) {
            return $item->count();
        })->values()->toArray();

        return Stat::make('Total Employers', $totalEmployers)
            ->icon('heroicon-o-user-group')
            // TODO - change with actual raising or falling icon, also description and color
            ->description("ACTIVE - $activeEmployers | UNDER PUNISHMENT - $employersUnderPunishment")
            ->chart($employerChart);
    }

    private function buildEmployeesStat(): Stat
    {
        $registeredEmployees = Employee::select(['created_at', 'system_status'])->get();
        $activeEmployees = $registeredEmployees->where('system_status', SystemStatus::ACTIVE->value)->count();
        $employeesUnderPunishment = $registeredEmployees->where('system_status', SystemStatus::UNDER_PUNISHMENT->value)->count();

        $totalEmployees = $registeredEmployees->count();
        $employerChart = $registeredEmployees->groupBy(function ($employer) {
            $intervalMinutes = floor($employer->created_at->diffInMinutes(now()) / 5) * 5;

            return sprintf('%02d:%02d', $intervalMinutes / 60, $intervalMinutes % 60);
        })->map(function ($item) {
            return $item->count();
        })->values()->toArray();

        return Stat::make('Total Employees', $totalEmployees)
            ->icon('heroicon-o-user-group')
            // TODO - change with actual raising or falling icon, also description and color
            ->description("ACTIVE - $activeEmployees | UNDER PUNISHMENT - $employeesUnderPunishment")
            ->chart($employerChart);
    }
}
