<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TripayCallbackController extends Controller
{
    /**
     * Endpoint webhook (Callback URL) dari Tripay.
     * Dipasang di dashboard Tripay: Merchant > Callback URL.
     *
     * Signature dikirim Tripay pada header X-Callback-Signature
     * (HMAC-SHA256 atas raw JSON body, kunci = Private Key).
     */
    public function handle(Request $request, PaymentService $paymentService): JsonResponse
    {
        $rawBody = $request->getContent();
        $signature = (string) $request->header('X-Callback-Signature', '');

        try {
            $transaction = $paymentService->processTripayCallback($rawBody, $signature);

            // Tripay mengharapkan respons {"success": true} dengan HTTP 200.
            return response()->json([
                'success' => true,
                'invoice_number' => $transaction->invoice_number,
                'transaction_status' => $transaction->status,
                'booking_status' => $transaction->booking?->status,
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->validator->errors()->first(),
            ], 422);
        }
    }
}
