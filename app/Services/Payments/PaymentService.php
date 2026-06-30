<?php

namespace App\Services\Payments;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    /**
     * Memproses callback notifikasi pembayaran dari gateway (mock/manual).
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

            $transaction = $this->lockTransaction($invoiceNumber);

            if (!$transaction) {
                throw ValidationException::withMessages([
                    'invoice_number' => 'Invoice tidak ditemukan.',
                ]);
            }

            return $this->applyStatus($transaction, $callbackStatus, $payload);
        });
    }

    /**
     * Memproses notifikasi webhook dari Midtrans (server-to-server).
     * Signature wajib valid agar payload tidak bisa dipalsukan.
     *
     * @param array<string, mixed> $payload
     */
    public function processMidtransNotification(array $payload): PaymentTransaction
    {
        return DB::transaction(function () use ($payload) {
            $orderId = (string) ($payload['order_id'] ?? '');

            if ($orderId === '') {
                throw ValidationException::withMessages([
                    'order_id' => 'order_id wajib ada pada notifikasi Midtrans.',
                ]);
            }

            if (!$this->isValidMidtransSignature($payload)) {
                throw ValidationException::withMessages([
                    'signature_key' => 'Signature Midtrans tidak valid.',
                ]);
            }

            $transaction = $this->lockTransaction($orderId);

            if (!$transaction) {
                throw ValidationException::withMessages([
                    'order_id' => 'Invoice tidak ditemukan untuk order_id ini.',
                ]);
            }

            $mappedStatus = $this->mapMidtransStatus($payload);

            // Status masih PENDING (mis. menunggu pembayaran QRIS) — simpan payload saja.
            if ($mappedStatus === null) {
                $transaction->update(['callback_payload' => $payload]);

                return $transaction->fresh(['booking']);
            }

            return $this->applyStatus($transaction, $mappedStatus, $payload);
        });
    }

    /**
     * Memproses callback webhook dari Tripay (server-to-server).
     * Signature (HMAC-SHA256 atas raw body) wajib valid agar tidak bisa dipalsukan.
     */
    public function processTripayCallback(string $rawBody, string $signature): PaymentTransaction
    {
        if (!$this->isValidTripaySignature($rawBody, $signature)) {
            throw ValidationException::withMessages([
                'signature' => 'Signature Tripay tidak valid.',
            ]);
        }

        $payload = json_decode($rawBody, true);

        if (!is_array($payload)) {
            throw ValidationException::withMessages([
                'payload' => 'Payload Tripay tidak valid.',
            ]);
        }

        return $this->applyTripayStatus($payload);
    }

    /**
     * Terapkan payload Tripay (dari webhook atau Transaction Detail API) ke transaksi.
     * Dicocokkan via merchant_ref (= invoice_number internal).
     *
     * @param array<string, mixed> $payload
     */
    public function applyTripayStatus(array $payload): PaymentTransaction
    {
        return DB::transaction(function () use ($payload) {
            $merchantRef = (string) ($payload['merchant_ref'] ?? '');

            if ($merchantRef === '') {
                throw ValidationException::withMessages([
                    'merchant_ref' => 'merchant_ref wajib ada pada notifikasi Tripay.',
                ]);
            }

            $transaction = $this->lockTransaction($merchantRef);

            if (!$transaction) {
                throw ValidationException::withMessages([
                    'merchant_ref' => 'Invoice tidak ditemukan untuk merchant_ref ini.',
                ]);
            }

            $mappedStatus = $this->mapTripayStatus((string) ($payload['status'] ?? ''));

            // Status masih UNPAID (menunggu pembayaran) — simpan payload saja.
            if ($mappedStatus === null) {
                $transaction->update(['callback_payload' => $payload]);

                return $transaction->fresh(['booking']);
            }

            return $this->applyStatus($transaction, $mappedStatus, $payload);
        });
    }

    /**
     * Terapkan perubahan status pada transaksi + booking secara idempotent.
     *
     * @param array<string, mixed> $payload
     */
    private function applyStatus(PaymentTransaction $transaction, string $status, array $payload): PaymentTransaction
    {
        // Callback idempotent: transaksi yang sudah SUCCESS dipertahankan.
        if ($transaction->status === PaymentTransaction::STATUS_SUCCESS) {
            $transaction->update(['callback_payload' => $payload]);

            return $transaction->fresh(['booking']);
        }

        $transaction->status = $status;
        $transaction->callback_payload = $payload;

        if ($status === PaymentTransaction::STATUS_SUCCESS) {
            $transaction->paid_at = now();
        }

        $transaction->save();

        $booking = $transaction->booking()->lockForUpdate()->first();

        if ($booking) {
            $booking->status = $status === PaymentTransaction::STATUS_SUCCESS
                ? Booking::STATUS_CONFIRMED
                : Booking::STATUS_CANCELLED;
            $booking->save();
        }

        $fresh = $transaction->fresh([
            'booking',
            'booking.guest',
            'booking.studio',
            'booking.servicePackage',
        ]);

        // Kirim email konfirmasi hanya saat transisi pertama ke SUCCESS.
        if ($status === PaymentTransaction::STATUS_SUCCESS) {
            $this->sendConfirmationEmail($fresh);
        }

        return $fresh;
    }

    /**
     * Kirim email konfirmasi + invoice PDF ke tamu, setelah transaksi commit.
     * Kegagalan email di-log dan tidak mengganggu alur pembayaran.
     */
    private function sendConfirmationEmail(PaymentTransaction $transaction): void
    {
        $email = $transaction->booking?->guest?->email;

        if (!$email) {
            return;
        }

        DB::afterCommit(function () use ($transaction, $email) {
            try {
                Mail::to($email)->send(new BookingConfirmedMail($transaction));
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim email konfirmasi booking.', [
                    'invoice' => $transaction->invoice_number,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }

    /**
     * Ambil transaksi berdasarkan invoice + kunci baris untuk update.
     */
    private function lockTransaction(string $invoiceNumber): ?PaymentTransaction
    {
        return PaymentTransaction::query()
            ->where('invoice_number', $invoiceNumber)
            ->lockForUpdate()
            ->first();
    }

    /**
     * Verifikasi signature Midtrans: sha512(order_id + status_code + gross_amount + server_key).
     *
     * @param array<string, mixed> $payload
     */
    private function isValidMidtransSignature(array $payload): bool
    {
        $serverKey = (string) config('services.midtrans.server_key');

        if ($serverKey === '') {
            return false;
        }

        $expected = hash('sha512', sprintf(
            '%s%s%s%s',
            (string) ($payload['order_id'] ?? ''),
            (string) ($payload['status_code'] ?? ''),
            (string) ($payload['gross_amount'] ?? ''),
            $serverKey
        ));

        return hash_equals($expected, (string) ($payload['signature_key'] ?? ''));
    }

    /**
     * Petakan transaction_status Midtrans ke status internal aplikasi.
     * Mengembalikan null bila pembayaran masih berlangsung (PENDING).
     *
     * @param array<string, mixed> $payload
     */
    private function mapMidtransStatus(array $payload): ?string
    {
        $status = (string) ($payload['transaction_status'] ?? '');
        $fraud = (string) ($payload['fraud_status'] ?? 'accept');

        return match ($status) {
            'capture' => $fraud === 'deny'
                ? PaymentTransaction::STATUS_FAILED
                : PaymentTransaction::STATUS_SUCCESS,
            'settlement' => PaymentTransaction::STATUS_SUCCESS,
            'deny', 'cancel', 'failure', 'refund', 'partial_refund' => PaymentTransaction::STATUS_FAILED,
            'expire' => PaymentTransaction::STATUS_EXPIRED,
            default => null, // pending / authorize
        };
    }

    /**
     * Verifikasi signature webhook Tripay: HMAC-SHA256(raw_body, private_key).
     */
    private function isValidTripaySignature(string $rawBody, string $signature): bool
    {
        $privateKey = (string) config('services.tripay.private_key');

        if ($privateKey === '' || $signature === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $rawBody, $privateKey);

        return hash_equals($expected, $signature);
    }

    /**
     * Petakan status Tripay ke status internal aplikasi.
     * Mengembalikan null bila pembayaran masih berlangsung (UNPAID).
     */
    private function mapTripayStatus(string $status): ?string
    {
        return match (strtoupper($status)) {
            'PAID' => PaymentTransaction::STATUS_SUCCESS,
            'EXPIRED' => PaymentTransaction::STATUS_EXPIRED,
            'FAILED', 'REFUND' => PaymentTransaction::STATUS_FAILED,
            default => null, // UNPAID
        };
    }
}
