<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suplai extends Model
{
    use HasFactory;

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
