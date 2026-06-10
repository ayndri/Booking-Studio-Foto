<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MidtransNotificationController extends Controller
{
    /**
     * Endpoint webhook (HTTP Notification) dari Midtrans.
     * Dipasang di dashboard Midtrans: Payment Notification URL.
     */
    public function handle(Request $request, PaymentService $paymentService): JsonResponse
    {
        try {
            $transaction = $paymentService->processMidtransNotification($request->all());

            return response()->json([
                'message' => 'Notifikasi diproses.',
                'invoice_number' => $transaction->invoice_number,
                'transaction_status' => $transaction->status,
                'booking_status' => $transaction->booking?->status,
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Notifikasi Midtrans ditolak.',
                'errors' => $exception->errors(),
            ], 422);
        }
    }
}
