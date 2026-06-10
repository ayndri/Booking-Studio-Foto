@extends('layouts.dashboard')

@section('title', 'Transaksi - Admin')

@section('content')
<h1 class="h4 mb-3">Data Transaksi</h1>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['PENDING', 'SUCCESS', 'FAILED', 'EXPIRED'] as $status)
                        <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Invoice</label>
                <input type="text" name="invoice" class="form-control" value="{{ $selectedInvoice }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Invoice</th>
                <th>Booking</th>
                <th>Guest</th>
                <th>Metode</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Waktu Bayar</th>
            </tr>
            </thead>
            <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->booking->booking_code ?? '-' }}</td>
                    <td>{{ $transaction->booking->guest->full_name ?? '-' }}</td>
                    <td>{{ $transaction->payment_method }} ({{ $transaction->payment_type }})</td>
                    <td>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    <td><span class="badge text-bg-info">{{ $transaction->status }}</span></td>
                    <td>{{ optional($transaction->paid_at)->format('d-m-Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">Belum ada transaksi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $transactions->links() }}</div>
@endsection
