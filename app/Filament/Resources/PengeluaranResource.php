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
use Illuminate\Database\Eloquent\Model;

class PengeluaranResource extends Resource
{
    protected static ?string $model = Pengeluaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // ----------hidden pengeluaran jika tidak memiliki akses
    // note : tambahkan "visible(static::shouldRegisterNavigation())" di vendor->filament->filament->src->resources->resource.php
    // didalam public static function getNavigationItems(): array
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('pengeluaran'))
            return true;
        else
            return false;
    }
    public static function canEdit($record): bool
    {
        $user = auth()->user();

        // Jika role adalah admin, maka tidak bisa mengedit
        if ($user->hasRole('admin')) {
            return false;
        }

        return true; // Untuk role lainnya, izinkan edit
    }
    public static function canCreate(): bool
    {
        $user = auth()->user();

        // Jika role adalah admin, maka tidak bisa create data
        if ($user->hasRole('admin')) {
            return false;
        }

        return true; // Untuk role lainnya, izinkan create
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        // Jika role adalah admin, maka tidak bisa delete data
        if ($user->hasRole('admin')) {
            return false;
        }

        return true; // Untuk role lainnya, izinkan delete   
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
                    ->default(Carbon::now()->isoformat('D MMMM Y'))
                    ->label('Tanggal Pengeluaran')
                    ->required(),
                Forms\Components\TextInput::make('keperluan')
                    ->label('Keperluan')
                    ->required(),
                Forms\Components\TextInput::make('jumlah_keperluan')
                    ->label('Jumlah')
                    ->integer()
                    ->required(),
                Forms\Components\TextInput::make('harga')
                    ->label('Harga')
                    ->integer()
                    ->required(),
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {
                return Pengeluaran::query()->where('user_id', auth()->user()->id);
                })
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_pengeluaran')
                    ->default(Carbon::now()->isoformat('D MMMM Y'))
                    ->getStateUsing(fn ($record) => Carbon::parse($record->tanggal_pengeluaran)->isoformat('D MMMM Y')),
                Tables\Columns\TextColumn::make('keperluan')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('jumlah_keperluan')
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Total Harga')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Penjaga')
                    ->visible(fn () => auth()->user()->hasRole('admin')),
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
