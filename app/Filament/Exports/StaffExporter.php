<?php

namespace App\Filament\Exports;

use App\Models\Staff;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class StaffExporter extends Exporter
{
    protected static ?string $model = Staff::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('name'),
            ExportColumn::make('email'),
            ExportColumn::make('specialist'),

            ExportColumn::make('working_days')
                ->label('Working Days')
                ->formatStateUsing(function ($state) {
                    $decoded = json_decode($state, true);
                    return is_array($decoded) ? implode(', ', $decoded) : $state;
                }),

            ExportColumn::make('slot1')->label('Slot 1'),
            ExportColumn::make('slot2')->label('Slot 2'),
            ExportColumn::make('slot3')->label('Slot 3'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your staff export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
