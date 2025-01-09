<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gaji extends Model
{
    protected $table = 'gajis';
    protected $fillable = [
        'id',
        'user_id',
        'bulan',
        'tahun',
        'gaji'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $bulan = $model->bulan;
            $tahun = $model->tahun;
            $user_id = $model->user_id; 
            // dd($bulan, $tahun, $user_id);

            if ($bulan && $tahun && $user_id) {
                // Hitung total keuntungan berdasarkan bulan, tahun, dan user_id
                $hasil = HasilPenjualan::whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->where('user_id', $user_id)
                    ->sum('keuntungan');

                $pengeluaran = Pengeluaran::whereMonth('tanggal_pengeluaran', $bulan)
                    ->whereYear('tanggal_pengeluaran', $tahun)
                    ->where('user_id', $user_id)
                    ->sum('harga');

                $model->gaji = $hasil - $pengeluaran;
            } else {
                $model->gaji = 0; // Jika data tidak lengkap, set gaji ke 0
            }
        });
        
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
