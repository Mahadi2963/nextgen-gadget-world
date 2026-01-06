<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Mobiles';

    public static function form(Forms\Form $form): Forms\Form
{
    $mobileCategoryId = Category::where('name', 'Mobile')->value('id');

    return $form->schema([
        Forms\Components\Select::make('category_id')
            ->label('Category')
            ->options([
                $mobileCategoryId => 'Mobile',
            ])
            ->default($mobileCategoryId)
            ->disabled()          // locked in UI
            ->dehydrated(true)    // 🔥 VERY IMPORTANT
            ->required(),

        Forms\Components\TextInput::make('name')
            ->label('Brand Name')
            ->required()
            ->maxLength(255),
    ]);
}

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Brand')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Delete Brand?')
                    ->modalDescription(
                        'If this brand has mobile models, deletion will fail.'
                    ),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit'   => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
