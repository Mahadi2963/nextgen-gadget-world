<?php

namespace App\Filament\Resources\MobileSoldOutResource\Pages;

use App\Filament\Resources\MobileSoldOutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMobileSoldOut extends EditRecord
{
    protected static string $resource = MobileSoldOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
