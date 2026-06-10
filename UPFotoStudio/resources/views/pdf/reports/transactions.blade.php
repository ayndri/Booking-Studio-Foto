<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h2 { margin: 0 0 5px; }
        .meta { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f1f1f1; }
    </style>
</head>
<body>
<h2>{{ $title }}</h2>
<div class="meta">
    Periode: {{ $summary['start_date']->format('d-m-Y') }} s/d {{ $summary['end_date']->format('d-m-Y') }}<br>
    Total transaksi: {{ $summary['total_transactions'] }} | Sukses: {{ $summary['success_transactions'] }}<br>
    Total sukses: Rp{{ number_format($summary['total_success_amount'], 0, ',', '.') }}
</div>

<table>
    <thead>
    <tr>
        <th>Invoice</th>
        <th>Tanggal</th>
        <th>Booking</th>
        <th>Guest</th>
        <th>Studio</th>
        <th>Nominal</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($transactions as $transaction)
        <tr>
            <td>{{ $transaction->invoice_number }}</td>
            <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
            <td>{{ $transaction->booking->booking_code ?? '-' }}</td>
            <td>{{ $transaction->booking->guest->full_name ?? '-' }}</td>
            <td>{{ $transaction->booking->studio->name ?? '-' }}</td>
            <td>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
            <td>{{ $transaction->status }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align: center;">Tidak ada data transaksi</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
