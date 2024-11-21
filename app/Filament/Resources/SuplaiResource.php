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


class SuplaiResource extends Resource
{
    protected static ?string $model = Suplai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->format('Y-m-d')
                    ->label('Tanggal')
                    ->required(),
                Forms\Components\Select::make('produk_id')
                    ->relationship('produk', 'nama_produk')
                    ->label('Nama Produk')
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
                    ->numeric()
                    ->label('Tanggal'),
                Tables\Columns\TextColumn::make('suplai.nama_produk')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('jumlah_suplai')
                    ->numeric()
                    ->label('Nama Produk'),
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
            'index' => Pages\ListSuplais::route('/'),
            'create' => Pages\CreateSuplai::route('/create'),
            'edit' => Pages\EditSuplai::route('/{record}/edit'),
        ];
    }
}
