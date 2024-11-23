<?php

namespace App\Filament\Resources\MonitoringRekapLapakNyoofreshResource\Pages;

use App\Filament\Resources\MonitoringRekapLapakNyoofreshResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMonitoringRekapLapakNyoofreshes extends ListRecords
{
    protected static string $resource = MonitoringRekapLapakNyoofreshResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
