<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsultationResource\Pages;
use App\Filament\Resources\ConsultationResource\RelationManagers;
use App\Models\Consultation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\ConsultationExporter;

class ConsultationResource extends Resource
{
    protected static ?string $model = Consultation::class;
    protected static ?string $navigationIcon = 'heroicon-s-calendar-date-range';
    protected static ?string $navigationLabel = 'Consultations';
    protected static ?string $pluralLabel = 'Consultations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('consultation_name')
                    ->label('Consultation Name')
                    ->required()
                    ->options([
                        'Acne Care Consultation' => 'Acne Care Consultation',
                        'Radiant Skin Consultation' => 'Radiant Skin Consultation',
                        'Age Renew Consultation' => 'Age Renew Consultation',
                        'Lift & Glow Consultation' => 'Lift & Glow Consultation',
                        'Glow Up Consultation' => 'Glow Up Consultation',
                    ]),

                Forms\Components\Textarea::make('notes')
                    ->label('Notes'),

                Forms\Components\Select::make('patient_id')
                    ->label('Patient')
                    ->required()
                    ->relationship('patient', 'name'),

                Forms\Components\Select::make('staff_id')
                    ->label('Staff')
                    ->required()
                    ->relationship('staff', 'name'),

                Forms\Components\DatePicker::make('cons_date')
                    ->label('Consultation Date')
                    ->firstDayOfWeek(1)
                    ->native(false)
                    ->date()
                    ->required(),

                Forms\Components\TimePicker::make('cons_time')
                    ->label('Consultation Time')
                    ->datalist([
                        '08:00',
                        '10:00',
                        '14:00'
                    ])
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'Pending' => 'Pending',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->default('Completed'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultation_name')
                    ->label('Consultation Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes'),
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Patient')
                    ->searchable(),
                Tables\Columns\TextColumn::make('staff.name')
                    ->label('Staff')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cons_date')
                    ->label('Consultation Date'),
                Tables\Columns\TextColumn::make('cons_time')
                    ->label('Consultation Time'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('staff_id')
                    ->label('Staff')
                    ->relationship('staff', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->iconButton(),
                Tables\Actions\ViewAction::make()
                ->iconButton(),
                Tables\Actions\DeleteAction::make()
                ->iconButton(),
            ])

            ->headerActions([
                ExportAction::make()->exporter(ConsultationExporter::class)
                    ->label('Export')
                    ->color('secondary')
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultations::route('/'),
            'create' => Pages\CreateConsultation::route('/create'),
            'edit' => Pages\EditConsultation::route('/{record}/edit'),
        ];
    }
}
