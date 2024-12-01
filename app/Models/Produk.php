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
        'lapak',
        'nama_produk',
        'deskripsi',
        'harga_kulak',
        'harga_jual',
        'foto_produk'
    ];
    public function suplai(): HasMany
    {
        return $this->hasMany(Suplai::class);
    }
}
