<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'roles',
        'email',
        'password',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function suplai(): BelongsTo
    {
        return $this->belongsTo(Suplai::class, 'id', 'user_id');
    }
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'supplier_id');
    }
    public function hasil(): BelongsTo
    {
        return $this->belongsTo(HasilPenjualan::class, 'user_id');
    }
    public function user_produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'user_id');
    }
    public function pengeluaran(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'user_id');
    }
    public function gaji(): HasMany
    {
        return $this->hasMany(Gaji::class);
    }
    protected static function booted()
    {
        static::saved(function ($user) {
            $roles = $user->roles()->pluck('name')->toArray();

            if (in_array('supplier', $roles)) {
                \App\Models\Suplai::updateOrCreate(
                    ['user_id' => $user->id], 
                    ['nama_supplier' => $user->name]
                );
            }
        });
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
