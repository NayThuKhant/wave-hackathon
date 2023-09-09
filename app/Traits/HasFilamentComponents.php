<?php

namespace App\Traits;

use App\Enums\SystemStatus;
use Filament\Tables\Filters\SelectFilter;

trait HasFilamentComponents
{
    private static function buildSystemStatusFilter()
    {
        return SelectFilter::make('system_status')
            ->label('SYSTEM STATUS')
            ->options([
                SystemStatus::ACTIVE->value => SystemStatus::ACTIVE->value,
                SystemStatus::UNDER_PUNISHMENT->value => SystemStatus::UNDER_PUNISHMENT->value,
            ]);
    }
}
