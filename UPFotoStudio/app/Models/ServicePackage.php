<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServicePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'studio_id',
        'name',
        'description',
        'image_path',
        'price',
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Paket dimiliki oleh satu studio.
     */
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    /**
     * Riwayat booking yang menggunakan paket ini.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * URL gambar paket dengan fallback default bila belum ada upload.
     */
    public function getImageUrlAttribute(): string
    {
        $path = trim((string) $this->image_path);

        if ($path !== '') {
            if (
                str_starts_with($path, 'http://')
                || str_starts_with($path, 'https://')
                || str_starts_with($path, 'data:')
            ) {
                return $path;
            }

            if (str_starts_with($path, 'storage/')) {
                return asset(ltrim($path, '/'));
            }

            return Storage::disk('public')->url($path);
        }

        $fallbacks = [
            asset('assets/images/home/gallery/gallery-1.svg'),
            asset('assets/images/home/gallery/gallery-2.svg'),
            asset('assets/images/home/gallery/gallery-3.svg'),
            asset('assets/images/home/gallery/gallery-4.svg'),
            asset('assets/images/home/gallery/gallery-5.svg'),
        ];

        $index = max(((int) $this->id) - 1, 0) % count($fallbacks);

        return $fallbacks[$index];
    }
}
