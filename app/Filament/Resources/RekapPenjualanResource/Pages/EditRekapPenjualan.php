<?php

namespace App\Filament\Resources\RekapPenjualanResource\Pages;

use App\Filament\Resources\RekapPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekapPenjualan extends EditRecord
{
    protected static string $resource = RekapPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
