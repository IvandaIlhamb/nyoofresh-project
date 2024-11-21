<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengeluaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal_pengeluaran', 
        'keperluan',
        'jumlah_keperluan'
    ];
}
