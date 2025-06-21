<?php

namespace App\Filament\Resources;
use Filament\Tables\Filters\SelectFilter;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\ProductExporter;
use Filament\Forms\Components\Select;
use App\Models\Category;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\TextInput;
use App\Filament\Exports\ProductPdfExporter;
use Filament\Tables\Actions\Action;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-c-star';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id'))  // get category names with IDs
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('product_number')
                    // ->disabled()
                    ->readOnly()
                    ->default(fn () => 'PROD-' . now()->format('YmdHis')),

                Forms\Components\FileUpload::make('image')
                    ->required()
                    ->image()
                    ->label('Product Image')
                    ->directory('product-image'),

                Forms\Components\TextInput::make('name')
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
                    ->placeholder('YYYY-MM-DD'),
                    // ->minDate(now()->addDays(1))
                    // ->maxDate(now()->addYears(5))
                    // ->default(now()->addDays(30))

                // Forms\Components\Select::make('inventory')
                //     ->options([
                //         'High in Stock' => 'High in Stock',
                //         'Low in Stock' => 'Low in Stock',
                //         'Out of Stock' => 'Out of Stock',
                //     ])
                //     ->default('High in Stock')
                //     ->label('Inventory'),

                // Forms\Components\TextInput::make('updated_at')
                //     ->label('Last Updated')
                //     ->readOnly()
                //     ->default(now()->format('d-m-Y H:i:s')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('product_number'),

                Tables\Columns\ImageColumn::make('image')
                    ->url(fn ($record) => asset('storage/' . $record->image))
                    ->square()
                    ->size(80)
                    ->disk('public')
                    ->visibility(visibility: 'public'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('MYR'),

                Tables\Columns\TextColumn::make('qty')
                    ->label('Stock Quantity'),

                Tables\Columns\TextColumn::make('inventory_status')
                    ->label('Inventory Status')
                    ->getStateUsing(function ($record): string {
                        if ($record->qty > 100) {
                            return 'High in Stock';
                        } elseif ($record->qty > 0) {
                            return 'Low in Stock';
                        } else {
                            return 'Out of Stock';
                        }
                    })
                    ->badge()
                    ->color(function ($record): string {
                        if ($record->qty > 100) {
                            return 'success';
                        } elseif ($record->qty > 0) {
                            return 'warning';
                        } else {
                            return 'danger';
                        }
                    }),

                Tables\Columns\TextColumn::make('expiry_date')
                    ->date()
                    ->label('Expiry Date')
                    ->sortable(),

                Tables\Columns\TextColumn::make('expiry_status')
                    ->label('Expiry Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Expired' => 'danger',
                        'Expiring Soon' => 'warning',
                        'Valid' => 'success',
                        'Unknown' => 'gray',
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
                ])

            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name') // 👈 assumes 'name' is the category label
                    ->label('Category'),

                SelectFilter::make('inventory_status')
                    ->label('Inventory Status')
                    ->options([
                        'high' => 'High in Stock',
                        'low' => 'Low in Stock',
                        'out' => 'Out of Stock',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'high' => $query->where('qty', '>', 100),
                            'low'  => $query->whereBetween('qty', [1, 100]),
                            'out'  => $query->where('qty', '<=', 0),
                            default => $query,
                        };
                    }),

                SelectFilter::make('expiry_status')
                    ->label('Expiry Status')
                    ->options([
                        'expired' => 'Expired',
                        'soon'    => 'Expiring Soon (≤30 days)',
                        'valid'   => 'Valid (>30 days)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $now = now();

                        return match ($data['value']) {
                            'expired' => $query->whereDate('expiry_date', '<', $now),
                            'soon'    => $query->whereBetween('expiry_date', [$now, $now->copy()->addDays(30)]),
                            'valid'   => $query->whereDate('expiry_date', '>', $now->copy()->addDays(30)),
                            default => $query,
                        };
                    }),

                Filter::make('price_range')
                    ->form([
                        TextInput::make('min')->numeric()->placeholder('Min Price'),
                        TextInput::make('max')->numeric()->placeholder('Max Price'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                                return $query
                                    ->when($data['min'], fn ($q) => $q->where('price', '>=', $data['min']))
                                    ->when($data['max'], fn ($q) => $q->where('price', '<=', $data['max']));
                            }),

                Filter::make('qty_range')
                    ->form([
                        TextInput::make('min')->numeric()->label('Min Qty'),
                        TextInput::make('max')->numeric()->label('Max Qty'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['min'], fn ($q) => $q->where('qty', '>=', $data['min']))
                            ->when($data['max'], fn ($q) => $q->where('qty', '<=', $data['max']));
                    }),
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
                ExportAction::make()->exporter(ProductExporter::class)
                    ->label('Export')
                    ->color('secondary'),
                Action::make('Export to PDF')
                ->label('Export PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('danger')
                        ->action(function () {
                            $products = \App\Models\Product::with('category')->get();

                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.products', [
                                'products' => $products,
                            ]);

                            return response()->streamDownload(
                                fn () => print($pdf->stream()),
                                'products.pdf'
                            );
                        }),
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
