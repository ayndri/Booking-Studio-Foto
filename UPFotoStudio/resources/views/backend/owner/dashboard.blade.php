@extends('layouts.dashboard')

@section('title', 'Dashboard Owner')

@section('content')
<h1 class="h4 mb-4">Dashboard Owner</h1>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Harian</h6>
                <div class="h5">Rp{{ number_format($dailySummary['total_success_amount'], 0, ',', '.') }}</div>
                <small>{{ $dailySummary['success_transactions'] }} transaksi sukses</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Mingguan</h6>
                <div class="h5">Rp{{ number_format($weeklySummary['total_success_amount'], 0, ',', '.') }}</div>
                <small>{{ $weeklySummary['success_transactions'] }} transaksi sukses</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Bulanan</h6>
                <div class="h5">Rp{{ number_format($monthlySummary['total_success_amount'], 0, ',', '.') }}</div>
                <small>{{ $monthlySummary['success_transactions'] }} transaksi sukses</small>
            </div>
        </div>
    </div>
</div>
@endsection
