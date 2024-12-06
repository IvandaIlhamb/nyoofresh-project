<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasilPenjualan extends Model
{
    protected $table = 'hasil_penjualans';
    protected $fillable = [
        'tanggal', 
        'id_suplai',
        'id_produk',
        'terjual',
        'kembali'
    ];

    public function suplai(): BelongsTo
    {
        return $this->belongsTo(Suplai::class, 'id_suplai');
    }
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }


}
