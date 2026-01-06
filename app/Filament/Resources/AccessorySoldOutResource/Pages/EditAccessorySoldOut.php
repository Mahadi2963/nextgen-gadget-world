<?php

namespace App\Filament\Resources\AccessorySoldOutResource\Pages;

use App\Filament\Resources\AccessorySoldOutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccessorySoldOut extends EditRecord
{
    protected static string $resource = AccessorySoldOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
