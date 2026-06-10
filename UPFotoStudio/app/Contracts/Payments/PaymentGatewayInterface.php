<?php

namespace App\Contracts\Payments;

interface PaymentGatewayInterface
{
    /**
     * Membuat permintaan pembayaran QRIS ke provider.
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function createQrisPayment(array $payload): array;
}
