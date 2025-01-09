<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemasukanResource\Pages;
use App\Filament\Resources\PemasukanResource\RelationManagers;
use App\Models\HasilPenjualan;
use App\Models\Pemasukan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PemasukanResource extends Resource
{
    protected static ?string $model = Pemasukan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // ----------hidden pemasukan jika tidak memiliki akses
    // note : tambahkan "visible(static::shouldRegisterNavigation())" di vendor->filament->filament->src->resources->resource.php
    // didalam public static function getNavigationItems(): array
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('pemasukan'))
            return true;
        else
            return false;
    }

    // ----------hidden akses url /pemasukan jika tidak memiliki akses
    public static function canViewAny(): bool
    {
        if(auth()->user()->can('pemasukan'))
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
        $totalKeuntungan = HasilPenjualan::sum('keuntungan');

        return $table
            ->query(function (Builder $query) {
                $user = auth()->user();
                if($user->hasRole('dropping')){
                    return HasilPenjualan::query()
                    ->whereHas('suplai', function ($query) {
                        $query->whereHas('produk', function ($query) {
                            $query->where('lapak', 'Diluar Nyoofresh');
                        });
                    });
                }elseif($user->hasRole('penjaga lapak')){
                    return HasilPenjualan::query()
                    ->whereHas('suplai', function ($query) {
                        $query->whereHas('produk', function ($query) {
                            $query->where('lapak', 'Lapak Nyoofresh');
                        });
                    });
                }elseif($user->hasRole('supplier')){
                    return HasilPenjualan::query()
                    ->whereHas('suplai', function ($query) {
                        $query->where('nama_supplier', auth()->user()->name);
                        });
                }
                return HasilPenjualan::query();
                })
            ->columns([
                // Grid::make([
                //     'lg' => 8,
                // ])
                // ->schema([
                    Tables\Columns\TextColumn::make('tanggal')
                    ->formatStateUsing(function ($state, $record) {
                        // Cek apakah tabel hasil memiliki tanggal
                        $tanggalHasil = $record->tanggal;
                        if ($tanggalHasil) {
                            return \Carbon\Carbon::parse($tanggalHasil)->isoformat('D MMMM Y');
                        }
                
                        // Jika tidak ada, gunakan tanggal dari tabel suplai
                        $tanggalSuplai = optional($record->suplai)->tanggal;
                        return $tanggalHasil
                            ? \Carbon\Carbon::parse($tanggalSuplai)->isoformat('D MMMM Y')
                            : $tanggalHasil;
                    })
                    ->label('Tanggal Penjualan')
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
                    // ->getStateUsing(function ($record) {
                    //     $terjual = $record->terjual; 
                    //     $suplai = $record->suplai->jumlah_suplai; 
                        
                    //     // Hitung total pendapatan
                    //     return $suplai - $terjual;
                    // })
                    ->searchable(),
                Tables\Columns\TextColumn::make('suplai.produk.harga_jual')
                    ->label('Harga Jual')
                    ->money('IDR', locale: 'id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keuntungan')
                    ->label('Keuntungan')
                    // ->getStateUsing(function ($record) {
                    //     $terjual = $record->terjual; // Ambil nilai 'terjual'
                    //     $hargaJual = optional($record->suplai?->produk)->harga_jual; // Ambil nilai 'harga_jual'
                        
                    //     // Hitung total pendapatan
                    //     return $terjual && $hargaJual ? $terjual * $hargaJual : 0;
                    // })
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')) // Format angka (opsional)
                    ->sortable()
                    ->money('IDR', locale: 'id')
                    ->searchable(),
                // ])
                // View::make('collapsible-row-content')
                // ->components([
                //     Tables\Columns\TextColumn::make('email'),
                // ]),
            ])
            ->content(function () use ($totalKeuntungan) {
        
                return view('collapsible-row-content', [
                    'totalKeuntungan' => $totalKeuntungan,
                ]);
            })
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPemasukans::route('/'),
            'create' => Pages\CreatePemasukan::route('/create'),
            'edit' => Pages\EditPemasukan::route('/{record}/edit'),
        ];
    }
}
