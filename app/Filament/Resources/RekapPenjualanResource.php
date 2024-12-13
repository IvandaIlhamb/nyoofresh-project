<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapPenjualanResource\Pages;
use App\Filament\Resources\RekapPenjualanResource\RelationManagers;
use App\Models\HasilPenjualan;
use App\Models\RekapPenjualan;
use Carbon\Carbon;
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
    public static function canCreate(): bool
    {
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
        ->query(function (Builder $query) {
            $user = auth()->user();
            if ($user->hasRole('supplier')) {
                return HasilPenjualan::query()
                ->whereHas('suplai', function ($query) {
                    $query->where('nama_supplier', auth()->user()->name);
                    });
            }
            return HasilPenjualan::query();
            })
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->default(Carbon::now()->isoformat('D MMMM Y'))
                    ->label('Tanggal')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('produk.nama_produk')
                //     ->label('Produk'),
                Tables\Columns\TextColumn::make('suplai.produk.nama_produk')
                    ->label('Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('suplai.nama_supplier')
                    ->label('Nama Supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('suplai.jumlah_suplai')
                    ->label('Jumlah Suplai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('terjual')
                    ->label('Terjual')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kembali')
                    ->label('Kembali')
                    ->searchable(),
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
