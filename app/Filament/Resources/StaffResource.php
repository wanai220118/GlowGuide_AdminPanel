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
                        'aesthetic doctor' => 'Aesthetic Doctor',
                        'dermatologist' => 'Dermatologist',
                        'esthetician' => 'Esthetician',
                    ])
                    ->required(),

                Forms\Components\Toggle::make('availability')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->inline(false)
                    ->required(),

                // Forms\Components\Select::make('patient_id')
                //     ->relationship('patient', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->required(),
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
                Tables\Columns\TextColumn::make('availability')
                    ->badge(),
                    // ->formatStateUsing(fn ($value): string => $value ? 'Yes' : 'No'),

                // Tables\Columns\TextColumn::make(name: 'patient.name'),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
