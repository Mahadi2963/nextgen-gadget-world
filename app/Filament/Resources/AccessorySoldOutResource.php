<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessorySoldOutResource\Pages;
use App\Models\AccessoryItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;

class AccessorySoldOutResource extends Resource
{
    protected static ?string $model = AccessoryItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Sold-Out Accessories';
    protected static ?string $navigationGroup = 'Accessories';
    protected static ?int $navigationSort = 2;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('item_name')
                ->label('Item Name')
                ->disabled(),

            Forms\Components\TextInput::make('type.type_name')
                ->label('Type')
                ->disabled(),

            Forms\Components\TextInput::make('buy_price')
                ->label('Buy Price')
                ->numeric()
                ->disabled(),

            Forms\Components\TextInput::make('quantity')
                ->label('Stock Quantity')
                ->numeric()
                ->required(),

            Forms\Components\FileUpload::make('image')
                ->label('Image')
                ->image(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'available' => 'Available',
                    'sold' => 'Sold',
                ])
                ->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_name')
                    ->label('Item')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type.type_name')
                    ->label('Type'),

                Tables\Columns\TextColumn::make('buy_price')
                    ->label('Buy Price'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Stock')
                    ->badge()
                    ->colors([
                        'danger' => fn ($state) => $state <= 0,
                        'warning' => fn ($state) => $state <= 5 && $state > 0,
                        'success' => fn ($state) => $state > 5,
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'available',
                        'danger' => 'sold',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function ($record, $data) {
                        // Auto-update status based on new quantity
                        if ($data['quantity'] > 0) {
                            $record->status = 'available';
                        } else {
                            $record->status = 'sold';
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccessorySoldOut::route('/'),
            'edit'  => Pages\EditAccessorySoldOut::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Only show sold-out accessories
        return parent::getEloquentQuery()
            ->where('quantity', '<=', 0);
    }
}
