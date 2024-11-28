<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapPenjualanResource\Pages;
use App\Filament\Resources\RekapPenjualanResource\RelationManagers;
use App\Models\RekapPenjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RekapPenjualanResource extends Resource
{
    protected static ?string $model = RekapPenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // ----------hidden rekappenjualan jika tidak memiliki akses
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('rekappenjualan'))
            return true;
        else
            return false;
    }

    // ----------hidden akses url /rekappenjualan jika tidak memiliki akses
    public static function canViewAny(): bool
    {
        if(auth()->user()->can('rekappenjualan'))
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
            'index' => Pages\ListRekapPenjualans::route('/'),
            'create' => Pages\CreateRekapPenjualan::route('/create'),
            'edit' => Pages\EditRekapPenjualan::route('/{record}/edit'),
        ];
    }
}
