<?php

namespace App\Services\Bookings;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Mail\BookingPendingMail;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\PaymentTransaction;
use App\Models\ServicePackage;
use App\Models\Studio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(private readonly PaymentGatewayInterface $paymentGateway)
    {
    }

    /**
     * Membuat booking + transaksi pembayaran secara atomik.
     *
     * @param array<string, mixed> $payload
     * @return array{booking: Booking, transaction: PaymentTransaction, payment_url: string|null}
     */
    public function createBooking(array $payload): array
    {
        return DB::transaction(function () use ($payload) {
            $studio = Studio::query()
                ->whereKey($payload['studio_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (!$studio) {
                throw ValidationException::withMessages([
                    'studio_id' => 'Studio tidak ditemukan atau sedang nonaktif.',
                ]);
            }

            $servicePackage = ServicePackage::query()
                ->whereKey($payload['service_package_id'])
                ->where('studio_id', $studio->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (!$servicePackage) {
                throw ValidationException::withMessages([
                    'service_package_id' => 'Paket layanan tidak valid untuk studio ini.',
                ]);
            }

            $startAt = Carbon::createFromFormat(
                'Y-m-d H:i',
                sprintf('%s %s', $payload['booking_date'], $payload['start_time'])
            );
            $endAt = (clone $startAt)->addMinutes($servicePackage->duration_minutes);

            // Jadwal tidak boleh di masa lalu (relatif terhadap waktu sekarang).
            if ($startAt->lte(now())) {
                throw ValidationException::withMessages([
                    'start_time' => 'Jadwal yang dipilih sudah lewat. Silakan pilih waktu lain.',
                ]);
            }

            // Jam operasional studio 10:00–21:00; sesi harus selesai sebelum tutup.
            $openAt = $startAt->copy()->setTime(10, 0);
            $closeAt = $startAt->copy()->setTime(21, 0);

            if ($startAt->lt($openAt) || $endAt->gt($closeAt)) {
                throw ValidationException::withMessages([
                    'start_time' => 'Jadwal di luar jam operasional studio (10:00–21:00).',
                ]);
            }

            // Anti double booking: cek interval overlap pada studio yang sama.
            $hasOverlap = Booking::query()
                ->where('studio_id', $studio->id)
                ->whereDate('booking_date', $payload['booking_date'])
                ->whereIn('status', [Booking::STATUS_PENDING_PAYMENT, Booking::STATUS_CONFIRMED])
                ->where(function ($query) use ($startAt, $endAt) {
                    $query->where('start_time', '<', $endAt->format('H:i:s'))
                        ->where('end_time', '>', $startAt->format('H:i:s'));
                })
                ->lockForUpdate()
                ->exists();

            if ($hasOverlap) {
                throw ValidationException::withMessages([
                    'start_time' => 'Jadwal bentrok dengan booking lain pada studio yang sama.',
                ]);
            }

            // Batasi jumlah booking belum dibayar (PENDING) yang aktif per email,
            // untuk mencegah penumpukan slot oleh satu orang. Booking yang sudah
            // kadaluarsa (expires_at lewat) tidak dihitung.
            $maxPending = (int) config('services.booking.max_pending_per_email', 3);

            if ($maxPending > 0) {
                $activePending = Booking::query()
                    ->where('status', Booking::STATUS_PENDING_PAYMENT)
                    ->whereHas('guest', fn ($query) => $query->where('email', $payload['guest_email']))
                    ->whereHas('paymentTransaction', function ($query) {
                        $query->where('status', PaymentTransaction::STATUS_PENDING)
                            ->where(function ($inner) {
                                $inner->whereNull('expires_at')
                                    ->orWhere('expires_at', '>', now());
                            });
                    })
                    ->count();

                if ($activePending >= $maxPending) {
                    throw ValidationException::withMessages([
                        'guest_email' => "Email ini sudah memiliki {$maxPending} booking yang menunggu pembayaran. Selesaikan pembayaran tersebut terlebih dahulu.",
                    ]);
                }
            }

            $guest = Guest::firstOrCreate(
                [
                    'email' => $payload['guest_email'],
                    'phone' => $payload['guest_phone'],
                ],
                [
                    'full_name' => $payload['guest_name'],
                ]
            );

            if ($guest->full_name !== $payload['guest_name']) {
                $guest->update(['full_name' => $payload['guest_name']]);
            }

            $addOnAmount = (int) ($payload['add_on_amount'] ?? 0);
            $totalAmount = (int) $servicePackage->price + $addOnAmount;

            $paymentType = $payload['payment_type'];
            $paymentAmount = $paymentType === Booking::PAYMENT_DP
                ? (int) round($totalAmount * 0.3)
                : $totalAmount;

            $booking = Booking::create([
                'booking_code' => $this->generateBookingCode(),
                'guest_id' => $guest->id,
                'studio_id' => $studio->id,
                'service_package_id' => $servicePackage->id,
                'booking_date' => $payload['booking_date'],
                'start_time' => $startAt->format('H:i:s'),
                'end_time' => $endAt->format('H:i:s'),
                'add_on_amount' => $addOnAmount,
                'total_amount' => $totalAmount,
                'payment_type' => $paymentType,
                'status' => Booking::STATUS_PENDING_PAYMENT,
                'notes' => $payload['notes'] ?? null,
            ]);

            $invoiceNumber = $this->generateInvoiceNumber();

            $transaction = PaymentTransaction::create([
                'booking_id' => $booking->id,
                'invoice_number' => $invoiceNumber,
                'payment_type' => $paymentType,
                'payment_method' => 'QRIS',
                'amount' => $paymentAmount,
                'status' => PaymentTransaction::STATUS_PENDING,
            ]);

            $gatewayResponse = $this->paymentGateway->createQrisPayment([
                'invoice_number' => $invoiceNumber,
                'amount' => $paymentAmount,
                'customer_name' => $guest->full_name,
                'customer_email' => $guest->email,
                'customer_phone' => $guest->phone,
                'booking_code' => $booking->booking_code,
            ]);

            $transaction->update([
                'gateway_reference' => $gatewayResponse['reference'] ?? null,
                'qr_payload' => $gatewayResponse['qr_string'] ?? null,
                'expires_at' => $gatewayResponse['expires_at'] ?? now()->addMinutes(30),
            ]);

            $paymentUrl = is_string($gatewayResponse['payment_url'] ?? null)
                ? $gatewayResponse['payment_url']
                : null;

            $this->sendPendingEmail(
                $transaction->fresh(['booking.guest', 'booking.studio', 'booking.servicePackage']),
                $paymentUrl ?: route('frontend.booking.status', $invoiceNumber)
            );

            return [
                'booking' => $booking->fresh(['guest', 'studio', 'servicePackage', 'paymentTransaction']),
                'transaction' => $transaction->fresh(),
                'payment_url' => $paymentUrl,
            ];
        });
    }

    /**
     * Buat ulang QR/pembayaran untuk transaksi yang kadaluarsa atau gagal.
     *
     * Memakai ulang baris transaksi yang sama (invoice baru, status PENDING)
     * agar tetap satu transaksi per booking. Slot & jadwal divalidasi ulang
     * supaya tidak menabrak booking lain yang sudah mengambil slot tersebut.
     *
     * @return array{transaction: PaymentTransaction, payment_url: string|null}
     */
    public function regeneratePayment(PaymentTransaction $transaction): array
    {
        return DB::transaction(function () use ($transaction) {
            $locked = PaymentTransaction::query()
                ->whereKey($transaction->id)
                ->lockForUpdate()
                ->first();

            if (!$locked) {
                throw ValidationException::withMessages([
                    'payment' => 'Transaksi tidak ditemukan.',
                ]);
            }

            if ($locked->status === PaymentTransaction::STATUS_SUCCESS) {
                throw ValidationException::withMessages([
                    'payment' => 'Pembayaran sudah berhasil, tidak perlu membuat QR baru.',
                ]);
            }

            // Hanya boleh regenerate bila sudah kadaluarsa/gagal. Bila masih
            // PENDING dan belum lewat masa berlaku, arahkan ke QR yang ada.
            $isReusable = in_array($locked->status, [
                PaymentTransaction::STATUS_EXPIRED,
                PaymentTransaction::STATUS_FAILED,
            ], true);

            $stillActive = $locked->status === PaymentTransaction::STATUS_PENDING
                && ($locked->expires_at === null || $locked->expires_at->isFuture());

            if (!$isReusable && $stillActive) {
                throw ValidationException::withMessages([
                    'payment' => 'Pembayaran masih aktif. Silakan selesaikan QR yang masih berlaku.',
                ]);
            }

            $booking = $locked->booking()->lockForUpdate()->first();

            if (!$booking) {
                throw ValidationException::withMessages([
                    'payment' => 'Booking terkait tidak ditemukan.',
                ]);
            }

            $startAt = Carbon::parse($booking->booking_date->toDateString() . ' ' . $booking->start_time);

            if ($startAt->lte(now())) {
                throw ValidationException::withMessages([
                    'payment' => 'Jadwal booking ini sudah lewat. Silakan buat booking baru dengan jadwal lain.',
                ]);
            }

            // Pastikan slot belum diambil booking lain (selain booking ini).
            $hasOverlap = Booking::query()
                ->where('studio_id', $booking->studio_id)
                ->whereKeyNot($booking->id)
                ->whereDate('booking_date', $booking->booking_date->toDateString())
                ->whereIn('status', [Booking::STATUS_PENDING_PAYMENT, Booking::STATUS_CONFIRMED])
                ->where(function ($query) use ($booking) {
                    $query->where('start_time', '<', $booking->end_time)
                        ->where('end_time', '>', $booking->start_time);
                })
                ->lockForUpdate()
                ->exists();

            if ($hasOverlap) {
                throw ValidationException::withMessages([
                    'payment' => 'Slot jadwal ini sudah dipesan orang lain. Silakan buat booking baru dengan jadwal lain.',
                ]);
            }

            // Hidupkan kembali booking (sebelumnya CANCELLED saat auto-release).
            $booking->update(['status' => Booking::STATUS_PENDING_PAYMENT]);

            // Invoice baru wajib: order_id Midtrans harus unik per transaksi Snap.
            $invoiceNumber = $this->generateInvoiceNumber();

            $locked->update([
                'invoice_number' => $invoiceNumber,
                'status' => PaymentTransaction::STATUS_PENDING,
                'qr_payload' => null,
                'gateway_reference' => null,
                'paid_at' => null,
                'expires_at' => null,
                'callback_payload' => null,
            ]);

            $guest = $booking->guest;

            $gatewayResponse = $this->paymentGateway->createQrisPayment([
                'invoice_number' => $invoiceNumber,
                'amount' => (int) $locked->amount,
                'customer_name' => $guest?->full_name,
                'customer_email' => $guest?->email,
                'customer_phone' => $guest?->phone,
                'booking_code' => $booking->booking_code,
            ]);

            $locked->update([
                'gateway_reference' => $gatewayResponse['reference'] ?? null,
                'qr_payload' => $gatewayResponse['qr_string'] ?? null,
                'expires_at' => $gatewayResponse['expires_at'] ?? now()->addMinutes(30),
            ]);

            $paymentUrl = is_string($gatewayResponse['payment_url'] ?? null)
                ? $gatewayResponse['payment_url']
                : null;

            $this->sendPendingEmail(
                $locked->fresh(['booking.guest', 'booking.studio', 'booking.servicePackage']),
                $paymentUrl ?: route('frontend.booking.status', $invoiceNumber)
            );

            return [
                'transaction' => $locked->fresh(),
                'payment_url' => $paymentUrl,
            ];
        });
    }

    /**
     * Kirim email instruksi pembayaran ke tamu, setelah transaksi commit.
     * Kegagalan email di-log dan tidak mengganggu proses booking.
     */
    private function sendPendingEmail(PaymentTransaction $transaction, string $payUrl): void
    {
        $email = $transaction->booking?->guest?->email;

        if (!$email) {
            return;
        }

        DB::afterCommit(function () use ($transaction, $email, $payUrl) {
            try {
                Mail::to($email)->send(new BookingPendingMail($transaction, $payUrl));
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim email instruksi pembayaran.', [
                    'invoice' => $transaction->invoice_number,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'BOOK-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }

    private function generateInvoiceNumber(): string
    {
        do {
            $invoice = 'INV-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        } while (PaymentTransaction::where('invoice_number', $invoice)->exists());

        return $invoice;
    }
}
