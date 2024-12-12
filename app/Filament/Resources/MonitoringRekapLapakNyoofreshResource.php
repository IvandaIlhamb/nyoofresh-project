<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitoringRekapLapakNyoofreshResource\Pages;
use App\Filament\Resources\MonitoringRekapLapakNyoofreshResource\RelationManagers;
use App\Models\HasilPenjualan;
use App\Models\MonitoringRekapLapakNyoofresh;
use App\Models\Produk;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
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
            return HasilPenjualan::query()
            ->whereHas('suplai', function ($query) {
                $query->whereHas('produk', function ($query) {
                    $query->where('lapak', 'Lapak Nyoofresh');
                });
            });
        return HasilPenjualan::query();
        })
        ->columns([
            Tables\Columns\TextColumn::make('suplai.tanggal')
                ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->isoformat('D MMMM Y') : '-')
                ->label('Tanggal Produk Masuk')
                ->searchable()
                ->sortable(),
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
            'index' => Pages\ListMonitoringRekapLapakNyoofreshes::route('/'),
            'create' => Pages\CreateMonitoringRekapLapakNyoofresh::route('/create'),
            'edit' => Pages\EditMonitoringRekapLapakNyoofresh::route('/{record}/edit'),
        ];
    }
}
