<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $transaction->invoice_number }}</title>
    <style>
        @page { margin: 28px 32px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #2b2b2b; margin: 0; }

        .brand { font-size: 22px; font-weight: bold; color: #2f5443; letter-spacing: 1px; }
        .brand-sub { font-size: 10px; color: #777; margin-top: 2px; }
        .contact { font-size: 9.5px; color: #777; line-height: 1.5; margin-top: 6px; }

        .doc-title { font-size: 26px; font-weight: bold; color: #2f5443; letter-spacing: 2px; text-align: right; }
        .meta { font-size: 10px; color: #555; text-align: right; line-height: 1.7; margin-top: 6px; }
        .meta strong { color: #222; }

        .badge { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 9.5px; font-weight: bold; letter-spacing: .5px; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-failed  { background: #fee2e2; color: #991b1b; }
        .badge-expired { background: #f3f4f6; color: #6b7280; }

        .hr { border: none; border-top: 2px solid #2f5443; margin: 14px 0; }

        .section-label { font-size: 9px; font-weight: bold; color: #2f5443; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .info-box { background: #f7f9f8; border: 1px solid #e7ece9; border-radius: 8px; padding: 10px 12px; line-height: 1.6; }
        .info-box .name { font-size: 13px; font-weight: bold; color: #222; }

        table.items { width: 100%; border-collapse: collapse; margin-top: 4px; }
        table.items th { background: #2f5443; color: #fff; font-size: 10px; text-align: left; padding: 8px 10px; }
        table.items th.r, table.items td.r { text-align: right; }
        table.items td { padding: 9px 10px; border-bottom: 1px solid #eee; vertical-align: top; }
        table.items .item-name { font-weight: bold; color: #222; }
        table.items .item-meta { font-size: 9.5px; color: #888; margin-top: 2px; }

        table.totals { width: 46%; border-collapse: collapse; margin-left: 54%; margin-top: 8px; }
        table.totals td { padding: 6px 10px; font-size: 10.5px; }
        table.totals td.r { text-align: right; }
        table.totals tr.grand td { background: #eef7f2; color: #2f5443; font-weight: bold; font-size: 13px; border-top: 2px solid #2f5443; }
        table.totals tr.due td { color: #b45309; font-weight: bold; }

        .pay-info { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .pay-info td { padding: 4px 0; font-size: 10px; vertical-align: top; }
        .pay-info td.k { color: #777; width: 38%; }
        .pay-info td.v { color: #222; font-weight: bold; }

        .notes-box { background: #fffdf5; border: 1px solid #f0e6c8; border-radius: 8px; padding: 9px 12px; font-size: 9.5px; color: #6b5a2a; line-height: 1.6; }

        .footer { margin-top: 22px; border-top: 1px solid #eee; padding-top: 10px; font-size: 9px; color: #999; line-height: 1.6; text-align: center; }
    </style>
</head>
<body>

@php
    $txStatus = strtolower($transaction->status);
    $badgeCls = 'badge-' . (in_array($txStatus, ['success','pending','failed','expired']) ? $txStatus : 'pending');

    $packagePrice = (int) $transaction->booking->servicePackage->price;
    $addOnAmount  = (int) $booking->add_on_amount;
    $orderTotal   = (int) $booking->total_amount;
    $paid         = (int) $transaction->amount;
    $isDp         = $transaction->payment_type === 'DP';
    $due          = max($orderTotal - $paid, 0);

    // Pecah notes: ambil rincian add-on, pisahkan catatan lain.
    $noteParts = array_values(array_filter(array_map('trim', explode('|', (string) $booking->notes))));
    $addOnNames = [];
    $otherNotes = [];
    foreach ($noteParts as $part) {
        if (stripos($part, 'Add-ons:') === 0) {
            $list = trim(substr($part, strlen('Add-ons:')));
            $addOnNames = array_values(array_filter(array_map('trim', explode(',', $list))));
        } else {
            $otherNotes[] = $part;
        }
    }

    $rp = fn ($v) => 'Rp' . number_format((int) $v, 0, ',', '.');
@endphp

{{-- HEADER --}}
<table style="width:100%; border-collapse:collapse;">
    <tr>
        <td style="vertical-align:top; width:55%;">
            <div class="brand">UPFotoStudio</div>
            <div class="brand-sub">Studio Foto &amp; Booking Online</div>
            <div class="contact">
                Surabaya, Indonesia<br>
                hello@upfotostudio.test &bull; (+62) 812 0000 0000
            </div>
        </td>
        <td style="vertical-align:top; width:45%;">
            <div class="doc-title">INVOICE</div>
            <div class="meta">
                No: <strong>{{ $transaction->invoice_number }}</strong><br>
                Tanggal: <strong>{{ $transaction->created_at->translatedFormat('d M Y, H:i') }}</strong><br>
                <span style="display:inline-block; margin-top:4px;">
                    <span class="badge {{ $badgeCls }}">{{ $transaction->status }}</span>
                </span>
            </div>
        </td>
    </tr>
</table>

<hr class="hr">

{{-- BILLED TO + BOOKING --}}
<table style="width:100%; border-collapse:collapse;">
    <tr>
        <td style="vertical-align:top; width:48%; padding-right:10px;">
            <div class="section-label">Ditagihkan Kepada</div>
            <div class="info-box">
                <div class="name">{{ $booking->guest->full_name }}</div>
                {{ $booking->guest->email }}<br>
                {{ $booking->guest->phone }}
            </div>
        </td>
        <td style="vertical-align:top; width:48%; padding-left:10px;">
            <div class="section-label">Detail Booking</div>
            <div class="info-box">
                Kode: <strong>{{ $booking->booking_code }}</strong><br>
                {{ $booking->studio->name }}<br>
                {{ $booking->booking_date->translatedFormat('l, d F Y') }}<br>
                Jam {{ \Illuminate\Support\Str::of($booking->start_time)->substr(0,5) }} – {{ \Illuminate\Support\Str::of($booking->end_time)->substr(0,5) }}
            </div>
        </td>
    </tr>
</table>

{{-- LINE ITEMS --}}
<div class="section-label" style="margin-top:16px;">Rincian Pesanan</div>
<table class="items">
    <thead>
        <tr>
            <th>Deskripsi</th>
            <th class="r" style="width:30%;">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <div class="item-name">{{ $booking->servicePackage->name }}</div>
                <div class="item-meta">Paket foto &bull; {{ $booking->servicePackage->duration_minutes }} menit</div>
            </td>
            <td class="r">{{ $rp($packagePrice) }}</td>
        </tr>
        @if($addOnAmount > 0)
            <tr>
                <td>
                    <div class="item-name">Add-on</div>
                    <div class="item-meta">{{ count($addOnNames) ? implode(', ', $addOnNames) : 'Layanan tambahan' }}</div>
                </td>
                <td class="r">{{ $rp($addOnAmount) }}</td>
            </tr>
        @endif
    </tbody>
</table>

{{-- TOTALS --}}
<table class="totals">
    <tr>
        <td>Subtotal</td>
        <td class="r">{{ $rp($orderTotal) }}</td>
    </tr>
    <tr>
        <td>Pembayaran ({{ $transaction->payment_type }})</td>
        <td class="r">{{ $rp($paid) }}</td>
    </tr>
    <tr class="grand">
        <td>{{ $isDp ? 'Dibayar' : 'Total' }}</td>
        <td class="r">{{ $rp($paid) }}</td>
    </tr>
    @if($isDp && $due > 0)
        <tr class="due">
            <td>Sisa di studio</td>
            <td class="r">{{ $rp($due) }}</td>
        </tr>
    @endif
</table>

{{-- PAYMENT INFO --}}
<div style="margin-top:18px;">
    <div class="section-label">Informasi Pembayaran</div>
    <table class="pay-info">
        <tr>
            <td class="k">Metode Pembayaran</td>
            <td class="v">{{ $transaction->payment_method ?? 'QRIS' }}{{ config('services.payment_gateway') === 'midtrans' ? ' (Midtrans)' : '' }}</td>
        </tr>
        <tr>
            <td class="k">Jenis</td>
            <td class="v">{{ $transaction->payment_type === 'DP' ? 'DP (30%)' : 'Lunas (100%)' }}</td>
        </tr>
        <tr>
            <td class="k">Status Transaksi</td>
            <td class="v">{{ $transaction->status }}</td>
        </tr>
        <tr>
            <td class="k">Tanggal Lunas</td>
            <td class="v">{{ $transaction->paid_at ? $transaction->paid_at->translatedFormat('d M Y, H:i') : '-' }}</td>
        </tr>
        @if($transaction->gateway_reference)
            <tr>
                <td class="k">Referensi</td>
                <td class="v">{{ $transaction->gateway_reference }}</td>
            </tr>
        @endif
    </table>
</div>

{{-- CATATAN --}}
@if(!empty($otherNotes))
    <div style="margin-top:14px;">
        <div class="section-label">Catatan</div>
        <div class="notes-box">{{ implode(' • ', $otherNotes) }}</div>
    </div>
@endif

<div class="footer">
    Terima kasih telah melakukan booking di UPFotoStudio.<br>
    Dokumen ini dibuat otomatis oleh sistem dan sah tanpa tanda tangan. Reschedule maks. 1x, 24 jam sebelum sesi. Pembayaran tidak dapat di-refund.
</div>

</body>
</html>
