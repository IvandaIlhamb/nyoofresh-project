<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Impor HasFactory
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'lapak',
        'nama_produk',
        'deskripsi',
        'harga_kulak',
        'foto_produk'
    ];
    public function suplai(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
