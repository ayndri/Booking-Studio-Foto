<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Payments\MidtransSnapGateway;
use App\Services\Payments\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentRedirectController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    /**
     * Finish Redirect URL — customer kembali setelah menyelesaikan pembayaran.
     * Sinkronkan status dari Midtrans lalu tampilkan halaman status booking.
     */
    public function finish(Request $request): RedirectResponse
    {
        $invoiceNumber = $this->syncStatus($request->query('order_id'));

        if ($invoiceNumber === null) {
            return redirect()->route('frontend.home')
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        return redirect()->route('frontend.booking.status', $invoiceNumber);
    }

    /**
     * Unfinish Redirect URL — customer menekan "Back to Order Website".
     */
    public function unfinish(Request $request): RedirectResponse
    {
        $invoiceNumber = $this->syncStatus($request->query('order_id'));

        if ($invoiceNumber === null) {
            return redirect()->route('frontend.home');
        }

        return redirect()->route('frontend.booking.status', $invoiceNumber)
            ->with('warning', 'Pembayaran belum diselesaikan. Silakan lanjutkan kembali.');
    }

    /**
     * Error Redirect URL — terjadi kegagalan pada proses pembayaran.
     */
    public function error(Request $request): RedirectResponse
    {
        $invoiceNumber = $this->syncStatus($request->query('order_id'));

        if ($invoiceNumber === null) {
            return redirect()->route('frontend.home')
                ->with('error', 'Pembayaran gagal diproses.');
        }

        return redirect()->route('frontend.booking.status', $invoiceNumber)
            ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
    }

    /**
     * Tarik status terbaru dari Midtrans Status API dan terapkan ke transaksi.
     * Mengembalikan invoice number (= order_id) bila valid.
     */
    private function syncStatus(?string $orderId): ?string
    {
        $orderId = trim((string) $orderId);

        if ($orderId === '') {
            return null;
        }

        // Hanya relevan bila gateway aktif adalah Midtrans.
        if (config('services.payment_gateway') === 'midtrans') {
            try {
                $statusPayload = app(MidtransSnapGateway::class)->getStatus($orderId);

                if (!empty($statusPayload['order_id'])) {
                    $this->paymentService->processMidtransNotification($statusPayload);
                }
            } catch (ValidationException) {
                // Signature/invoice tidak valid — abaikan, halaman status tetap ditampilkan.
            } catch (\Throwable) {
                // Jaringan/Midtrans down — biarkan webhook yang menyusul memperbarui status.
            }
        }

        return $orderId;
    }
}
