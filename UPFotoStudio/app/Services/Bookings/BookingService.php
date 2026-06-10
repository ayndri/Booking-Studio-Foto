<?php

namespace App\Services\Bookings;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\PaymentTransaction;
use App\Models\ServicePackage;
use App\Models\Studio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
                ? max((int) round($totalAmount * 0.3), 50000)
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

            return [
                'booking' => $booking->fresh(['guest', 'studio', 'servicePackage', 'paymentTransaction']),
                'transaction' => $transaction->fresh(),
                'payment_url' => is_string($gatewayResponse['payment_url'] ?? null)
                    ? $gatewayResponse['payment_url']
                    : null,
            ];
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
