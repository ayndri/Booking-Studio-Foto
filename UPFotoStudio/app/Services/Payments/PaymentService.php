<?php

namespace App\Services\Payments;

use App\Models\Booking;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    /**
     * Memproses callback notifikasi pembayaran dari gateway.
     *
     * @param array<string, mixed> $payload
     */
    public function processCallback(array $payload): PaymentTransaction
    {
        return DB::transaction(function () use ($payload) {
            $invoiceNumber = (string) ($payload['invoice_number'] ?? '');
            $callbackStatus = strtoupper((string) ($payload['status'] ?? ''));

            if ($invoiceNumber === '') {
                throw ValidationException::withMessages([
                    'invoice_number' => 'Invoice number wajib diisi pada callback.',
                ]);
            }

            if (!in_array($callbackStatus, [
                PaymentTransaction::STATUS_SUCCESS,
                PaymentTransaction::STATUS_FAILED,
                PaymentTransaction::STATUS_EXPIRED,
            ], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Status callback tidak valid.',
                ]);
            }

            $transaction = PaymentTransaction::query()
                ->where('invoice_number', $invoiceNumber)
                ->lockForUpdate()
                ->first();

            if (!$transaction) {
                throw ValidationException::withMessages([
                    'invoice_number' => 'Invoice tidak ditemukan.',
                ]);
            }

            // Callback idempotent: jika sudah SUCCESS tetap dipertahankan.
            if ($transaction->status === PaymentTransaction::STATUS_SUCCESS) {
                $transaction->update([
                    'callback_payload' => $payload,
                ]);

                return $transaction->fresh(['booking']);
            }

            $transaction->status = $callbackStatus;
            $transaction->callback_payload = $payload;

            if ($callbackStatus === PaymentTransaction::STATUS_SUCCESS) {
                $transaction->paid_at = now();
            }

            $transaction->save();

            $booking = $transaction->booking()->lockForUpdate()->first();

            if ($booking) {
                $booking->status = $callbackStatus === PaymentTransaction::STATUS_SUCCESS
                    ? Booking::STATUS_CONFIRMED
                    : Booking::STATUS_CANCELLED;
                $booking->save();
            }

            return $transaction->fresh([
                'booking',
                'booking.guest',
                'booking.studio',
                'booking.servicePackage',
            ]);
        });
    }
}
