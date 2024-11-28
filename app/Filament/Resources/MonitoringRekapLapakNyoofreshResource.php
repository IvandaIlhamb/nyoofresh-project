<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitoringRekapLapakNyoofreshResource\Pages;
use App\Filament\Resources\MonitoringRekapLapakNyoofreshResource\RelationManagers;
use App\Models\MonitoringRekapLapakNyoofresh;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MonitoringRekapLapakNyoofreshResource extends Resource
{
    protected static ?string $model = MonitoringRekapLapakNyoofresh::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // ----------hidden monitoringrekaplapak jika tidak memiliki akses
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('monitoringrekaplapak'))
            return true;
        else
            return false;
    }

    // ----------hidden akses url /monitoringrekaplapak jika tidak memiliki akses
    public static function canViewAny(): bool
    {
        if(auth()->user()->can('monitoringrekaplapak'))
            return true;
        else
            return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonitoringRekapLapakNyoofreshes::route('/'),
            'create' => Pages\CreateMonitoringRekapLapakNyoofresh::route('/create'),
            'edit' => Pages\EditMonitoringRekapLapakNyoofresh::route('/{record}/edit'),
        ];
    }
}
