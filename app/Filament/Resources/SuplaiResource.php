<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Suplai;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SuplaiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SuplaiResource\RelationManagers;
use Filament\Forms\Components\Select;
use Carbon\Carbon;
use App\Enums\StatusToko;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SuplaiResource extends Resource
{
    protected static ?string $model = Suplai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // ----------hidden suplai jika tidak memiliki akses
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('suplai'))
            return true;
        else
            return false;
    }

    // ----------hidden akses url /suplai jika tidak memiliki akses
    // public static function canViewAny(): bool
    // {
    //     if(auth()->user()->can('suplai'))
    //         return true;
    //     else
    //         return false;
    // }
    // Membatasi akses untuk membuat user
    public static function canCreate(): bool
    {
        if(auth()->user()->can('create-suplai'))
            return true;
        else
            return false;
    }

    // Membatasi akses untuk mengedit user
    public static function canEdit($record): bool
    {
        if(auth()->user()->can('edit-suplai'))
            return true;
        else
            return false;
    }

    // Membatasi akses untuk menghapus user
    public static function canDelete($record): bool
    {
        if(auth()->user()->can('delete-suplai'))
            return true;
        else
            return false;
    }
    public static function canView($record): bool
    {
        if(auth()->user()->can('view-suplai'))
            return true;
        else
            return false;
    }
    public static function canViewAny(): bool
    {
        if(auth()->user()->can('suplai'))
            return true;
        else
            return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options(StatusToko::class)
                    ->native(false)
                    ->default(StatusToko::Tutup)
                    ->label('Status Toko')
                    ->required()
                    ->reactive(),
                Forms\Components\DatePicker::make('tanggal')
                    ->default(Carbon::now()->format('d-m-Y'))
                    ->label('Tanggal')
                    ->required(),
                    // ->readOnly(fn (callable $get) => $get('status') !== StatusToko::Buka),
                Forms\Components\TextInput::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->required()
                    ->default(auth()->user()->name)
                    ->readonly()
                    ->extraAttributes([
                        'style' => 'background-color: #f0f0f0; color: #888; cursor: not-allowed;',  
                    ]),
                Forms\Components\Select::make('id_produk')
                    ->relationship('produk', 'nama_produk', function (\Illuminate\Database\Eloquent\Builder $query) {
                        $query->where('supplier_id', auth()->user()->id)
                        ->where('is_active', 1);
                    })
                    ->placeholder('Tidak Ada Suplai Produk')
                    ->label('Nama Produk')
                    ->disabled(fn (callable $get) => $get('status') === StatusToko::Tutup),
                Forms\Components\TextInput::make('jumlah_suplai')
                    ->numeric()
                    ->label('Jumlah Produk')
                    ->readOnly(fn (callable $get) => $get('status') === StatusToko::Tutup),
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {
                $user = auth()->user();
                if ($user->hasRole('supplier')) {
                    return Suplai::query()->where('nama_supplier', auth()->user()->name);
                }elseif($user->hasRole('dropping')){
                    return Suplai::query()
                    ->whereHas('produk', function ($query) {
                        $query->where('lapak', 'Diluar Nyoofresh');
                    });
                }elseif($user->hasRole('penjaga lapak')){
                    return Suplai::query()
                    ->whereHas('produk', function ($query) {
                        $query->where('lapak', 'Lapak Nyoofresh');
                    });
                }
                return Suplai::query();
                })
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->getStateUsing(fn ($record) => 
                        $record->tanggal ? \Carbon\Carbon::parse($record->tanggal)->format('d-m-Y') : null 
                        )
                    ->label('Tanggal'),
                    // ->visible(fn ($record) => auth()->user()->can('view-suplai')),
                Tables\Columns\TextColumn::make('nama_supplier')
                    ->label('Nama Supplier'),
                    // ->visible(fn ($record) => auth()->user()->can('view-suplai')),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Is Active')
                    ->visible(fn () => auth()->user()->can('activated-suplai')),
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->label('Nama Produk')
                    ->default('-'),
                    // ->visible(fn ($record) => auth()->user()->can('view-suplai')),
                Tables\Columns\TextColumn::make('jumlah_suplai')
                    ->label('Jumlah Suplai')
                    ->default('-'),
                    // ->visible(fn ($record) => auth()->user()->can('view-suplai')),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Toko')
                    ->badge(StatusToko::class),
                    // ->visible(fn ($record) => auth()->user()->can('view-suplai')),
            ])
            ->filters([
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
            'index' => Pages\ListSuplais::route('/'),
            'create' => Pages\CreateSuplai::route('/create'),
            'edit' => Pages\EditSuplai::route('/{record}/edit'),
        ];
    }
}