<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Impor HasFactory
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Produk extends Model
{
    use HasFactory;
    protected $table = 'produks';
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
        return $this->hasMany(HasilPenjualan::class, 'produk_id', 'id');
    }
    // protected static function booted()
    // {
    //     static::saved(function ($produk) {
    //         // $produk = Produk::first();
    //         HasilPenjualan::updateOrCreate(
    //             ['produk_id' => $produk->id], // Kondisi untuk mencegah duplikasi
    //             [/* kolom lain jika perlu */]
    //         );
    //     });
    // }

}
