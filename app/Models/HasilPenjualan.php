<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilPenjualan extends Model
{
    protected $fillable = [
        'tanggal', 
        'id_suplai'
    ];

    public function suplai(): BelongsTo
    {
        return $this->belongsTo(Suplai::class, 'id_suplai');
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

}
