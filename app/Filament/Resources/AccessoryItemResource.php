<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessoryItemResource\Pages;
use App\Models\AccessoryItem;
use App\Models\AccessoryType;
use App\Models\Sale;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class AccessoryItemResource extends Resource
{
    protected static ?string $model = AccessoryItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Accessory Items';
    protected static ?string $navigationGroup = 'Accessories';
    protected static ?int $navigationSort = 2;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('type_id')
                ->label('Accessory Type')
                ->options(AccessoryType::pluck('type_name', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('item_name')
                ->label('Item Name')
                ->required(),

            Forms\Components\TextInput::make('buy_price')
                ->label('Buy Price')
                ->numeric()
                ->required(),

            // ✅ Quantity field
            Forms\Components\TextInput::make('quantity')
                ->label('Stock Quantity')
                ->numeric()
                ->minValue(0)
                ->required(),

            Forms\Components\FileUpload::make('image')
                ->label('Image')
                ->image()
                ->directory('accessories'),

            Forms\Components\Select::make('status')
                ->options([
                    'available' => 'Available',
                    'sold' => 'Sold',
                ])
                ->default('available')
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
                    ->label('Buy Price')
                    ->money('BDT'),

                // 🔥 Stock indicator
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Stock')
                    ->badge()
                    ->colors([
                        'danger' => fn ($state) => $state <= 0,
                        'warning' => fn ($state) => $state <= 5,
                        'success' => fn ($state) => $state > 5,
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'available',
                        'danger' => 'sold',
                    ]),
            ])
            ->actions([

                // ✅ SELL ACTION
                Tables\Actions\Action::make('sell')
                    ->label('Sell')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->quantity > 0)
                    ->form([
                        Forms\Components\TextInput::make('selling_price')
                            ->label('Selling Price')
                            ->numeric()
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {

                        Sale::create([
                            'product_type'  => 'accessory',
                            'product_id'    => $record->id,
                            'quantity_sold' => 1,
                            'selling_price' => $data['selling_price'],
                            'sold_at'       => now(),
                        ]);

                        $remaining = $record->quantity - 1;

                        $record->update([
                            'quantity' => $remaining,
                            'status'   => $remaining <= 0 ? 'sold' : 'available',
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Sell Accessory'),

                // ✅ EDIT
                Tables\Actions\EditAction::make(),

                // ✅ DELETE (protected)
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn ($record) => $record->quantity <= 0)
                    ->modalDescription('Sold-out accessories cannot be deleted.'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAccessoryItems::route('/'),
            'create' => Pages\CreateAccessoryItem::route('/create'),
            'edit'   => Pages\EditAccessoryItem::route('/{record}/edit'),
        ];
    }
}
