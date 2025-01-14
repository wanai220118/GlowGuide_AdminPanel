<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PatientGenderOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Male', Patient::query()->where('gender', 'male')->count()),
            Stat::make('Female', Patient::query()->where('gender', 'female')->count()),
        ];
    }
}
