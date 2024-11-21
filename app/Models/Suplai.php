<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Suplai extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal', 
        'produk_id',
        'nama_produk', 
        'jumlah_suplai'
    ];

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
}
