<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_SUCCESS = 'SUCCESS';
    public const STATUS_FAILED = 'FAILED';
    public const STATUS_EXPIRED = 'EXPIRED';

    protected $fillable = [
        'booking_id',
        'invoice_number',
        'payment_type',
        'payment_method',
        'amount',
        'status',
        'gateway_reference',
        'qr_payload',
        'paid_at',
        'expires_at',
        'callback_payload',
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'callback_payload' => 'array',
    ];

    /**
     * Transaksi selalu terkait ke satu booking.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
