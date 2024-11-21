<?php

namespace App\Filament\Resources\SuplaiResource\Pages;

use App\Filament\Resources\SuplaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuplai extends EditRecord
{
    protected static string $resource = SuplaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
