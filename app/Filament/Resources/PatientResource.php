<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('patient_number')
                //     ->disabled()
                //     ->default(fn () => 'PAT-' . now()->format('YmdHis')),

                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('email')
                ->required()
                ->email()
                ->hint('Example: name@example.com'),

                Forms\Components\DatePicker::make('date_of_birth')
                ->required()
                ->maxDate(now()),

                Forms\Components\TextInput::make('phone_No')
                ->required()
                ->tel()
                ->rules(['regex:/^[0-9\-\+]{9,15}$/'])
                ->hint('Enter a valid phone number'),

                Forms\Components\Select::make('gender')
                ->required()
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                ]),

                // Forms\Components\Select::make('staff_id')
                // ->relationship('staff', 'name')
                // ->searchable()
                // ->preload()
                // ->required(),

                Forms\Components\FileUpload::make('image')
                ->uploadingMessage('Uploading attachment...')
                ->image()
                ->maxSize(2048)
                ->hint('The maximum file size is 2MB.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                // Tables\Columns\TextColumn::make(name: 'patient_number'),

                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->visibility('public')
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('staff.name')
                //     ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
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
            // RelationManagers\ConsultationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
