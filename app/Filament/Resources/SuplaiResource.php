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
                Forms\Components\DatePicker::make('tanggal')
                    ->default(Carbon::now()->format('d-m-Y'))
                    ->label('Tanggal')
                    ->required(),
                Forms\Components\Select::make('nama_produk')
                    ->relationship('produk', 'nama_produk')
                    ->label('Nama Produk')
                    ->native(false)
                    ->required(),
                //  ->searchable()
                Forms\Components\TextInput::make('jumlah_suplai')
                    ->numeric()
                    ->label('Jumlah Produk')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->getStateUsing(fn ($record) => Carbon::parse($record->tanggal_pengeluaran)->format('d-m-Y'))
                    ->label('Tanggal'),
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('jumlah_suplai')
                    ->label('Nama Produk'),
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
            'index' => Pages\ListSuplais::route('/'),
            'create' => Pages\CreateSuplai::route('/create'),
            'edit' => Pages\EditSuplai::route('/{record}/edit'),
        ];
    }
}
