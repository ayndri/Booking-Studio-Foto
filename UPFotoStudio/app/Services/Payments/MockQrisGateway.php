<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use Illuminate\Support\Str;

class MockQrisGateway implements PaymentGatewayInterface
{
    /**
     * Simulasi pembuatan QRIS tanpa gateway eksternal.
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function createQrisPayment(array $payload): array
    {
        $reference = 'MOCK-QRIS-' . strtoupper(Str::random(12));

        return [
            'reference' => $reference,
            'qr_string' => sprintf(
                'MOCKQRIS|INVOICE:%s|AMOUNT:%d|REF:%s',
                $payload['invoice_number'],
                (int) $payload['amount'],
                $reference
            ),
            'payment_url' => route('frontend.booking.status', [
                'invoiceNumber' => $payload['invoice_number'],
                'gateway' => 'mock',
            ]),
            'expires_at' => now()->addMinutes(30),
        ];
    }
}
