<?php

namespace App\Filament\Resources\MonitoringRekapDroppingResource\Pages;

use App\Filament\Resources\MonitoringRekapDroppingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonitoringRekapDropping extends EditRecord
{
    protected static string $resource = MonitoringRekapDroppingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
