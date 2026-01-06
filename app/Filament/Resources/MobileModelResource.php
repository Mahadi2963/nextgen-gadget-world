<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MobileModelResource\Pages;
use App\Models\Brand;
use App\Models\MobileModel;
use App\Models\Sale;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class MobileModelResource extends Resource
{
    protected static ?string $model = MobileModel::class;
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';
    protected static ?string $navigationGroup = 'Mobiles';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('brand_id')
                ->label('Brand')
                ->options(Brand::pluck('name', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('model_name')
                ->label('Model Name')
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
                ->directory('mobiles'),

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
                Tables\Columns\TextColumn::make('model_name')
                    ->label('Model')
                    ->searchable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand'),

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

                        // Sale history
                        Sale::create([
                            'product_type'   => 'mobile',
                            'product_id'     => $record->id,
                            'quantity_sold'  => 1,
                            'selling_price'  => $data['selling_price'],
                            'sold_at'        => now(),
                        ]);

                        // Stock calculation
                        $remaining = $record->quantity - 1;

                        $record->update([
                            'quantity' => $remaining,
                            'status'   => $remaining <= 0 ? 'sold' : 'available',
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Sell Mobile'),

                // ✅ EDIT ACTION
                Tables\Actions\EditAction::make(),

                // ✅ DELETE ACTION (protected)
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn ($record) => $record->quantity <= 0)
                    ->modalDescription('You cannot delete a sold-out product.'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMobileModels::route('/'),
            'create' => Pages\CreateMobileModel::route('/create'),
            'edit'   => Pages\EditMobileModel::route('/{record}/edit'),
        ];
    }
}
