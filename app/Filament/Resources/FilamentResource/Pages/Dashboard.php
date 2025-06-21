<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\OrderTrendChart;
use App\Filament\Widgets\PatientGenderChart;
use App\Filament\Widgets\StatsOverview;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $title = 'Dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    public function getFooterWidgets(): array
    {
        return [
            OrderTrendChart::class,
        ];
    }
}
