<?php

namespace App\Filament\Resources\DroppingResource\Pages;

use App\Filament\Resources\DroppingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDroppings extends ListRecords
{
    protected static string $resource = DroppingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
