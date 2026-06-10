<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Admin\UpdateBookingStatusRequest;
use App\Models\Booking;
use App\Models\PaymentTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $date   = $request->query('booking_date');

        $bookings = Booking::query()
            ->with(['guest', 'studio', 'servicePackage', 'paymentTransaction'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($date,   fn ($q) => $q->whereDate('booking_date', $date))
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->paginate(12)
            ->withQueryString();

        return view('backend.admin.bookings.index', [
            'bookings'       => $bookings,
            'selectedStatus' => $status,
            'selectedDate'   => $date,
        ]);
    }

    /**
     * Update status booking + otomatis sinkron payment_transaction.
     */
    public function updateStatus(UpdateBookingStatusRequest $request, Booking $booking): RedirectResponse
    {
        $newStatus = $request->validated('status');

        $booking->update(['status' => $newStatus]);

        // Sinkronisasi otomatis ke payment_transaction
        if ($booking->paymentTransaction) {
            $txStatus = match ($newStatus) {
                Booking::STATUS_CONFIRMED  => PaymentTransaction::STATUS_SUCCESS,
                Booking::STATUS_CANCELLED  => PaymentTransaction::STATUS_FAILED,
                Booking::STATUS_COMPLETED  => PaymentTransaction::STATUS_SUCCESS,
                default                    => null,
            };

            if ($txStatus) {
                $booking->paymentTransaction->update(['status' => $txStatus]);
            }
        }

        return back()->with('success', 'Status booking berhasil diperbarui dan transaksi tersinkronisasi.');
    }
}
