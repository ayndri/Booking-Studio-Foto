@extends('layouts.frontend')

@section('title', 'Status Pembayaran - UPFotoStudio')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if(request('gateway') === 'mock')
                    <div class="alert alert-primary small">
                        Anda sedang berada di halaman payment gateway QRIS (mode simulasi).
                    </div>
                @endif

                <h1 class="h4">Status Pembayaran</h1>
                <p class="text-muted mb-4">Invoice: <strong>{{ $transaction->invoice_number }}</strong></p>

                <table class="table table-sm">
                    <tr><th width="35%">Booking Code</th><td>{{ $transaction->booking->booking_code }}</td></tr>
                    <tr><th>Guest</th><td>{{ $transaction->booking->guest->full_name }}</td></tr>
                    <tr><th>Studio</th><td>{{ $transaction->booking->studio->name }}</td></tr>
                    <tr><th>Paket</th><td>{{ $transaction->booking->servicePackage->name }}</td></tr>
                    <tr><th>Tanggal</th><td>{{ $transaction->booking->booking_date->format('d-m-Y') }}</td></tr>
                    <tr><th>Waktu</th><td>{{ $transaction->booking->start_time }} - {{ $transaction->booking->end_time }}</td></tr>
                    <tr><th>Payment Type</th><td>{{ $transaction->payment_type }}</td></tr>
                    <tr><th>Nominal Bayar</th><td>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td></tr>
                    <tr><th>Status Transaksi</th><td><span class="badge text-bg-info">{{ $transaction->status }}</span></td></tr>
                    <tr><th>Status Booking</th><td><span class="badge text-bg-secondary">{{ $transaction->booking->status }}</span></td></tr>
                </table>

                <a href="{{ route('frontend.booking.invoice', $transaction->invoice_number) }}" class="btn btn-outline-primary btn-sm">
                    Download Invoice PDF
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5>QRIS Payload (Mock)</h5>
                <div class="bg-light border rounded p-3 small" style="word-break: break-all;">
                    {{ $transaction->qr_payload ?? '-' }}
                </div>
                @if($transaction->expires_at)
                    <small class="text-muted d-block mt-2">Kadaluarsa: {{ $transaction->expires_at->format('d-m-Y H:i') }}</small>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6>Simulasi Callback Cepat</h6>
                <code class="small d-block">POST /api/payments/qris/callback</code>
                <small class="text-muted">Gunakan invoice di atas dengan status <strong>SUCCESS</strong>, <strong>FAILED</strong>, atau <strong>EXPIRED</strong>.</small>
            </div>
        </div>
    </div>
</div>
@endsection
