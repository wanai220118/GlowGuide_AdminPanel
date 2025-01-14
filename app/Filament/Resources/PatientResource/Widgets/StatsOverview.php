<?php

namespace App\Filament\Resources\PatientResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Patient as Patient;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Patients', Patient::count())
                ->description('Increase in patients')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('purple')
                ->chart([7,3,4,5,6,3,5,3]),
        ];
    }

    'widgets' => [
        App\Filament\Resources\PatientResource\Widgets\StatsOverview::class,
    ]


}
