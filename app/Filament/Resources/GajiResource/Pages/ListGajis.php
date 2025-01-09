<?php

namespace App\Filament\Resources\GajiResource\Pages;

use App\Filament\Resources\GajiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGajis extends ListRecords
{
    protected static string $resource = GajiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
