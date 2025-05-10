<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-c-star';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([                
                Forms\Components\TextInput::make('product_number')
                    // ->disabled()
                    ->readOnly()
                    ->default(fn () => 'PROD-' . now()->format('YmdHis')),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->label('Label')
                    ->directory('product-image'),

                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\Select::make('category')
                    ->options([
                        'facial wash' => 'Facial Wash',
                        'toner' => 'Toner',
                        'serum' => 'Serum',
                        'moisturizer' => 'Moisturizer',
                        'sunscreen' => 'Sunscreen',
                    ])
                    ->required(),
                
                    Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpan('full')
                    ->maxLength(255),

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('MYR')
                    ->required(),
            
                Forms\Components\TextInput::make('qty')
                    ->required()
                    ->label('Stock Quantity'),
                
                Forms\Components\DatePicker::make('expiry_date')
                    ->label('Expiry Date')
                    ->required()
                    ->placeholder('YYYY-MM-DD')
                    ->minDate(now()->addDays(1))
                    ->maxDate(now()->addYears(2))
                    ->default(now()->addDays(30)),

                Forms\Components\Select::make('inventory')
                    ->options([
                        'high' => 'High',
                        'low' => 'Low',
                        'out of stock' => 'Out of Stock',
                    ])
                    ->default('High')
                    ->required()
                    ->label('Inventory'),
                
                Forms\Components\ViewField::make('updated_at')
                    ->label('Last Updated')
                    ->default(now()->format('d-m-Y H:i:s')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('product_number'),

                Tables\Columns\ImageColumn::make('image')
                    ->square()
                    ->size(80)
                    ->disk('public')
                    ->visibility(visibility: 'public'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('MYR'),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Stock Quantity'),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->date()
                    ->label('Expiry Date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventory')
                    ->label('Inventory'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),                    
                ])

            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'facial wash' => 'Facial Wash',
                        'toner' => 'Toner',
                        'serum' => 'Serum',
                        'moisturizer' => 'Moisturizer',
                        'sunscreen' => 'Sunscreen',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
