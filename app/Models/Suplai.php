<?php

namespace App\Models;

use App\Enums\StatusToko;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Suplai extends Model
{
    use HasFactory;
    protected $table = 'suplais';
    protected $fillable = [
        'tanggal', 
        'nama_supplier',
        'id_produk', 
        'status',
        'user_id',
        'is_active',
        'jumlah_suplai'
    ];

    protected $casts = [
        'status' => StatusToko::class
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
    public function hasilpenjualan(): HasMany
    {
        return $this->hasMany(HasilPenjualan::class, 'id_suplai');
    }
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}