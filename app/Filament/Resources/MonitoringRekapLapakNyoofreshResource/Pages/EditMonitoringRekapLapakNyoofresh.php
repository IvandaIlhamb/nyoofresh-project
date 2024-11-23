<?php

namespace App\Filament\Resources\MonitoringRekapLapakNyoofreshResource\Pages;

use App\Filament\Resources\MonitoringRekapLapakNyoofreshResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonitoringRekapLapakNyoofresh extends EditRecord
{
    protected static string $resource = MonitoringRekapLapakNyoofreshResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
