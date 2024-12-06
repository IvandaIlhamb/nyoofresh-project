<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Impor HasFactory
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'lapak',
        'nama_produk',
        'deskripsi',
        'harga_kulak',
        'is_active',        
        'supplier_id',
        'harga_jual',
        'foto_produk'
    ];
    public function suplai(): HasMany
    {
        return $this->hasMany(Suplai::class, 'id_produk');
    }
    public function hasil(): HasMany
    {
        return $this->hasMany(HasilPenjualan::class);
    }
    // protected static function booted()
    // {
    //     static::saved(function ($produk) {
    //         HasilPenjualan::create([
    //             'id_produk' => $produk->id,
    //             'tanggal' => now(),
    //         ]);
    //     });
    // }

}
