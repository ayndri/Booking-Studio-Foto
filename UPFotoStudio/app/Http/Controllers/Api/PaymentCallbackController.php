<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentCallbackController extends Controller
{
    /**
     * Endpoint callback notifikasi pembayaran QRIS.
     */
    public function handle(Request $request, PaymentService $paymentService): JsonResponse
    {
        try {
            $request->validate([
                'invoice_number' => ['required', 'string'],
                'status' => ['required', 'string'],
            ]);

            $transaction = $paymentService->processCallback($request->all());

            return response()->json([
                'message' => 'Callback diproses.',
                'invoice_number' => $transaction->invoice_number,
                'transaction_status' => $transaction->status,
                'booking_status' => $transaction->booking?->status,
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Validasi callback gagal.',
                'errors' => $exception->errors(),
            ], 422);
        }
    }
}
