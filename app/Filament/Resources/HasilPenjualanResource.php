<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HasilPenjualanResource\Pages;
use App\Filament\Resources\HasilPenjualanResource\RelationManagers;
use App\Models\HasilPenjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasilPenjualanResource extends Resource
{
    protected static ?string $model = HasilPenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // ----------hidden hasil penjualan jika tidak memiliki akses
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('hasilpenjualan'))
            return true;
        else
            return false;
    }

    // ----------hidden akses url /hasil-penjualan jika tidak memiliki akses
    public static function canViewAny(): bool
    {
        if(auth()->user()->can('hasilpenjualan'))
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
            'index' => Pages\ListHasilPenjualans::route('/'),
            'create' => Pages\CreateHasilPenjualan::route('/create'),
            'edit' => Pages\EditHasilPenjualan::route('/{record}/edit'),
        ];
    }
}
