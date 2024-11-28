<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengeluaranResource\Pages;
use App\Filament\Resources\PengeluaranResource\RelationManagers;
use App\Models\Pengeluaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class PengeluaranResource extends Resource
{
    protected static ?string $model = Pengeluaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // ----------hidden pengeluaran jika tidak memiliki akses
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('pengeluaran'))
            return true;
        else
            return false;
    }

    // ----------hidden akses url /pengeluaran jika tidak memiliki akses
    public static function canViewAny(): bool
    {
        if(auth()->user()->can('pengeluaran'))
            return true;
        else
            return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal_pengeluaran')
                    ->default(Carbon::now()->format('d-m-Y'))
                    ->label('Tanggal Pengeluaran')
                    ->required(),
                Forms\Components\TextInput::make('keperluan')
                    ->label('Keperluan')
                    ->required(),
                Forms\Components\TextInput::make('jumlah_keperluan')
                    ->label('Jumlah')
                    ->integer()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_pengeluaran')
                    ->default(Carbon::now()->format('d-m-Y'))
                    ->getStateUsing(fn ($record) => Carbon::parse($record->tanggal_pengeluaran)->format('d-m-Y')),
                Tables\Columns\TextColumn::make('keperluan')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('jumlah_keperluan')
                    ->label('Jumlah Keperluan'),
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
            'index' => Pages\ListPengeluarans::route('/'),
            'create' => Pages\CreatePengeluaran::route('/create'),
            'edit' => Pages\EditPengeluaran::route('/{record}/edit'),
        ];
    }
}
