@extends('layouts.dashboard')

@section('title', 'Laporan Owner')

@section('content')
<h1 class="h4 mb-3">Laporan Transaksi (Owner)</h1>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="get" action="{{ route('owner.reports.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Periode</label>
                <select name="period" class="form-select">
                    @foreach(['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan', 'custom' => 'Custom'] as $value => $label)
                        <option value="{{ $value }}" @selected($filter['period'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Dari</label>
                <input type="date" name="from" class="form-control" value="{{ $filter['from'] }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai</label>
                <input type="date" name="to" class="form-control" value="{{ $filter['to'] }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Tampilkan</button>
                <a class="btn btn-outline-danger" href="{{ route('owner.reports.export-pdf', ['period' => $filter['period'], 'from' => $filter['from'], 'to' => $filter['to']]) }}">PDF</a>
                <a class="btn btn-outline-success" href="{{ route('owner.reports.export-excel', ['period' => $filter['period'], 'from' => $filter['from'], 'to' => $filter['to']]) }}">Excel</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total Transaksi</div><div class="h5">{{ $summary['total_transactions'] }}</div></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Sukses</div><div class="h5">{{ $summary['success_transactions'] }}</div></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total Sukses</div><div class="h5">Rp{{ number_format($summary['total_success_amount'], 0, ',', '.') }}</div></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total Nominal</div><div class="h5">Rp{{ number_format($summary['total_amount'], 0, ',', '.') }}</div></div></div></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
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
                    <td>{{ $transaction->booking->guest->full_name ?? '-' }}</td>
                    <td>{{ $transaction->booking->studio->name ?? '-' }}</td>
                    <td>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    <td>{{ $transaction->status }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
