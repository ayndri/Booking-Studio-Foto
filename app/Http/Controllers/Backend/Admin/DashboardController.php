<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\PaymentTransaction;
use App\Models\ServicePackage;
use App\Models\Studio;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Ringkasan dashboard admin.
     */
    public function index(): View
    {
        $hasContactMessagesTable = Schema::hasTable('contact_messages');

        return view('backend.admin.dashboard', [
            'totalStudios' => Studio::count(),
            'totalPackages' => ServicePackage::count(),
            'totalBookings' => Booking::count(),
            'pendingBookings' => Booking::where('status', Booking::STATUS_PENDING_PAYMENT)->count(),
            'confirmedBookings' => Booking::where('status', Booking::STATUS_CONFIRMED)->count(),
            'todayTransactions' => PaymentTransaction::whereDate('created_at', now()->toDateString())->count(),
            'todayRevenue' => PaymentTransaction::whereDate('created_at', now()->toDateString())
                ->where('status', PaymentTransaction::STATUS_SUCCESS)
                ->sum('amount'),
            'unreadContactMessages' => $hasContactMessagesTable
                ? ContactMessage::where('is_read', false)->count()
                : 0,
            'todayContactMessages' => $hasContactMessagesTable
                ? ContactMessage::whereDate('created_at', now()->toDateString())->count()
                : 0,
            'recentContactMessages' => $hasContactMessagesTable
                ? ContactMessage::query()->latest()->limit(5)->get()
                : collect(),
        ]);
    }
}
