<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_PENDING_PAYMENT = 'PENDING_PAYMENT';
    public const STATUS_CONFIRMED = 'CONFIRMED';
    public const STATUS_CANCELLED = 'CANCELLED';
    public const STATUS_COMPLETED = 'COMPLETED';

    public const PAYMENT_DP = 'DP';
    public const PAYMENT_LUNAS = 'LUNAS';

    protected $fillable = [
        'booking_code',
        'guest_id',
        'studio_id',
        'service_package_id',
        'booking_date',
        'start_time',
        'end_time',
        'add_on_amount',
        'total_amount',
        'payment_type',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'add_on_amount' => 'integer',
        'total_amount' => 'integer',
    ];

    /**
     * Data tamu yang melakukan booking.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Studio yang dipilih pada booking.
     */
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    /**
     * Paket layanan yang dipilih pada booking.
     */
    public function servicePackage(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class);
    }

    /**
     * Setiap booking memiliki satu transaksi pembayaran aktif.
     */
    public function paymentTransaction(): HasOne
    {
        return $this->hasOne(PaymentTransaction::class);
    }
}
