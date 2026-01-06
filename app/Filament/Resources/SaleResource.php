<?php

namespace App\Filament\Resources;

use App\Models\Sale;
use App\Models\MobileModel;
use App\Models\AccessoryItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SaleResource\Pages;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'Sales History';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sold_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('product_type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'info' => 'mobile',
                        'warning' => 'accessory',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                // 🔍 CUSTOM SEARCH (UNCHANGED)
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(
                        query: function (Builder $query, $search) {
                            $query->where(function ($q) use ($search) {
                                $q->where(function ($q2) use ($search) {
                                    $q2->where('product_type', 'mobile')
                                        ->whereHas('mobile', function ($q3) use ($search) {
                                            $q3->where('model_name', 'like', "%{$search}%");
                                        });
                                })->orWhere(function ($q2) use ($search) {
                                    $q2->where('product_type', 'accessory')
                                        ->whereHas('accessory', function ($q3) use ($search) {
                                            $q3->where('item_name', 'like', "%{$search}%");
                                        });
                                });
                            });
                        }
                    ),

                // ✅ NEW FEATURE
                Tables\Columns\TextColumn::make('quantity_sold')
                    ->label('Qty')
                    ->sortable(),

                Tables\Columns\TextColumn::make('selling_price')
                    ->label('Selling Price')
                    ->money('BDT')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sold_at')
                    ->label('Sold At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('today')
                    ->label('Today')
                    ->query(fn (Builder $query) =>
                        $query->whereDate('sold_at', today())
                    ),
            ])
            ->actions([])       // ❌ no edit/delete
            ->bulkActions([]);  // ❌ no bulk delete
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
        ];
    }
}
