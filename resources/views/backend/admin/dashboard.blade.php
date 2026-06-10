@extends('layouts.dashboard')
@section('title', 'Dashboard Admin')

@push('styles')
<style>
.stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 20px; }
.stat-grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin-bottom: 20px; }
.stat-grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 16px; margin-bottom: 24px; }

.stat-card {
    background: #fff; border: 1px solid rgba(0,0,0,.07);
    border-radius: 14px; padding: 18px 20px;
    box-shadow: 0 1px 8px rgba(0,0,0,.04);
    border-left: 3px solid var(--accent, #2f5443);
    position: relative; overflow: hidden;
}
.stat-card::after {
    content: attr(data-icon);
    position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
    font-size: 2rem; opacity: .12;
    pointer-events: none;
}
.stat-label { font-size: .72rem; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px; }
.stat-val   { font-family: 'Playfair Display', serif; font-size: clamp(1.6rem,2.5vw,2.2rem); font-weight: 700; color: #111; line-height: 1; }

@media(max-width:1199.98px){ .stat-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:767.98px){ .stat-grid,.stat-grid-3,.stat-grid-2 { grid-template-columns: 1fr 1fr; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard Admin</h1>
    <small class="text-muted">{{ now()->translatedFormat('l, j F Y') }}</small>
</div>

{{-- Row 1: 4 stats --}}
<div class="stat-grid">
    <div class="stat-card" style="--accent:#2f5443" data-icon="🏢">
        <div class="stat-label">Studio Aktif</div>
        <div class="stat-val">{{ $totalStudios }}</div>
    </div>
    <div class="stat-card" style="--accent:#0d9488" data-icon="📦">
        <div class="stat-label">Paket Layanan</div>
        <div class="stat-val">{{ $totalPackages }}</div>
    </div>
    <div class="stat-card" style="--accent:#2563eb" data-icon="📅">
        <div class="stat-label">Total Booking</div>
        <div class="stat-val">{{ $totalBookings }}</div>
    </div>
    <div class="stat-card" style="--accent:#f59e0b" data-icon="⏳">
        <div class="stat-label">Booking Pending</div>
        <div class="stat-val">{{ $pendingBookings }}</div>
    </div>
</div>

{{-- Row 2: 3 stats --}}
<div class="stat-grid-3">
    <div class="stat-card" style="--accent:#16a34a" data-icon="✅">
        <div class="stat-label">Booking Confirmed</div>
        <div class="stat-val">{{ $confirmedBookings }}</div>
    </div>
    <div class="stat-card" style="--accent:#7c3aed" data-icon="💳">
        <div class="stat-label">Transaksi Hari Ini</div>
        <div class="stat-val">{{ $todayTransactions }}</div>
    </div>
    <div class="stat-card" style="--accent:#166534" data-icon="💰">
        <div class="stat-label">Revenue Hari Ini</div>
        <div class="stat-val" style="font-size:clamp(1.1rem,1.8vw,1.5rem)">Rp{{ number_format($todayRevenue,0,',','.') }}</div>
    </div>
</div>

{{-- Row 3: 2 stats --}}
<div class="stat-grid-2">
    <div class="stat-card" style="--accent:#dc2626" data-icon="✉️">
        <div class="stat-label">Pesan Belum Dibaca</div>
        <div class="stat-val">{{ $unreadContactMessages }}</div>
    </div>
    <div class="stat-card" style="--accent:#d97706" data-icon="📬">
        <div class="stat-label">Pesan Masuk Hari Ini</div>
        <div class="stat-val">{{ $todayContactMessages }}</div>
    </div>
</div>

{{-- Recent messages --}}
<div class="d-card">
    <div style="padding:16px 20px;border-bottom:1px solid rgba(0,0,0,.06);display:flex;justify-content:space-between;align-items:center;">
        <h2 style="font-family:'Poppins',sans-serif;font-size:.95rem;font-weight:700;margin:0;">✉️ Pesan Kontak Terbaru</h2>
        <a href="{{ route('admin.contact-messages.index') }}" class="btn-edit" style="font-size:.76rem;">Lihat Semua →</a>
    </div>
    <div class="table-responsive">
        <table class="d-table">
            <thead>
                <tr>
                    <th>Waktu</th><th>Nama</th><th>Email</th><th>Subjek</th><th>Pesan</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($recentContactMessages as $msg)
                <tr>
                    <td style="white-space:nowrap;color:#888">{{ $msg->created_at->format('d M, H:i') }}</td>
                    <td style="font-weight:600">{{ $msg->full_name }}</td>
                    <td style="color:#888">{{ $msg->email }}</td>
                    <td>{{ $msg->subject }}</td>
                    <td style="color:#666">{{ \Illuminate\Support\Str::limit($msg->message, 70) }}</td>
                    <td>
                        @if($msg->is_read)
                            <span class="dbadge" style="background:#f4f3f0;color:#888">READ</span>
                        @else
                            <span class="dbadge" style="background:#fff7ed;color:#c2410c">UNREAD</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:#bbb;padding:28px">Belum ada pesan masuk.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
