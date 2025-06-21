<?php

namespace App\Filament\Exports;

use App\Models\Consultation;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ConsultationExporter extends Exporter
{
    protected static ?string $model = Consultation::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('consultation_name'),
            ExportColumn::make('notes'),
            ExportColumn::make('patient_id'),
            ExportColumn::make('staff_id'),
            ExportColumn::make('cons_date'),
            ExportColumn::make('cons_time'),
            ExportColumn::make('status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your consultation export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
