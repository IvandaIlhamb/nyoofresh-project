<?php

namespace App\Filament\Resources\HasilPenjualanResource\Pages;

use App\Filament\Resources\HasilPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHasilPenjualan extends EditRecord
{
    protected static string $resource = HasilPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
