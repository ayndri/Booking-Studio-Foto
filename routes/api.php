<?php

use App\Http\Controllers\Api\MidtransNotificationController;
use App\Http\Controllers\Api\PaymentCallbackController;
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
