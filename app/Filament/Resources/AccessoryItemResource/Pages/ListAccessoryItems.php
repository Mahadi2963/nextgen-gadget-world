<?php

namespace App\Filament\Resources\AccessoryItemResource\Pages;

use App\Filament\Resources\AccessoryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccessoryItems extends ListRecords
{
    protected static string $resource = AccessoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
