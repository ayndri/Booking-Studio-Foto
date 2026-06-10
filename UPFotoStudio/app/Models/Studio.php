<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Paket layanan yang tersedia di studio ini.
     */
    public function servicePackages(): HasMany
    {
        return $this->hasMany(ServicePackage::class);
    }

    /**
     * Relasi booking ke studio.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
