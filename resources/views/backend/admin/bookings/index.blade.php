@extends('layouts.dashboard')
@section('title', 'Booking - Admin')

@push('styles')
<style>
/* Status tabs */
.status-tabs { display:flex; flex-wrap:wrap; gap:7px; margin-bottom:16px; }
.stab {
    display:inline-flex; align-items:center; gap:6px;
    padding:7px 16px; border-radius:999px; font-size:.78rem; font-weight:600;
    text-decoration:none; border:1.5px solid transparent;
    transition:all 150ms ease; font-family:'Poppins',sans-serif;
}
.stab-all     { background:#f4f3f0; color:#555; border-color:#e0ddd8; }
.stab-all.on  { background:#111; color:#fff; border-color:#111; }
.stab-pending { background:#fff7ed; color:#c2410c; border-color:#fed7aa; }
.stab-pending.on { background:#ea580c; color:#fff; border-color:#ea580c; }
.stab-confirm { background:#f0fdf4; color:#15803d; border-color:#bbf7d0; }
.stab-confirm.on { background:#16a34a; color:#fff; border-color:#16a34a; }
.stab-cancel  { background:#fff1f2; color:#be123c; border-color:#fecdd3; }
.stab-cancel.on { background:#e11d48; color:#fff; border-color:#e11d48; }
.stab-complete { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
.stab-complete.on { background:#2563eb; color:#fff; border-color:#2563eb; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">📅 Data Booking</h1>
</div>

{{-- Status tabs --}}
@php
    $base  = route('admin.bookings.index');
    $dateQ = $selectedDate ? '&booking_date='.$selectedDate : '';
@endphp
<div class="status-tabs">
    <a href="{{ $base }}{{ $dateQ }}" class="stab stab-all {{ !$selectedStatus ? 'on' : '' }}">Semua</a>
    <a href="{{ $base }}?status=PENDING_PAYMENT{{ $dateQ }}" class="stab stab-pending {{ $selectedStatus==='PENDING_PAYMENT' ? 'on' : '' }}">⏳ Pending</a>
    <a href="{{ $base }}?status=CONFIRMED{{ $dateQ }}"       class="stab stab-confirm {{ $selectedStatus==='CONFIRMED'       ? 'on' : '' }}">✅ Confirmed</a>
    <a href="{{ $base }}?status=CANCELLED{{ $dateQ }}"       class="stab stab-cancel  {{ $selectedStatus==='CANCELLED'       ? 'on' : '' }}">❌ Cancelled</a>
    <a href="{{ $base }}?status=COMPLETED{{ $dateQ }}"       class="stab stab-complete {{ $selectedStatus==='COMPLETED'      ? 'on' : '' }}">🏁 Completed</a>
</div>

{{-- Date filter --}}
<div class="filter-bar" style="margin-bottom:20px">
    <form method="get" style="display:contents">
        @if($selectedStatus)<input type="hidden" name="status" value="{{ $selectedStatus }}">@endif
        <div class="filter-field">
            <label>Tanggal Booking</label>
            <input type="date" name="booking_date" class="d-input" value="{{ $selectedDate }}">
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn-g">Filter</button>
            <a href="{{ route('admin.bookings.index', $selectedStatus ? ['status'=>$selectedStatus] : []) }}" class="btn-back">Reset</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="d-card">
    <div class="table-responsive">
        <table class="d-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Guest</th>
                    <th>Studio / Paket</th>
                    <th>Jadwal</th>
                    <th>Total</th>
                    <th>Status Booking</th>
                    <th>Status Transaksi</th>
                    <th width="210">Update Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($bookings as $booking)
                @php
                    $bsCls = match($booking->status){
                        'PENDING_PAYMENT' => 'dbadge-pending',
                        'CONFIRMED'       => 'dbadge-success',
                        'CANCELLED'       => 'dbadge-failed',
                        'COMPLETED'       => 'dbadge-expired',
                        default           => 'dbadge-pending',
                    };
                    $bsLabel = match($booking->status){
                        'PENDING_PAYMENT' => '⏳ Pending',
                        'CONFIRMED'       => '✅ Confirmed',
                        'CANCELLED'       => '❌ Cancelled',
                        'COMPLETED'       => '🏁 Completed',
                        default           => $booking->status,
                    };
                    $txStatus = $booking->paymentTransaction?->status ?? null;
                    $txCls = match($txStatus){
                        'SUCCESS' => 'dbadge-success',
                        'FAILED'  => 'dbadge-failed',
                        'EXPIRED' => 'dbadge-expired',
                        'PENDING' => 'dbadge-pending',
                        default   => 'dbadge-pending',
                    };
                @endphp
                <tr>
                    <td><code style="font-size:.73rem;color:#888;background:#f4f3f0;padding:2px 6px;border-radius:4px">{{ $booking->booking_code }}</code></td>
                    <td>
                        <div style="font-weight:600;font-size:.88rem">{{ $booking->guest->full_name }}</div>
                        <div style="font-size:.75rem;color:#888">{{ $booking->guest->email }}</div>
                    </td>
                    <td>
                        <div style="font-size:.86rem;font-weight:600">{{ $booking->studio->name }}</div>
                        <div style="font-size:.75rem;color:#2f5443;font-weight:500">{{ $booking->servicePackage->name }}</div>
                    </td>
                    <td style="font-size:.83rem">
                        {{ $booking->booking_date->format('d M Y') }}<br>
                        <span style="color:#888">{{ $booking->start_time }} – {{ $booking->end_time }}</span>
                    </td>
                    <td style="font-family:'Playfair Display',serif;font-weight:700;color:#2f5443;white-space:nowrap">
                        Rp{{ number_format($booking->total_amount,0,',','.') }}
                    </td>
                    <td><span class="dbadge {{ $bsCls }}">{{ $bsLabel }}</span></td>
                    <td>
                        @if($txStatus)
                            <span class="dbadge {{ $txCls }}">{{ $txStatus }}</span>
                        @else
                            <span style="color:#bbb;font-size:.8rem">–</span>
                        @endif
                    </td>
                    <td>
                        <form method="post" action="{{ route('admin.bookings.update-status', $booking) }}" style="display:flex;gap:6px">
                            @csrf @method('patch')
                            <select name="status" class="d-select" style="font-size:.8rem;padding:6px 10px">
                                @foreach(['PENDING_PAYMENT'=>'Pending','CONFIRMED'=>'Confirmed','CANCELLED'=>'Cancelled','COMPLETED'=>'Completed'] as $v=>$l)
                                    <option value="{{ $v }}" @selected($booking->status===$v)>{{ $l }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-g" style="padding:6px 14px;font-size:.78rem;white-space:nowrap">Simpan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;color:#bbb;padding:32px">Belum ada data booking.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $bookings->links() }}</div>
@endsection
