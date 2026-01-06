<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessoryTypeResource\Pages;
use App\Models\AccessoryType;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class AccessoryTypeResource extends Resource
{
    protected static ?string $model = AccessoryType::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube'; // safe icon
    protected static ?string $navigationGroup = 'Accessories';
    protected static ?string $navigationLabel = 'Accessory Types';


    public static function form(Forms\Form $form): Forms\Form
    {
        // Set default category ID (example: "Accessories")
        $defaultCategoryId = Category::where('name', 'Accessories')->value('id');

        return $form->schema([
            Forms\Components\Select::make('category_id')
                ->label('Category')
                ->options([
                    $defaultCategoryId => 'Accessories',
                ])
                ->default($defaultCategoryId)  // default category
                ->disabled()                   // cannot be changed in UI
                ->dehydrated(true)             // very important for saving
                ->required(),

            Forms\Components\TextInput::make('type_name')
                ->label('Accessory Type Name')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type_name')
                    ->label('Type Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Delete Accessory Type?')
                    ->modalDescription(
                        'If this type has accessory items, deletion will fail.'
                    ),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAccessoryTypes::route('/'),
            'create' => Pages\CreateAccessoryType::route('/create'),
            'edit'   => Pages\EditAccessoryType::route('/{record}/edit'),
        ];
    }
}
