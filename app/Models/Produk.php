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
        'kategori',
        'harga_kulak',
        'is_active',        
        'supplier_id',
        'user_id',
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
    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'supplier_id');
    }
    public function user_produk(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
