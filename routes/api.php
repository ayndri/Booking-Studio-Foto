<?php

use App\Http\Controllers\Api\MidtransNotificationController;
use App\Http\Controllers\Api\PaymentCallbackController;
use App\Http\Controllers\Api\TripayCallbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Endpoint callback pembayaran dari payment gateway.
*/
Route::post('/payments/qris/callback', [PaymentCallbackController::class, 'handle'])
    ->name('api.payments.qris.callback');

// Webhook (HTTP Notification) dari Midtrans — dipasang di dashboard Midtrans.
Route::post('/payments/midtrans/notification', [MidtransNotificationController::class, 'handle'])
    ->name('api.payments.midtrans.notification');

// Webhook (Callback URL) dari Tripay — dipasang di dashboard Tripay.
Route::post('/payments/tripay/callback', [TripayCallbackController::class, 'handle'])
    ->name('api.payments.tripay.callback');
