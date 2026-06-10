<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $transaction->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        .header { margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td, th { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f5f5; text-align: left; width: 35%; }
    </style>
</head>
<body>
<div class="header">
    <div class="title">INVOICE BOOKING</div>
    <div>UPFotoStudio</div>
    <div>Invoice: {{ $transaction->invoice_number }}</div>
    <div>Tanggal: {{ $transaction->created_at->format('d-m-Y H:i') }}</div>
</div>

<table>
    <tr><th>Booking Code</th><td>{{ $booking->booking_code }}</td></tr>
    <tr><th>Nama Guest</th><td>{{ $booking->guest->full_name }}</td></tr>
    <tr><th>Email Guest</th><td>{{ $booking->guest->email }}</td></tr>
    <tr><th>Studio</th><td>{{ $booking->studio->name }}</td></tr>
    <tr><th>Paket</th><td>{{ $booking->servicePackage->name }}</td></tr>
    <tr><th>Tanggal Booking</th><td>{{ $booking->booking_date->format('d-m-Y') }}</td></tr>
    <tr><th>Jam</th><td>{{ $booking->start_time }} - {{ $booking->end_time }}</td></tr>
    <tr><th>Total Biaya</th><td>Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</td></tr>
    <tr><th>Jenis Pembayaran</th><td>{{ $transaction->payment_type }}</td></tr>
    <tr><th>Nominal Dibayar</th><td>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td></tr>
    <tr><th>Status Transaksi</th><td>{{ $transaction->status }}</td></tr>
    <tr><th>Status Booking</th><td>{{ $booking->status }}</td></tr>
</table>

<p style="margin-top: 25px; font-size: 11px;">Dokumen ini dibuat otomatis oleh sistem booking UPFotoStudio.</p>
</body>
</html>
