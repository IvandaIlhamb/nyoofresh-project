<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitoringRekapDroppingResource\Pages;
use App\Filament\Resources\MonitoringRekapDroppingResource\RelationManagers;
use App\Models\HasilPenjualan;
use App\Models\MonitoringRekapDropping;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MonitoringRekapDroppingResource extends Resource
{
    protected static ?string $model = MonitoringRekapDropping::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // ----------hidden monitoring rekap dropping jika tidak memiliki akses
    // note : tambahkan "visible(static::shouldRegisterNavigation())" di vendor->filament->filament->src->resources->resource.php
    // didalam public static function getNavigationItems(): array
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('monitoringrekapdropping'))
            return true;
        else
            return false;
    }

    // ----------hidden akses url /monitoringrekapdropping jika tidak memiliki akses
    public static function canViewAny(): bool
    {
        if(auth()->user()->can('monitoringrekapdropping'))
            return true;
        else
            return false;
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    //hasil penjualan

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
                    return HasilPenjualan::query()
                    ->whereHas('suplai', function ($query) {
                        $query->whereHas('produk', function ($query) {
                            $query->whereHas('user_produk', function ($query) {
                                $query->whereHas('roles', function ($query) {
                                    $query->where('name', 'dropping');
                                });
                            });
                        });
                    });
                return HasilPenjualan::query();
                })
            ->columns([
                Tables\Columns\TextColumn::make('suplai.tanggal')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->isoformat('D MMMM Y') : '-')
                    ->label('Tanggal Produk Masuk')
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
                Tables\Columns\TextColumn::make('suplai.produk.harga_jual')
                    ->label('Harga Jual')
                    ->money('IDR', locale: 'id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keuntungan')
                    ->label('Keuntungan')
                    ->getStateUsing(function ($record) {
                        $terjual = $record->terjual; // Ambil nilai 'terjual'
                        $hargaJual = optional($record->suplai?->produk)->harga_jual; // Ambil nilai 'harga_jual'
                        
                        // Hitung total pendapatan
                        return $terjual && $hargaJual ? $terjual * $hargaJual : 0;
                    })
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')) // Format angka (opsional)
                    ->sortable()
                    ->money('IDR', locale: 'id')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListMonitoringRekapDroppings::route('/'),
            'create' => Pages\CreateMonitoringRekapDropping::route('/create'),
            'edit' => Pages\EditMonitoringRekapDropping::route('/{record}/edit'),
        ];
    }
}
