<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\OrderExporter;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    // ->disabled()
                    ->readOnly()
                    ->default(fn () => 'ORD-' . now()->format('YmdHis')),

                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->required(),

                Forms\Components\TextInput::make('details')
                    ->label('Order Details')
                    ->maxLength(1000),

                Forms\Components\TextInput::make('total_amount')
                    ->numeric()
                    ->prefix('MYR')
                    ->label('Total Amount')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->default('Completed')
                    ->required(),


                Forms\Components\Select::make('payment_method')
                    ->options([
                        'Online Banking' => 'Online Banking',
                        'Credit Card' => 'Credit Card',
                        'Cash On Delivery' => 'Cash On Delivery',
                    ])
                    ->default('Online Banking')
                    ->required(),

                Forms\Components\Select::make('payment_status')
                    ->options([
                        'Unpaid' => 'Unpaid',
                        'Paid' => 'Paid',
                    ])
                    ->default('Paid')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order Number'),
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Ordered By')
                    ->searchable(),
                Tables\Columns\TextColumn::make('details')
                    ->label('Order Details'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('MYR'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->searchable(),
        ])

        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->label('Order Status')
                ->options([
                    'Pending' => 'Pending',
                    'Processing' => 'Processing',
                    'Completed' => 'Completed',
                    'Cancelled' => 'Cancelled',
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
                ExportAction::make()->exporter(OrderExporter::class)
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
