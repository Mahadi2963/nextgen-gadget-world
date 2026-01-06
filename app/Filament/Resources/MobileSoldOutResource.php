<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MobileSoldOutResource\Pages;
use App\Models\MobileModel;
use App\Models\Sale;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class MobileSoldOutResource extends Resource
{
    protected static ?string $model = MobileModel::class;
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';
    protected static ?string $navigationLabel = 'Sold-Out Mobiles';
    protected static ?string $navigationGroup = 'Mobiles';
    protected static ?int $navigationSort = 2;

    // Only show sold-out items
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('quantity', '<=', 0);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('model_name')
                ->label('Model')
                ->disabled(),

            Forms\Components\TextInput::make('brand.name')
                ->label('Brand')
                ->disabled(),

            Forms\Components\TextInput::make('buy_price')
                ->label('Buy Price (BDT)')
                ->numeric()
                ->required(),

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
                ->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model_name')->label('Model')->searchable(),
                Tables\Columns\TextColumn::make('brand.name')->label('Brand'),
                Tables\Columns\TextColumn::make('buy_price')->label('Buy Price')->money('BDT'),
                Tables\Columns\TextColumn::make('quantity')->label('Stock')->badge()
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
                        // Auto-update status if quantity > 0
                        if ($data['quantity'] > 0) {
                        $record->status = 'available';
                        }
                    }),
])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMobileSoldOut::route('/'),
            'edit'  => Pages\EditMobileSoldOut::route('/{record}/edit'),
        ];
    }
}
