<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand',
        'model',
        'tahun',
        'plat_nomor',
        'harga_sewa_per_hari',
        'status',
        'gambar',
    ];

    /**
     * Get the orders for the car.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
