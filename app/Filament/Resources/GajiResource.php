<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GajiResource\Pages;
use App\Filament\Resources\GajiResource\RelationManagers;
use App\Models\Gaji;
use App\Models\HasilPenjualan;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GajiResource extends Resource
{
    protected static ?string $model = Gaji::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    // ----------hidden gaji jika tidak memiliki akses
    // note : tambahkan "visible(static::shouldRegisterNavigation())" di vendor->filament->filament->src->resources->resource.php
    // didalam public static function getNavigationItems(): array
    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('gaji'))
            return true;
        else
            return false;
    }
    public static function canViewAny(): bool
    {
        if(auth()->user()->can('gaji'))
            return true;
        else
            return false;
    }
    protected function getMonthNumber($bulan)
    {
        $months = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12,
        ];

        return $months[$bulan] ?? null;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pilih User - Role')
                    ->required()
                    ->relationship('user', 'name', function ($query) {
                        // Ambil user beserta relasi ke role
                        $query->whereHas('roles', function ($query) {
                            $query->whereIn('name', ['penjaga lapak', 'dropping']);
                        });
                    })
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $namaUser = $record->name;
                        $roles = $record->roles->pluck('name')->join(', ');
                        return "{$namaUser} - {$roles}";
                    }),
                Forms\Components\Select::make('bulan')
                    ->label('Pilih Bulan')
                    ->options(function () {
                        $bulan = [
                            1 => 'Januari', 2 => 'Februari', 3  => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        ];
                
                        $options = [];
                        foreach ($bulan as $key => $namaBulan) {
                            $options[$key] = $namaBulan; // Gunakan kunci dari $bulan sebagai key
                        }
                
                        return $options;
                    })
                    ->required(),
                Forms\Components\Select::make('tahun')
                    ->label('Pilih Tahun') // Perbaiki label agar sesuai
                    ->options(function () {
                        $tahunSekarang = now()->year;
                        $tahunAwal = $tahunSekarang - 2;
                
                        // Membuat array dari range tahun
                        $options = [];
                        foreach (range($tahunAwal, $tahunSekarang) as $tahun) {
                            $options[$tahun] = $tahun; // Key dan value adalah tahun
                        }
                
                        return $options;
                    })
                    ->required(),
                Forms\Components\TextInput::make('gaji')
                    ->label('Gaji')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->maxValue(42949672.95),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->formatStateUsing(function ($state, $record) {
                        $roles = $record->roles->pluck('name')->join(', ');
                        return $roles ?: 'Tidak ada role';
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bulan')
                    ->label('Bulan')
                    ->formatStateUsing(function ($state) {
                        $bulan = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        ];
                
                        return $bulan[$state] ?? 'Tidak Diketahui';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tahun')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gaji')
                    ->label('Gaji')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')) // Format angka (opsional)
                    ,
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
            'index' => Pages\ListGajis::route('/'),
            'create' => Pages\CreateGaji::route('/create'),
            'edit' => Pages\EditGaji::route('/{record}/edit'),
        ];
    }
}
