@extends('layouts.frontend')
@section('title', 'Status Pembayaran - UPFotoStudio')

@push('styles')
<style>
/* ── Base ─────────────────────────────────── */
.bk {
    --g:  #2f5443; --gd: #1f3d30; --gl: #3d7a5a; --gp: #eef7f2;
    --k:  #111;    --ks: #555;    --km: #999;
    --bg: #fff;    --bg2: #fafaf8; --bg3: #f4f3f0;
    --br: rgba(0,0,0,.07);
    --w:  min(1100px, calc(100% - 48px));
    font-family: 'Poppins', sans-serif; color: var(--k);
    width: 100vw; max-width: 100vw;
    margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);
    background: var(--bg3);
}
.bk h2,.bk h3 { font-family:'Playfair Display',serif; letter-spacing:-.02em; }
.bkc { width: var(--w); margin-inline: auto; }

/* ── STEP INDICATOR ───────────────────────── */
.bk-steps-bar { background: var(--bg); border-bottom: 1px solid var(--br); padding: 20px 0; }
.bk-steps { display:flex; align-items:center; justify-content:center; max-width:480px; margin-inline:auto; }
.bk-step  { display:flex; flex-direction:column; align-items:center; gap:7px; flex-shrink:0; }
.bk-num   { width:38px; height:38px; border-radius:50%; border:2px solid #ddd; background:#f2f2f0; color:#bbb; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.88rem; transition:all 220ms ease; }
.bk-step.active .bk-num { background:var(--g); border-color:var(--g); color:#fff; box-shadow:0 4px 14px rgba(47,84,67,.3); }
.bk-step.done   .bk-num { background:var(--gp); border-color:var(--g); color:var(--g); }
.bk-step.success .bk-num { background:#166534; border-color:#166534; color:#fff; }
.bk-lbl   { font-size:.7rem; font-weight:600; color:#bbb; white-space:nowrap; }
.bk-step.active  .bk-lbl { color:var(--g); }
.bk-step.done    .bk-lbl { color:var(--gl); }
.bk-step.success .bk-lbl { color:#166534; }
.bk-conn  { flex:1; height:1.5px; background:#e0ddd8; margin:0 10px 26px; min-width:36px; max-width:80px; }
.bk-conn.done { background:var(--g); }

/* ── STATUS BANNER ────────────────────────── */
.st-banner {
    padding: 18px 0;
    display: flex; align-items: center; gap: 14px;
}
.st-icon {
    width: 48px; height: 48px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
}
.st-title { font-family:'Playfair Display',serif; font-size: 1.3rem; font-weight: 700; margin-bottom: 2px; }
.st-sub   { font-size: .84rem; }

/* per status */
.st-pending  { --sc: #d97706; --sbg: #fef3c7; --stext: #92400e; }
.st-success  { --sc: #166534; --sbg: #dcfce7; --stext: #14532d; }
.st-failed   { --sc: #dc2626; --sbg: #fee2e2; --stext: #991b1b; }
.st-expired  { --sc: #6b7280; --sbg: #f3f4f6; --stext: #374151; }

.st-banner .st-icon  { background: var(--sbg); color: var(--sc); }
.st-banner .st-title { color: var(--sc); }
.st-banner .st-sub   { color: var(--stext); }

/* ── CARDS ────────────────────────────────── */
.bk-card { background:var(--bg); border:1px solid var(--br); border-radius:18px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.05); }
.bk-pad  { padding: 22px 24px; }

/* ── DETAIL TABLE ─────────────────────────── */
.detail-row {
    display: flex; align-items: baseline;
    padding: 11px 0; border-bottom: 1px solid var(--br);
    font-size: .92rem;
}
.detail-row:last-child { border-bottom: none; }
.detail-lbl { flex-shrink: 0; width: 140px; font-weight: 600; color: var(--ks); font-size: .82rem; text-transform: uppercase; letter-spacing: .04em; }
.detail-val { color: var(--k); flex: 1; }

/* Status badges */
.st-badge {
    display: inline-block; border-radius: 999px;
    padding: 3px 12px; font-size: .72rem; font-weight: 700;
    letter-spacing: .06em; text-transform: uppercase;
}
.badge-pending  { background: #fef3c7; color: #92400e; }
.badge-success  { background: #dcfce7; color: #166534; }
.badge-confirmed { background: #dcfce7; color: #166534; }
.badge-failed   { background: #fee2e2; color: #991b1b; }
.badge-expired  { background: #f3f4f6; color: #6b7280; }
.badge-cancelled { background: #fee2e2; color: #991b1b; }
.badge-completed { background: #dbeafe; color: #1e40af; }
.badge-pending-payment { background: #fef3c7; color: #92400e; }

/* ── QRIS BOX ─────────────────────────────── */
.qris-box {
    background: var(--bg2); border: 1px solid var(--br);
    border-radius: 12px; padding: 14px 16px;
    font-family: 'Courier New', monospace; font-size: .78rem;
    color: var(--ks); word-break: break-all; line-height: 1.6;
    margin-bottom: 10px;
}
.qris-expiry { font-size: .78rem; color: var(--km); }

/* ── MOCK INFO ────────────────────────────── */
.mock-box {
    background: var(--bg2); border: 1px solid var(--br);
    border-radius: 12px; padding: 14px 16px;
}
.mock-box code { font-size: .78rem; color: var(--g); display: block; margin-bottom: 4px; }
.mock-box p { font-size: .82rem; color: var(--ks); margin: 0; }

/* ── BUTTONS ──────────────────────────────── */
.bk-dl-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--g); color: #fff;
    border-radius: 999px; padding: 11px 22px;
    font-size: .84rem; font-weight: 600;
    text-decoration: none; transition: background 180ms ease, transform 180ms ease;
}
.bk-dl-btn:hover { background: var(--gd); color: #fff; transform: translateY(-1px); }

.bk-home-link {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .82rem; color: var(--ks); text-decoration: none;
    transition: color 140ms ease;
}
.bk-home-link:hover { color: var(--g); }

@media (max-width:991.98px) { .bk { --w: min(1100px,calc(100% - 32px)); } }
@media (max-width:575.98px) { .bk { --w: calc(100% - 24px); } .detail-lbl { width: 110px; } }
</style>
@endpush

@section('content')
@php
    $txStatus = strtolower($transaction->status);  /* pending, success, failed, expired */
    $bkStatus = strtolower($transaction->booking->status);

    $statusMap = [
        'pending'  => ['icon'=>'⏳', 'title'=>'Menunggu Pembayaran',   'sub'=>'Selesaikan pembayaran QRIS sebelum kadaluarsa.', 'cls'=>'st-pending'],
        'success'  => ['icon'=>'✅', 'title'=>'Pembayaran Berhasil',    'sub'=>'Transaksi dikonfirmasi. Booking Anda aktif!',     'cls'=>'st-success'],
        'failed'   => ['icon'=>'❌', 'title'=>'Pembayaran Gagal',       'sub'=>'Silakan coba booking kembali.',                   'cls'=>'st-failed'],
        'expired'  => ['icon'=>'⌛', 'title'=>'Pembayaran Kadaluarsa',  'sub'=>'Batas waktu pembayaran telah habis.',             'cls'=>'st-expired'],
    ];
    $si = $statusMap[$txStatus] ?? $statusMap['pending'];

    $stepThreeCls = match($txStatus) {
        'success'  => 'success',
        'failed','expired' => 'done',
        default    => 'active',
    };
@endphp

<div class="bk">

    {{-- STEP INDICATOR --}}
    <div class="bk-steps-bar">
        <div class="bk-steps">
            <div class="bk-step done">
                <div class="bk-num">✓</div>
                <span class="bk-lbl">Pilih Jadwal</span>
            </div>
            <div class="bk-conn done"></div>
            <div class="bk-step done">
                <div class="bk-num">✓</div>
                <span class="bk-lbl">Detail Pesanan</span>
            </div>
            <div class="bk-conn done"></div>
            <div class="bk-step {{ $stepThreeCls }}">
                <div class="bk-num">{{ $txStatus === 'success' ? '✓' : '3' }}</div>
                <span class="bk-lbl">Pembayaran</span>
            </div>
        </div>
    </div>

    <div class="bkc" style="padding:32px 0 72px;">

        @if(session('error'))
            <div class="bk-card mb-4" style="border-color:rgba(220,38,38,.3);">
                <div class="bk-pad" style="color:#991b1b;font-size:.88rem;">⚠️ {{ session('error') }}</div>
            </div>
        @endif

        @if(session('success'))
            <div class="bk-card mb-4" style="border-color:rgba(22,101,52,.3);">
                <div class="bk-pad" style="color:#166534;font-size:.88rem;">✅ {{ session('success') }}</div>
            </div>
        @endif

        {{-- Status Banner --}}
        <div class="bk-card mb-4 {{ $si['cls'] }}">
            <div class="bk-pad st-banner">
                <div class="st-icon">{{ $si['icon'] }}</div>
                <div>
                    <div class="st-title">{{ $si['title'] }}</div>
                    <div class="st-sub">{{ $si['sub'] }}</div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- LEFT: Booking details --}}
            <div class="col-lg-7">
                <div class="bk-card">
                    <div class="bk-pad">
                        <h2 style="font-size:1.2rem;margin-bottom:6px;">Detail Booking</h2>
                        <p style="font-size:.82rem;color:var(--km);margin-bottom:18px;">
                            Invoice: <strong style="color:var(--k);">{{ $transaction->invoice_number }}</strong>
                        </p>

                        <div class="detail-row">
                            <span class="detail-lbl">Kode Booking</span>
                            <span class="detail-val" style="font-family:'Courier New',monospace;font-size:.88rem;">{{ $transaction->booking->booking_code }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-lbl">Nama</span>
                            <span class="detail-val">{{ $transaction->booking->guest->full_name }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-lbl">Studio</span>
                            <span class="detail-val">{{ $transaction->booking->studio->name }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-lbl">Paket</span>
                            <span class="detail-val">{{ $transaction->booking->servicePackage->name }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-lbl">Tanggal</span>
                            <span class="detail-val">{{ $transaction->booking->booking_date->translatedFormat('j F Y') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-lbl">Waktu</span>
                            <span class="detail-val">{{ $transaction->booking->start_time }} – {{ $transaction->booking->end_time }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-lbl">Jenis Bayar</span>
                            <span class="detail-val">{{ $transaction->payment_type }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-lbl">Nominal</span>
                            <span class="detail-val" style="font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--g);">
                                Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-lbl">Status Transaksi</span>
                            <span class="detail-val">
                                <span class="st-badge badge-{{ $txStatus }}">{{ $transaction->status }}</span>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-lbl">Status Booking</span>
                            <span class="detail-val">
                                @php $bkCls = str_replace('_', '-', strtolower($transaction->booking->status)); @endphp
                                <span class="st-badge badge-{{ $bkCls }}">{{ $transaction->booking->status }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="bk-pad" style="border-top:1px solid var(--br);padding-top:16px;display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                        <a href="{{ route('frontend.booking.invoice', $transaction->invoice_number) }}" class="bk-dl-btn">
                            ↓ Download Invoice PDF
                        </a>
                        <a href="{{ route('frontend.home') }}" class="bk-home-link">← Kembali ke Beranda</a>
                    </div>
                </div>
            </div>

            {{-- RIGHT: QR + mock --}}
            <div class="col-lg-5">

                @if(in_array($txStatus, ['expired', 'failed']))
                    <div class="bk-card mb-3" style="border-color:rgba(217,119,6,.25);">
                        <div class="bk-pad" style="text-align:center;padding:24px;">
                            <div style="font-size:2rem;margin-bottom:8px;">{{ $txStatus === 'expired' ? '⌛' : '❌' }}</div>
                            <h3 style="font-size:1rem;margin-bottom:6px;">
                                {{ $txStatus === 'expired' ? 'Waktu Pembayaran Habis' : 'Pembayaran Gagal' }}
                            </h3>
                            <p style="font-size:.84rem;color:var(--ks);margin-bottom:16px;">
                                Buat ulang QR untuk melanjutkan pembayaran. Jadwal akan dicek ulang ketersediaannya.
                            </p>
                            <form method="POST" action="{{ route('frontend.booking.repay', $transaction->invoice_number) }}">
                                @csrf
                                <button type="submit" class="bk-dl-btn" style="border:none;cursor:pointer;">
                                    🔄 Buat QR Baru &amp; Bayar
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($txStatus !== 'expired' && $txStatus !== 'failed' && (request('gateway') === 'mock' || $transaction->qr_payload))
                    <div class="bk-card mb-3">
                        <div class="bk-pad">
                            <h3 style="font-size:1rem;margin-bottom:12px;">QRIS Payload</h3>
                            <div class="qris-box">{{ $transaction->qr_payload ?? '-' }}</div>
                            @if($transaction->expires_at)
                                <p class="qris-expiry">
                                    Kadaluarsa: <strong>{{ $transaction->expires_at->format('d-m-Y H:i') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                @if(request('gateway') === 'mock')
                    <div class="bk-card">
                        <div class="bk-pad">
                            <h3 style="font-size:.95rem;margin-bottom:10px;color:var(--ks);">Mode Simulasi</h3>
                            <div class="mock-box">
                                <code>POST /api/payments/qris/callback</code>
                                <p>Gunakan invoice di atas dengan status <strong>SUCCESS</strong>, <strong>FAILED</strong>, atau <strong>EXPIRED</strong> untuk simulasi.</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($txStatus === 'success')
                    <div class="bk-card" style="border-color: rgba(22,101,52,.2);">
                        <div class="bk-pad" style="text-align:center;padding:28px;">
                            <div style="font-size:2.5rem;margin-bottom:12px;">🎉</div>
                            <h3 style="font-size:1.1rem;color:#166534;margin-bottom:8px;">Booking Dikonfirmasi!</h3>
                            <p style="font-size:.88rem;color:var(--ks);margin:0;">Sampai jumpa di sesi foto Anda. Datang tepat waktu ya!</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
