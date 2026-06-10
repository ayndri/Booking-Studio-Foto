<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Admin\UpdateBookingStatusRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Daftar booking untuk monitoring admin.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $date = $request->query('booking_date');

        $bookings = Booking::query()
            ->with(['guest', 'studio', 'servicePackage', 'paymentTransaction'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($date, fn ($query) => $query->whereDate('booking_date', $date))
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->paginate(12)
            ->withQueryString();

        return view('backend.admin.bookings.index', [
            'bookings' => $bookings,
            'selectedStatus' => $status,
            'selectedDate' => $date,
        ]);
    }

    /**
     * Update status booking oleh admin.
     */
    public function updateStatus(UpdateBookingStatusRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update([
            'status' => $request->validated('status'),
        ]);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }
}
