<?php

namespace App\Filament\Resources\SuplaiResource\Pages;

use App\Filament\Resources\SuplaiResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use App\Enums\StatusToko;
use Carbon\Carbon;

class EditSuplai extends EditRecord
{
    protected static string $resource = SuplaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function form(Form $form): Form
    {
        return $form
        ->schema([
             Select::make('status')
                ->options(StatusToko::class)
                ->native(false)
                ->default(StatusToko::Tutup)
                ->label('Status Toko')
                ->required()
                ->reactive(),
             DatePicker::make('tanggal')
                ->default(Carbon::now()->isoformat('D MMMM Y'))
                ->label('Tanggal')
                ->required()
                ->readOnly(),
             TextInput::make('nama_supplier')
                ->label('Nama Supplier')
                ->required()
                ->default(auth()->user()->name)
                ->readonly()
                ->extraAttributes([
                    'style' => 'background-color: #f0f0f0; color: #888; cursor: not-allowed;',  
                ]),
             Select::make('id_produk')
                ->relationship('produk', 'nama_produk', function (\Illuminate\Database\Eloquent\Builder $query) {
                    $query->where('supplier_id', auth()->user()->id)
                    ->where('is_active', 1);
                })
                ->options(function () {
                    return \App\Models\Produk::where('supplier_id', auth()->user()->id)
                        ->where('is_active', 1)
                        ->with('user_produk') // Pastikan relasi user di-load
                        ->get()
                        ->mapWithKeys(function ($produk) {
                            return [$produk->id => $produk->user_produk->name . ' - ' . $produk->nama_produk];
                        });
                })
                ->placeholder('Tidak Ada Suplai Produk')
                ->label('User - Nama Produk')
                ->disabled(fn (callable $get) => $get('status') === StatusToko::Tutup),
             Select::make('id_produk')
                ->relationship('produk', 'harga_jual', function (\Illuminate\Database\Eloquent\Builder $query) {
                    $query->where('supplier_id', auth()->user()->id)
                    ->where('is_active', 1);
                })
                ->placeholder('Tidak Ada Suplai Produk')
                ->label('Harga Jual')
                ->disabled(),
             TextInput::make('jumlah_suplai')
                ->numeric()
                ->label('Jumlah Produk')
                ->rules(function (\Filament\Forms\Get $get) {
                    $suplaiId = $get('jumlah_suplai');
                    $suplai = \App\Models\Suplai::find($suplaiId);
                    return $suplai ? "gt:{$suplai->jumlah_suplai}" : 'nullable';
                })
                ->readOnly(fn (callable $get) => $get('status') === StatusToko::Tutup),
             Hidden::make('user_id')
                ->default(auth()->id())
                ->required(),
        ]);
    }
}
