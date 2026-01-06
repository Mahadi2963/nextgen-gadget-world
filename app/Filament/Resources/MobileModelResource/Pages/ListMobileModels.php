<?php

namespace App\Filament\Resources\MobileModelResource\Pages;

use App\Filament\Resources\MobileModelResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListMobileModels extends ListRecords
{
    protected static string $resource = MobileModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Mobile Model'),
        ];
    }
}
