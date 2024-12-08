<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HasilPenjualanResource\Pages;
use App\Filament\Resources\HasilPenjualanResource\RelationManagers;
use App\Models\HasilPenjualan;
use App\Models\Produk;
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
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->default(Carbon::now()->format('d-m-Y'))
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
                        return $record->nama_supplier . ' - ' . $record->produk->nama_produk;
                    })
                    ->disabled(),
                Forms\Components\TextInput::make('terjual')
                    ->label('Jumlah Terjual'),
                Forms\Components\TextInput::make('Jumlah Kembali')
                    ->label('Jumlah Kembali'),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            // ->query(function (Builder $query) {
            //     return $query = HasilPenjualan::join('produks', 'hasil_penjualans.produk_id', '=', 'produks.id')
            //             ->where('produks.is_active', 1);
            // })
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->default(Carbon::now()->format('d-m-Y'))
                    ->label('Tanggal'),
                // Tables\Columns\TextColumn::make('produk.nama_produk')
                //     ->label('Produk'),
                Tables\Columns\TextColumn::make('suplai.produk.nama_produk')
                    ->label('Produk'),
                Tables\Columns\TextColumn::make('suplai.nama_supplier')
                    ->label('Nama Supplier'),
                Tables\Columns\TextColumn::make('terjual')
                    ->label('Terjual'),
                Tables\Columns\TextColumn::make('kembali')
                    ->label('Kembali'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
