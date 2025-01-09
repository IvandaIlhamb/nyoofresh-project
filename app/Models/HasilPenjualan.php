<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasilPenjualan extends Model
{
    protected $table = 'hasil_penjualans';
    protected $fillable = [
        'id',
        'tanggal', 
        'id_suplai',
        'user_id',
        'produk_id',
        'terjual',
        'kembali',
        'keuntungan'
    ];

    public function suplai(): BelongsTo
    {
        return $this->belongsTo(Suplai::class, 'id_suplai');
    }
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $terjual = $model->terjual;
            $hargaJual = optional($model->suplai?->produk)->harga_jual;
            $suplai = $model->suplai->jumlah_suplai; 

            $model->keuntungan = $terjual && $hargaJual ? $terjual * $hargaJual : 0;
            $model->kembali = $suplai - $terjual;
        });
        
    }
    public static function totalKeuntunganBulanTahun($bulan, $tahun)
    {
        return self::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('keuntungan');
    }


}
