<?php

namespace App\Filament\Resources\HasilPenjualanResource\Pages;

use App\Filament\Resources\HasilPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHasilPenjualans extends ListRecords
{
    protected static string $resource = HasilPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
