<?php

namespace App\Filament\Resources\RekapPenjualanResource\Pages;

use App\Filament\Resources\RekapPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRekapPenjualans extends ListRecords
{
    protected static string $resource = RekapPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
