<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway
    |--------------------------------------------------------------------------
    | PAYMENT_GATEWAY=mock     -> pakai MockQrisGateway (simulasi, tanpa internet)
    | PAYMENT_GATEWAY=midtrans -> pakai Midtrans Snap (sandbox/production)
    | PAYMENT_GATEWAY=tripay   -> pakai Tripay (QRIS, sandbox/production)
    */
    'payment_gateway' => env('PAYMENT_GATEWAY', 'mock'),

    'midtrans' => [
        'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        // false = sandbox (development), true = production.
        'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
        // Masa berlaku pembayaran sebelum kadaluarsa (menit).
        'expiry_minutes' => (int) env('MIDTRANS_EXPIRY_MINUTES', 30),
    ],

    'tripay' => [
        'api_key' => env('TRIPAY_API_KEY'),
        'private_key' => env('TRIPAY_PRIVATE_KEY'),
        'merchant_code' => env('TRIPAY_MERCHANT_CODE'),
        // false = sandbox (development), true = production.
        'is_production' => (bool) env('TRIPAY_IS_PRODUCTION', false),
        // Kode channel pembayaran Tripay. QRIS default; ganti bila perlu (mis. QRISC).
        'qris_method' => env('TRIPAY_QRIS_METHOD', 'QRIS'),
        // Masa berlaku pembayaran sebelum kadaluarsa (menit).
        'expiry_minutes' => (int) env('TRIPAY_EXPIRY_MINUTES', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA v3 (Invisible)
    |--------------------------------------------------------------------------
    | Proteksi anti-bot untuk form booking & kontak.
    | Bila secret_key kosong, verifikasi otomatis dilewati (cocok untuk lokal).
    */
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        // Skor minimum v3 (0.0 bot – 1.0 manusia) agar dianggap valid.
        'min_score' => (float) env('RECAPTCHA_MIN_SCORE', 0.5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Kebijakan Booking
    |--------------------------------------------------------------------------
    */
    'booking' => [
        // Maksimal booking PENDING_PAYMENT (belum dibayar) yang aktif per email.
        'max_pending_per_email' => (int) env('BOOKING_MAX_PENDING_PER_EMAIL', 3),
    ],

];
