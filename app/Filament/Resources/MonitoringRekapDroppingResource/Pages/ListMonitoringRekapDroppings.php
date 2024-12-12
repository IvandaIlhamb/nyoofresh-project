<?php

namespace App\Filament\Resources\MonitoringRekapDroppingResource\Pages;

use App\Filament\Resources\MonitoringRekapDroppingResource;
use Filament\Actions;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;

class ListMonitoringRekapDroppings extends ListRecords
{
    protected static string $resource = MonitoringRekapDroppingResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
    protected function getActions(): array
    {
        return [
            ButtonAction::make('PDF')->url(fn()=>route('PDFRekapDropping'))->openUrlInNewTab(),
        ];
    }
}
