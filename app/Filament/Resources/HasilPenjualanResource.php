<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HasilPenjualanResource\Pages;
use App\Filament\Resources\HasilPenjualanResource\RelationManagers;
use App\Models\HasilPenjualan;
use App\Models\Produk;
use App\Models\Suplai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;
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
    // public static function hasilstatus($hasilBuka): string
    // {
    //     $hasilBuka = HasilPenjualan::where('status', 'Buka')
    //     ->pluck('id_suplai'); 
    // }

    // ----------hidden akses url /hasil-penjualan jika tidak memiliki akses
    public static function canViewAny(): bool
    {
        if(auth()->user()->can('hasilpenjualan'))
            return true;
        else
            return false;
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit($record): bool
    {
        $user = auth()->user();

        // Jika role adalah supplier, maka tidak bisa mengedit
        if ($user->hasRole('supplier')) {
            return false;
        }

        return true; // Untuk role lainnya, izinkan edit
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->default(Carbon::now()->isoformat('D MMMM Y'))
                    ->label('Tanggal')
                    ->required(),
                // Forms\Components\Select::make('produk_id')
                //     ->relationship('produk', 'nama_produk')
                //     ->label('Nama Produk')
                //     ->disabled(),
                Forms\Components\Select::make('id_suplai')
                    ->relationship('suplai', 'id_produk')
                    ->label('Nama Supplier - Nama Produk')
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $namaSupplier = $record->nama_supplier;
                        $namaProduk = optional($record->produk)->nama_produk; // Gunakan optional untuk menghindari error
                        return $namaSupplier . ' - ' . ($namaProduk ?: 'Produk Tidak Tersedia'); // Tampilkan pesan default jika produk tidak ada
                    })
                    ->disabled(),
                Forms\Components\TextInput::make('terjual')
                    ->label('Jumlah Terjual')
                    ->numeric(),
                Forms\Components\TextInput::make('kembali')
                    ->label('Jumlah Kembali')
                    ->numeric(),
                Forms\Components\TextInput::make('keuntungan')
                    ->label('Keuntungan')
                    ->disabled(),
            ]);
    }
    
    public static function table(Table $table): Table
    {
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
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
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
