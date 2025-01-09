<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengeluaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal_pengeluaran', 
        'keperluan',
        'jumlah_keperluan',
        'user_id',
        'harga'
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
}
