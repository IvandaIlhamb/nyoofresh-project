<?php

namespace App\Filament\Resources\GajiModalPengeluaranResource\Pages;

use App\Filament\Resources\GajiModalPengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGajiModalPengeluarans extends ListRecords
{
    protected static string $resource = GajiModalPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
