<?php

namespace App\Filament\Resources\SuplaiResource\Pages;

use App\Filament\Resources\SuplaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuplais extends ListRecords
{
    protected static string $resource = SuplaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
