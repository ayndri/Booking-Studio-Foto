<?php

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
