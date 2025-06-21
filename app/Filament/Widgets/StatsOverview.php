<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Product;
use App\Models\Consultation;
use App\Models\Order;
use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Patients', Patient::count())
                ->description('Registered patients')
                ->icon('heroicon-o-user-group')
                ->extraAttributes(['class' => 'bg-blue-500 text-white']),

            Card::make('Total Staff', User::where('role', 'staff')->count())
                ->description('All clinic staff')
                ->icon('heroicon-o-user')
                ->extraAttributes(['class' => 'bg-green-500 text-white']),

            Card::make('Total Products', Product::count())
                ->description('Products available')
                ->icon('heroicon-o-cube')
                ->extraAttributes(['class' => 'bg-yellow-400 text-black']),

            Card::make('Today\'s Consultations', Consultation::whereDate('cons_date', today())->count())
                ->description('Scheduled for today')
                ->icon('heroicon-o-calendar-days')
                ->extraAttributes(['class' => 'bg-orange-500 text-white']),

            Card::make('Total Orders', Order::count())
                ->description('All time total orders')
                ->icon('heroicon-o-shopping-cart')
                ->extraAttributes(['class' => 'bg-pink-500 text-white']),

            Card::make('Total Sales', 'MYR ' . number_format(Order::where('status', 'Completed')->sum('total_amount'), 2))
                ->description('Completed orders only')
                ->icon('heroicon-o-currency-dollar')
                ->extraAttributes(['class' => 'bg-purple-600 text-white']),
        ];
    }

    public static function getDefaultColumnSpan(): int|string|array
    {
        return 'full';
    }
}
