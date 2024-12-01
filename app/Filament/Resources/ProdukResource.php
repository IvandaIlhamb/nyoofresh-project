<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;


class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // ----------hidden produk jika tidak memiliki akses
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('produk'))
            return true;
        else
            return false;
    }

    // ----------hidden akses url /produk jika tidak memiliki akses
    public static function canViewAny(): bool
    {
        if(auth()->user()->can('produk'))
            return true;
        else
            return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lapak')
                    ->options([
                        'Lapak Nyoofresh' => 'Lapak Nyoofresh',
                        'Diluar Nyoofresh' => 'Diluar Nyoofresh',
                    ])
                    ->native(false)
                    ->label('Jenis Lapak')
                    ->required(),
                Forms\Components\TextInput::make('nama_produk')
                    ->label('Nama Produk')
                    ->required(),
                Forms\Components\TextArea::make('deskripsi')
                    ->label('Deskripsi Produk'),
                Forms\Components\TextInput::make('harga_kulak')
                    ->label('Harga Kulak')
                    ->numeric()
                    ->prefix('Rp')
                    ->maxValue(42949672.95),
                Forms\Components\TextInput::make('harga_jual')
                    ->label('Harga Jual')
                    ->numeric()
                    ->prefix('Rp')
                    ->maxValue(42949672.95),
                Forms\Components\FileUpload::make('foto_produk')
                    ->label('Foto Produk')
                    ->image()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lapak')
                    ->numeric(),
                Tables\Columns\TextColumn::make('nama_produk')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi Produk')
                    ->limit(50),
                Tables\Columns\TextColumn::make('harga_kulak')
                    ->label('Harga Kulak')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_jual')
                    ->label('Harga Jual')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('foto_produk')
                    ->label('Foto Produk')
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
