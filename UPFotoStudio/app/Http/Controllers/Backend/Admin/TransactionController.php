<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Daftar transaksi pembayaran.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $invoice = $request->query('invoice');

        $transactions = PaymentTransaction::query()
            ->with(['booking.guest', 'booking.studio'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($invoice, fn ($query) => $query->where('invoice_number', 'like', '%' . $invoice . '%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('backend.admin.transactions.index', [
            'transactions' => $transactions,
            'selectedStatus' => $status,
            'selectedInvoice' => $invoice,
        ]);
    }
}
