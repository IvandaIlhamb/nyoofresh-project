<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Suplai extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal', 
        'nama_produk', 
        'jumlah_suplai'
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
