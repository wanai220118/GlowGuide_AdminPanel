<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Orders Over Time';
    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    // Add this method to define filter options
    protected function getFilters(): ?array
    {
        return [
            'week' => 'This Week',
            'month' => 'This Year (Monthly)',
        ];
    }

    // Main logic to populate the chart
    public function getData(): array
    {
        $filter = $this->filter ?? 'month';

        if ($filter === 'week') {
            $data = Order::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subDays(6))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = collect(range(0, 6))
                ->map(fn($i) => now()->subDays(6 - $i)->format('Y-m-d'))
                ->toArray();

            $orders = $data->keyBy('date');

            $dataset = collect($labels)->map(fn($label) => $orders[$label]->total ?? 0);
        } else {
            $data = Order::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $labels = collect(range(1, 12))
                ->map(fn($m) => date('F', mktime(0, 0, 0, $m, 1)))
                ->toArray();

            $orders = $data->keyBy('month');

            $dataset = collect(range(1, 12))->map(fn($month) => $orders[$month]->total ?? 0);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Orders',
                    'data' => $dataset,
                    'borderColor' => '#6366f1',
                    'backgroundColor' => 'rgba(99,102,241,0.2)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        return request()->routeIs('filament.admin.pages.dashboard');
    }

    public static function getDefaultColumnSpan(): int|string|array
    {
        return 1; // Or 'full' to span across both columns
    }

}
