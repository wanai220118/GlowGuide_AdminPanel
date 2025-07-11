<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\PatientExporter;
use App\Filament\Resources\ExportBulkAction;
use Filament\Forms\Components\Select;
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
                Forms\Components\TextInput::make('patient_number')
                    ->readOnly()
                    ->default(fn () => 'PAT-' . now()->format('YmdHis')),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('date_of_birth')
                ->required()
                // ->date()
                // ->format(format: 'dd-mm-YYYY')
                ->maxDate(now()),

                Forms\Components\Select::make('gender')
                ->required()
                ->options([
                    'Male' => 'Male',
                    'Female' => 'Female',
                ]),

                Forms\Components\TextInput::make('email')
                ->required()
                ->email()
                ->hint('Example: name@gmail.com'),

                Forms\Components\TextInput::make('phone_No')
                ->label('Phone Number')
                ->required()
                ->tel()
                // ->rules(['regex:/^[0-9\-\+]{9,15}$/'])
                ->hint('Enter a valid phone number'),

                Forms\Components\TextInput::make('address')
                ->required()
                ->maxLength(255)
                ->hint('Enter a valid address'),

                Forms\Components\DatePicker::make('registration_date')
                ->required()
                ->default(now())
                ->maxDate(now()),

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
                Tables\Columns\ImageColumn::make('image')
                    ->url(fn ($record) => asset('storage/' . $record->image))
                    ->disk('public')
                    ->visibility('public')
                    ->circular()
                    ->ring(5)
                    ->size(80),
                Tables\Columns\TextColumn::make(name: 'patient_number'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone_No')
                    ->label('Phone Number'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('registration_date')
                    ->sortable()
                    ->date(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered At')
                    ->since()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('created_at_badge')
                    ->label('Registered')
                    ->getStateUsing(fn ($record) => $record->created_at)
                    ->formatStateUsing(function ($state) {
                        return now()->diffInDays($state) <= 3 ? 'Recently Registered' : 'Registered';
                    })
                    ->color(function ($state) {
                        return now()->diffInDays($state) <= 3 ? 'success' : 'secondary';
                    })
                    ->sortable(),

            ])


            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
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
                ExportAction::make()->exporter(PatientExporter::class)
                    ->label('Export')
                    ->color('secondary')
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                // ExportBulkAction::make()->exporter(PatientExporter::class)
                //     ->label('Export Selected')
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
    // public static function getWidgets(): array
    // {
    //     return [
    //         PatientResource\Widgets\PatientOverview::class,
    //     ]
    // }
}
