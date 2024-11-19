<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Impor HasFactory

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'lapak',
        'nama_produk',
        'deskripsi',
        'harga_kulak',
        'foto_produk'
    ];

}
