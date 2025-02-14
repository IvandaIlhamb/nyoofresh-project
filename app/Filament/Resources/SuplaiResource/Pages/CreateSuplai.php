<?php

namespace App\Filament\Resources\SuplaiResource\Pages;

use App\Filament\Resources\SuplaiResource;
use App\Models\Suplai;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
class CreateSuplai extends CreateRecord
{
    protected static string $resource = SuplaiResource::class;
    protected function getRedirectUrl(): string
    {       
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data); 
        // Cek apakah sudah ada suplai dengan tanggal dan id_produk yang sama
        $existingSuplai = Suplai::where('tanggal', $data['tanggal'])
            ->where('id_produk', $data['id_produk'])
            ->first();

        if ($existingSuplai) {
            // Jika sudah ada, tambahkan jumlah suplai
            $existingSuplai->increment('jumlah_suplai', $data['jumlah_suplai']);

            // unurk redirect ke halaman index
            redirect($this->getResource()::getUrl('index'));

            // untuk membatalkan pembuatan record baru
            $this->halt();

            // exit;
        }

        return $data;
    }
    // public function afterCreate(): void
    // {
    //     // Flash message untuk memberi notifikasi kepada pengguna
    //     session()->flash('success', 'Jumlah suplai berhasil diperbarui.');

    //     // Redirect ke halaman index Filament Resource
    //     $this->redirect($this->getRedirectUrl());
    // }
    
}
