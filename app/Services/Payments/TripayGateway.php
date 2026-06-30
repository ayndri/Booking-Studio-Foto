<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Integrasi Tripay (Closed Payment) khusus channel QRIS.
 *
 * Memakai HTTP client bawaan Laravel tanpa SDK tambahan. Customer diarahkan ke
 * halaman pembayaran Tripay (checkout_url) yang menampilkan QR QRIS + countdown.
 *
 * Dokumentasi: https://tripay.co.id/developer
 */
class TripayGateway implements PaymentGatewayInterface
{
    /**
     * @param string $apiKey        API Key Tripay (sandbox/production).
     * @param string $privateKey    Private Key untuk signature HMAC-SHA256.
     * @param string $merchantCode  Kode merchant Tripay (mis. T1234).
     * @param bool   $isProduction  false = sandbox, true = production.
     * @param int    $expiryMinutes Masa berlaku pembayaran sebelum kadaluarsa.
     * @param string $qrisMethod    Kode channel QRIS Tripay (default "QRIS").
     */
    public function __construct(
        private readonly string $apiKey,
        private readonly string $privateKey,
        private readonly string $merchantCode,
        private readonly bool $isProduction = false,
        private readonly int $expiryMinutes = 30,
        private readonly string $qrisMethod = 'QRIS',
    ) {
    }

    /**
     * Buat transaksi QRIS Tripay dan kembalikan URL halaman pembayaran.
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function createQrisPayment(array $payload): array
    {
        $invoiceNumber = (string) $payload['invoice_number'];
        $amount = (int) $payload['amount'];

        // Signature pembuatan transaksi: HMAC-SHA256(merchant_code + merchant_ref + amount).
        $signature = hash_hmac(
            'sha256',
            $this->merchantCode . $invoiceNumber . $amount,
            $this->privateKey
        );

        $body = [
            'method' => $this->qrisMethod,
            'merchant_ref' => $invoiceNumber,
            'amount' => $amount,
            'customer_name' => (string) ($payload['customer_name'] ?? 'Tamu'),
            'customer_email' => (string) ($payload['customer_email'] ?? ''),
            'customer_phone' => (string) ($payload['customer_phone'] ?? ''),
            'order_items' => [[
                'sku' => (string) ($payload['booking_code'] ?? $invoiceNumber),
                'name' => 'Booking UPFotoStudio',
                'price' => $amount,
                'quantity' => 1,
            ]],
            'return_url' => route('payment.tripay.finish'),
            'expired_time' => now()->addMinutes($this->expiryMinutes)->getTimestamp(),
            'signature' => $signature,
        ];

        $response = Http::withToken($this->apiKey)
            ->acceptJson()
            ->asJson()
            ->post($this->baseUrl() . '/transaction/create', $body);

        if ($response->failed() || $response->json('success') !== true) {
            Log::error('Tripay gagal membuat transaksi.', [
                'invoice' => $invoiceNumber,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Gagal membuat transaksi Tripay: ' . $response->body());
        }

        $data = (array) $response->json('data');

        return [
            'reference' => $data['reference'] ?? null,
            // QR ditampilkan di halaman checkout Tripay; payload mentah tidak disimpan.
            'qr_string' => null,
            'payment_url' => $data['checkout_url'] ?? null,
            'expires_at' => isset($data['expired_time'])
                ? Carbon::createFromTimestamp((int) $data['expired_time'])
                : now()->addMinutes($this->expiryMinutes),
        ];
    }

    /**
     * Ambil detail/status transaksi terbaru dari Tripay (Transaction Detail API).
     * Dipakai saat customer kembali dari halaman pembayaran agar status sinkron
     * meski webhook belum sampai.
     *
     * @return array<string, mixed>
     */
    public function getStatus(string $reference): array
    {
        $response = Http::withToken($this->apiKey)
            ->acceptJson()
            ->get($this->baseUrl() . '/transaction/detail', [
                'reference' => $reference,
            ]);

        if ($response->failed() || $response->json('success') !== true) {
            Log::warning('Tripay Transaction Detail API gagal.', [
                'reference' => $reference,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        }

        return (array) $response->json('data');
    }

    private function baseUrl(): string
    {
        return $this->isProduction
            ? 'https://tripay.co.id/api'
            : 'https://tripay.co.id/api-sandbox';
    }
}
