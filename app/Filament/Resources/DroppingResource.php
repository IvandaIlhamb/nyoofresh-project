<?php

namespace App\Filament\Resources;

use App\Enums\StatusToko;
use App\Filament\Resources\DroppingResource\Pages;
use App\Filament\Resources\DroppingResource\RelationManagers;
use App\Models\Dropping;
use App\Models\Suplai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DroppingResource extends Resource
{
    protected static ?string $model = Dropping::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('dropping'))
            return true;
        else
            return false;
    }

    // ----------hidden akses url /dropping jika tidak memiliki akses
    public static function canView($record): bool
    {
        if(auth()->user()->can('view-dropping'))
            return true;
        else
            return false;
    }
    public static function canCreate(): bool
    {
        if(auth()->user()->can('create-dropping'))
            return true;
        else
            return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(function (Builder $query) {
            $user = auth()->user();
            if($user->hasRole('dropping')){
                return Suplai::query()
                ->whereHas('produk', function ($query) {
                    $query->where('lapak', 'Diluar Nyoofresh');
                });
            }
            return Suplai::query();
            })
        ->columns([
            Tables\Columns\TextColumn::make('suplai.tanggal')
                ->getStateUsing(fn ($record) => 
                    $record->tanggal ? \Carbon\Carbon::parse($record->tanggal)->isoformat('D MMMM Y') : null 
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
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn () => false), 
            ]);;
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
            'index' => Pages\ListDroppings::route('/'),
            'create' => Pages\CreateDropping::route('/create'),
            'edit' => Pages\EditDropping::route('/{record}/edit'),
        ];
    }
}
