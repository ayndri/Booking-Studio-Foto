@extends('layouts.dashboard')
@section('title', 'Laporan - Admin')

@push('styles')
<style>
.rpt-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }
.rpt-stat  { background:#fff; border:1px solid rgba(0,0,0,.07); border-radius:12px; padding:16px 18px; box-shadow:0 1px 6px rgba(0,0,0,.04); }
.rpt-stat-lbl { font-size:.68rem; font-weight:700; color:#888; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
.rpt-stat-val { font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700; color:#111; line-height:1; }
@media(max-width:767.98px){ .rpt-stats { grid-template-columns:1fr 1fr; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">📈 Laporan Transaksi</h1>
</div>

<div class="filter-bar">
    <form method="get" action="{{ route('admin.reports.index') }}" style="display:contents">
        <div class="filter-field">
            <label>Periode</label>
            <select name="period" class="d-select" style="min-width:140px">
                @foreach(['daily'=>'Harian','weekly'=>'Mingguan','monthly'=>'Bulanan','yearly'=>'Tahunan','custom'=>'Custom'] as $v=>$l)
                    <option value="{{ $v }}" @selected($filter['period']===$v)>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-field">
            <label>Dari</label>
            <input type="date" name="from" class="d-input" value="{{ $filter['from'] }}">
        </div>
        <div class="filter-field">
            <label>Sampai</label>
            <input type="date" name="to" class="d-input" value="{{ $filter['to'] }}">
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn-g">Tampilkan</button>
            <a href="{{ route('admin.reports.export-pdf', ['period'=>$filter['period'],'from'=>$filter['from'],'to'=>$filter['to']]) }}"
               class="btn-export">↓ Export PDF</a>
        </div>
    </form>
</div>

{{-- Summary Stats --}}
<div class="rpt-stats">
    <div class="rpt-stat" style="border-left:3px solid #2563eb">
        <div class="rpt-stat-lbl">Total Transaksi</div>
        <div class="rpt-stat-val">{{ $summary['total_transactions'] }}</div>
    </div>
    <div class="rpt-stat" style="border-left:3px solid #16a34a">
        <div class="rpt-stat-lbl">Transaksi Sukses</div>
        <div class="rpt-stat-val">{{ $summary['success_transactions'] }}</div>
    </div>
    <div class="rpt-stat" style="border-left:3px solid #166534">
        <div class="rpt-stat-lbl">Total Sukses</div>
        <div class="rpt-stat-val" style="font-size:1.1rem">Rp{{ number_format($summary['total_success_amount'],0,',','.') }}</div>
    </div>
    <div class="rpt-stat" style="border-left:3px solid #2f5443">
        <div class="rpt-stat-lbl">Total Nominal</div>
        <div class="rpt-stat-val" style="font-size:1.1rem">Rp{{ number_format($summary['total_amount'],0,',','.') }}</div>
    </div>
</div>

<div class="d-card">
    <div class="table-responsive">
        <table class="d-table">
            <thead>
                <tr><th>Invoice</th><th>Tanggal</th><th>Guest</th><th>Studio</th><th>Nominal</th><th>Status</th></tr>
            </thead>
            <tbody>
            @forelse($transactions as $tx)
                @php $txCls = match(strtolower($tx->status)){'success'=>'dbadge-success','failed'=>'dbadge-failed','expired'=>'dbadge-expired',default=>'dbadge-pending'}; @endphp
                <tr>
                    <td><code style="font-size:.75rem;color:#888;background:#f4f3f0;padding:2px 6px;border-radius:4px">{{ $tx->invoice_number }}</code></td>
                    <td style="color:#888;font-size:.82rem;white-space:nowrap">{{ $tx->created_at->format('d M Y, H:i') }}</td>
                    <td style="font-weight:600">{{ $tx->booking->guest->full_name ?? '–' }}</td>
                    <td>
                        <span style="font-size:.78rem;background:#eef7f2;color:#2f5443;padding:2px 8px;border-radius:999px;font-weight:600">
                            {{ $tx->booking->studio->name ?? '–' }}
                        </span>
                    </td>
                    <td style="font-family:'Playfair Display',serif;font-weight:700;color:#2f5443">Rp{{ number_format($tx->amount,0,',','.') }}</td>
                    <td><span class="dbadge {{ $txCls }}">{{ $tx->status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:#bbb;padding:32px">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
