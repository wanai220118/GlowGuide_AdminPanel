<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffResource\Pages;
use App\Filament\Resources\StaffResource\RelationManagers;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\StaffExporter;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            //name, email, specialist, availability, patient
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                ->required()
                ->email()
                ->hint('Example: name@gmail.com'),

                //multiple specialist
                Forms\Components\Select::make('specialist')
                    ->label('Specialist')
                    ->options([
                        'Aesthetic Doctor' => 'Aesthetic Doctor',
                        'Dermatologist' => 'Dermatologist',
                        'Esthetician' => 'Esthetician',
                    ])
                    ->required(),

                // Forms\Components\Select::make('day')
                //     ->label('Day')
                //     ->options([
                //         'Monday' => 'Monday',
                //         'Tuesday' => 'Tuesday',
                //         'Wednesday' => 'Wednesday',
                //         'Thursday' => 'Thursday',
                //         'Friday' => 'Friday',
                //     ])
                //     ->required(),

                Forms\Components\CheckboxList::make('working_days')
                    ->label('Working Days')
                    ->options([
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                    ])
                    ->columns(2)
                    ->required()
                    ->afterStateHydrated(function ($component, $state) {
                        // Decode if it's stored as JSON in DB
                        if (is_string($state)) {
                            $component->state(json_decode($state, true));
                        }
                    })
                    ->dehydrateStateUsing(function ($state) {
                        return json_encode($state); // Save as JSON
                    })
                    ->default([]),

                Forms\Components\Toggle::make('slot1')
                    ->label('Slot 1 (8AM-10AM)')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->inline(false)
                    ->required(),

                Forms\Components\Toggle::make('slot2')
                    ->label('Slot 2 (10AM-12PM)')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->inline(false)
                    ->required(),

                Forms\Components\Toggle::make('slot3')
                    ->label('Slot 3 (2PM-4PM)')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->inline(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('specialist'),

                Tables\Columns\TextColumn::make('working_days')
                    ->label('Working Days')
                    ->formatStateUsing(function ($state) {
                        $decoded = json_decode($state, true);
                        if (is_array($decoded)) {
                            return implode(', ', $decoded);
                        }
                        return $state; // fallback: just show raw string
                    }),

                Tables\Columns\CheckboxColumn::make('slot1')
                    ->label('Slot 1 (8AM-10AM)'),
                Tables\Columns\CheckboxColumn::make('slot2')
                    ->label('Slot 2 (10AM-12PM)'),
                Tables\Columns\CheckboxColumn::make('slot3')
                    ->label('Slot 3 (2PM-4PM)'),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('specialist')
                    ->options([
                        'aesthetic doctor' => 'Aesthetic Doctor',
                        'dermatologist' => 'Dermatologist',
                        'esthetician' => 'Esthetician',
                    ]),
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
                ExportAction::make()->exporter(StaffExporter::class)
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
            // 'schedules' => RelationManagers\StaffSchedulesRelationManager::class,
            // 'patient' => RelationManagers\Patient::class,
            // 'consultations' => RelationManagers\Consultations::class,
            // RelationManagers\Consultations::class,
            // RelationManagers\Patient::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
