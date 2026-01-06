<?php

namespace App\Filament\Resources\AccessoryTypeResource\Pages;

use App\Filament\Resources\AccessoryTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccessoryType extends EditRecord
{
    protected static string $resource = AccessoryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
