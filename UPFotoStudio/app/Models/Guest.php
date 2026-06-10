<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
    ];

    /**
     * Relasi semua booking milik guest.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
