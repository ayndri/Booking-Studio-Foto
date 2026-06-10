@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
@php
    $masterDataStats = [
        ['label' => 'Total Studio', 'value' => $totalStudios, 'tone' => 'primary', 'note' => 'Jumlah studio aktif di sistem'],
        ['label' => 'Paket Layanan', 'value' => $totalPackages, 'tone' => 'info', 'note' => 'Total paket yang dapat dipesan'],
    ];

    $bookingStats = [
        ['label' => 'Total Booking', 'value' => $totalBookings, 'tone' => 'primary', 'note' => 'Semua data booking tercatat'],
        ['label' => 'Booking Pending', 'value' => $pendingBookings, 'tone' => 'warning', 'note' => 'Menunggu pembayaran/konfirmasi'],
        ['label' => 'Booking Confirmed', 'value' => $confirmedBookings, 'tone' => 'success', 'note' => 'Sudah terkonfirmasi'],
    ];

    $financeStats = [
        ['label' => 'Transaksi Hari Ini', 'value' => $todayTransactions, 'tone' => 'info', 'note' => 'Jumlah transaksi pada tanggal ini'],
        ['label' => 'Revenue Hari Ini', 'value' => 'Rp' . number_format($todayRevenue, 0, ',', '.'), 'tone' => 'success', 'note' => 'Akumulasi transaksi SUCCESS hari ini'],
    ];

    $contactStats = [
        ['label' => 'Pesan Belum Dibaca', 'value' => $unreadContactMessages, 'tone' => 'danger', 'note' => 'Perlu ditindaklanjuti'],
        ['label' => 'Pesan Masuk Hari Ini', 'value' => $todayContactMessages, 'tone' => 'secondary', 'note' => 'Total pesan yang dibuat hari ini'],
    ];
@endphp

<style>
    .dashboard-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
    }

    .dashboard-section + .dashboard-section {
        margin-top: 1rem;
    }

    .dashboard-section-title {
        font-size: 0.76rem;
        letter-spacing: 0.08em;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.75rem;
    }

    .dashboard-metric {
        height: 100%;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-left-width: 4px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    }

    .dashboard-metric .card-body {
        padding: 0.95rem 1rem;
    }

    .dashboard-metric-label {
        font-size: 0.9rem;
        color: #334155;
        margin-bottom: 0.35rem;
    }

    .dashboard-metric-value {
        font-size: 1.9rem;
        line-height: 1.1;
        font-weight: 700;
        color: #0f172a;
    }

    .dashboard-metric-note {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 0.45rem;
    }

    .dashboard-metric-primary { border-left-color: #3b82f6; }
    .dashboard-metric-success { border-left-color: #16a34a; }
    .dashboard-metric-warning { border-left-color: #f59e0b; }
    .dashboard-metric-danger { border-left-color: #ef4444; }
    .dashboard-metric-info { border-left-color: #06b6d4; }
    .dashboard-metric-secondary { border-left-color: #64748b; }

    .dashboard-table thead th {
        background: #f8fafc;
        color: #0f172a;
        font-weight: 600;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    .dashboard-table tbody td {
        vertical-align: middle;
        color: #1e293b;
        font-size: 0.92rem;
    }

    .dashboard-empty {
        padding: 1.25rem;
        font-size: 0.95rem;
        color: #64748b;
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h4 mb-1">Dashboard Admin</h1>
        <p class="text-muted mb-0 small">Ringkasan operasional, keuangan, dan pesan kontak.</p>
    </div>
    <span class="badge rounded-pill text-bg-light border text-secondary px-3 py-2">
        Data hari ini: {{ now()->format('d M Y') }}
    </span>
</div>

<section class="dashboard-panel dashboard-section">
    <div class="dashboard-section-title">Laporan Master Data</div>
    <div class="row g-3">
        @foreach($masterDataStats as $stat)
            <div class="col-12 col-md-6">
                <div class="card dashboard-metric dashboard-metric-{{ $stat['tone'] }}">
                    <div class="card-body">
                        <div class="dashboard-metric-label">{{ $stat['label'] }}</div>
                        <div class="dashboard-metric-value">{{ $stat['value'] }}</div>
                        <div class="dashboard-metric-note">{{ $stat['note'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="dashboard-panel dashboard-section">
    <div class="dashboard-section-title">Laporan Booking</div>
    <div class="row g-3">
        @foreach($bookingStats as $stat)
            <div class="col-12 col-md-4">
                <div class="card dashboard-metric dashboard-metric-{{ $stat['tone'] }}">
                    <div class="card-body">
                        <div class="dashboard-metric-label">{{ $stat['label'] }}</div>
                        <div class="dashboard-metric-value">{{ $stat['value'] }}</div>
                        <div class="dashboard-metric-note">{{ $stat['note'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<div class="row g-3 dashboard-section">
    <div class="col-12 col-lg-6">
        <section class="dashboard-panel h-100">
            <div class="dashboard-section-title">Laporan Keuangan Harian</div>
            <div class="row g-3">
                @foreach($financeStats as $stat)
                    <div class="col-12">
                        <div class="card dashboard-metric dashboard-metric-{{ $stat['tone'] }}">
                            <div class="card-body">
                                <div class="dashboard-metric-label">{{ $stat['label'] }}</div>
                                <div class="dashboard-metric-value">{{ $stat['value'] }}</div>
                                <div class="dashboard-metric-note">{{ $stat['note'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
    <div class="col-12 col-lg-6">
        <section class="dashboard-panel h-100">
            <div class="dashboard-section-title">Laporan Pesan Kontak</div>
            <div class="row g-3">
                @foreach($contactStats as $stat)
                    <div class="col-12">
                        <div class="card dashboard-metric dashboard-metric-{{ $stat['tone'] }}">
                            <div class="card-body">
                                <div class="dashboard-metric-label">{{ $stat['label'] }}</div>
                                <div class="dashboard-metric-value">{{ $stat['value'] }}</div>
                                <div class="dashboard-metric-note">{{ $stat['note'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <div>
            <strong>Pesan Kontak Terbaru</strong>
            <div class="text-muted small">Daftar 5 pesan terakhir dari formulir kontak.</div>
        </div>
        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0 dashboard-table">
            <thead>
            <tr>
                <th>Waktu</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Subjek</th>
                <th>Pesan</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($recentContactMessages as $message)
                <tr>
                    <td>{{ $message->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $message->full_name }}</td>
                    <td>{{ $message->email }}</td>
                    <td>{{ $message->subject }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($message->message, 80) }}</td>
                    <td>
                        @if($message->is_read)
                            <span class="badge text-bg-secondary">READ</span>
                        @else
                            <span class="badge text-bg-warning">UNREAD</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center dashboard-empty">Belum ada pesan masuk.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
