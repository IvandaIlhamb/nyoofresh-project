<?php

namespace App\Models;

use App\Enums\StatusToko;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Suplai extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal', 
        // 'produk_id',
        'nama_produk', 
        'status',
        'jumlah_suplai'
    ];

    protected $casts = [
        'status' => StatusToko::class
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'nama_produk');
    }
    
}