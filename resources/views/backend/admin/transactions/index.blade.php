@extends('layouts.dashboard')
@section('title', 'Transaksi - Admin')
@section('content')

<div class="page-header">
    <h1 class="page-title">💳 Data Transaksi</h1>
</div>

<div class="filter-bar">
    <form method="get" style="display:contents">
        <div class="filter-field">
            <label>Status</label>
            <select name="status" class="d-select" style="width:auto;min-width:150px">
                <option value="">Semua</option>
                @foreach(['PENDING','SUCCESS','FAILED','EXPIRED'] as $s)
                    <option value="{{ $s }}" @selected($selectedStatus===$s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-field" style="flex:1;min-width:200px">
            <label>No. Invoice</label>
            <input type="text" name="invoice" class="d-input" value="{{ $selectedInvoice }}" placeholder="Cari invoice...">
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn-g">Filter</button>
            <a href="{{ route('admin.transactions.index') }}" class="btn-back">Reset</a>
        </div>
    </form>
</div>

<div class="d-card">
    <div class="table-responsive">
        <table class="d-table">
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
            @forelse($transactions as $tx)
                @php
                    $txCls = match(strtolower($tx->status)){
                        'success' => 'dbadge-success',
                        'failed'  => 'dbadge-failed',
                        'expired' => 'dbadge-expired',
                        default   => 'dbadge-pending',
                    };
                @endphp
                <tr>
                    <td><code style="font-size:.75rem;color:#888;background:#f4f3f0;padding:2px 6px;border-radius:4px">{{ $tx->invoice_number }}</code></td>
                    <td><code style="font-size:.75rem;color:#888;background:#f4f3f0;padding:2px 6px;border-radius:4px">{{ $tx->booking->booking_code ?? '-' }}</code></td>
                    <td style="font-weight:600">{{ $tx->booking->guest->full_name ?? '-' }}</td>
                    <td>
                        <span style="font-size:.8rem;background:#eef7f2;color:#2f5443;padding:3px 8px;border-radius:999px;font-weight:600">{{ $tx->payment_method }}</span>
                        <span style="font-size:.76rem;color:#888;margin-left:4px">{{ $tx->payment_type }}</span>
                    </td>
                    <td style="font-family:'Playfair Display',serif;font-weight:700;color:#2f5443">Rp{{ number_format($tx->amount,0,',','.') }}</td>
                    <td><span class="dbadge {{ $txCls }}">{{ $tx->status }}</span></td>
                    <td style="color:#888;font-size:.82rem;white-space:nowrap">{{ optional($tx->paid_at)->format('d M Y, H:i') ?? '–' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#bbb;padding:32px">Belum ada transaksi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $transactions->links() }}</div>
@endsection
