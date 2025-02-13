<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\HasilPenjualan;
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
    // note : tambahkan "visible(static::shouldRegisterNavigation())" di vendor->filament->filament->src->resources->resource.php
    // didalam public static function getNavigationItems(): array
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('produk'))
            return true;
        else
            return false;
    }

    // ----------hidden akses url /produk jika tidak memiliki akses
    public static function canCreate(): bool
    {
        if(auth()->user()->can('create-produk'))
            return true;
        else
            return false;
    }

    // Membatasi akses untuk mengedit user
    public static function canEdit($record): bool
    {
        if(auth()->user()->can('edit-produk'))
            return true;
        else
            return false;
    }

    // Membatasi akses untuk menghapus user
    public static function canDelete($record): bool
    {
        if(auth()->user()->can('delete-produk'))
            return true;
        else
            return false;
    }
    public static function canView($record): bool
    {
        if(auth()->user()->can('view-produk'))
            return true;
        else
            return false;
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User - Jenis Lapak')
                    ->relationship('user_produk', 'name', function ($query) {
                        // Ambil user beserta relasi ke role
                        $query->whereHas('roles', function ($query) {
                            $query->whereIn('name', ['penjaga lapak', 'dropping']);
                        });
                    })
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $namaUser = $record->name;
                        $roles = $record->roles->pluck('name')->join(', ');
                
                        $keterangan = match (true) {
                            str_contains($roles, 'penjaga lapak') => 'Lapak Didalam Nyoofresh',
                            str_contains($roles, 'dropping') => 'Lapak Diluar Nyoofresh',
                            default => '-',
                        };
                
                        return "{$namaUser} - {$keterangan}";
                    })
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('nama_produk')
                    ->label('Nama Produk')
                    ->required(),
                Forms\Components\Select::make('kategori')
                    ->label('Kategori Produk')
                    ->options([
                        'Buah' => 'Buah',
                        'Sayur' => 'Sayur',
                        'Alat Rumah Tangga' => 'Alat Rumah Tangga',
                    ])
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
                    ->image(),
                Forms\Components\Hidden::make('supplier_id')
                    ->default(auth()->id())
                    ->required(),
                // Forms\Components\Hidden::make('id')
                //     ->default(function () {
                //         return Produk::latest()->first()?->id; // Mendapatkan ID produk terakhir
                //     })
                //     ->required(),
                    ]);
    }
    // public function afterSave(): void
    // {
    //     $produkId = $this->record->id;
        
    //     // Pastikan data tersimpan di tabel hasil
    //     HasilPenjualan::updateOrCreate(
    //         ['id_produk' => $this->$produkId], 
    //         [
    //             'id_produk' => $this->$produkId,
    //         ]
    //     );
    // }
    public static function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {
                $user = auth()->user();
                if ($user->hasRole('supplier')) {
                    return Produk::query()
                    ->where('supplier_id', auth()->user()->id)
                    ->where('is_active', 1);
                }
                return Produk::query();
                })
            ->columns([
                Tables\Columns\TextColumn::make('user_produk.name')
                    ->label('Lapak')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('suplai.id_produk')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->suplai->first()?->id_produk ? 'Produk Sudah Disuplai' : 'Produk Belum Disuplai')
                    ->searchable()
                    ->colors([
                        'success' => 'Produk Sudah Disuplai', 
                        'danger' => 'Produk Belum Disuplai',  
                    ]),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Acc Produk')
                    ->onColor('success') 
                    ->offColor('danger') 
                    ->toggleable()
                    ->searchable()
                    ->visible(fn () => auth()->user()->can('acc-produk')),
                Tables\Columns\TextColumn::make('nama_produk')
                    ->label('Nama Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi Produk')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga_kulak')
                    ->label('Harga Kulak')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga_jual')
                    ->label('Harga Jual')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->searchable(),
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
