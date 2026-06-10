@extends('layouts.frontend')
@section('title', 'Detail Pesanan - UPFotoStudio')

@push('styles')
<style>
/* ── Base (sama dengan package-detail) ────── */
.bk {
    --g:  #2f5443; --gd: #1f3d30; --gl: #3d7a5a; --gp: #eef7f2;
    --k:  #111;    --ks: #555;    --km: #999;
    --bg: #fff;    --bg2: #fafaf8; --bg3: #f4f3f0;
    --br: rgba(0,0,0,.07);
    --w:  min(1200px, calc(100% - 48px));
    font-family: 'Poppins', sans-serif; color: var(--k);
    width: 100vw; max-width: 100vw;
    margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);
    background: var(--bg3);
}
.bk h2,.bk h3 { font-family:'Playfair Display',serif; letter-spacing:-.02em; }
.bkc { width: var(--w); margin-inline: auto; }

/* ── STEP INDICATOR ───────────────────────── */
.bk-steps-bar { background: var(--bg); border-bottom: 1px solid var(--br); padding: 20px 0; }
.bk-steps { display:flex; align-items:center; justify-content:center; width:var(--w); margin-inline:auto; max-width:480px; }
.bk-step  { display:flex; flex-direction:column; align-items:center; gap:7px; flex-shrink:0; }
.bk-num   { width:38px; height:38px; border-radius:50%; border:2px solid #ddd; background:#f2f2f0; color:#bbb; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.88rem; transition:all 220ms ease; }
.bk-step.active .bk-num { background:var(--g); border-color:var(--g); color:#fff; box-shadow:0 4px 14px rgba(47,84,67,.3); }
.bk-step.done   .bk-num { background:var(--gp); border-color:var(--g); color:var(--g); }
.bk-lbl   { font-size:.7rem; font-weight:600; color:#bbb; white-space:nowrap; }
.bk-step.active .bk-lbl { color:var(--g); }
.bk-step.done   .bk-lbl { color:var(--gl); }
.bk-conn  { flex:1; height:1.5px; background:#e0ddd8; margin:0 10px 26px; min-width:36px; max-width:80px; }
.bk-conn.done { background:var(--g); }

/* ── CARDS / PANELS ───────────────────────── */
.bk-panel { background:var(--bg); border:1px solid var(--br); border-radius:18px; padding:22px 24px; margin-bottom:18px; box-shadow:0 2px 8px rgba(0,0,0,.04); }
.bk-panel h2 { font-size:1.2rem; margin-bottom:16px; color:var(--k); }
.bk-panel h3 { font-size:1.05rem; margin-bottom:12px; color:var(--k); }

/* Form inputs */
.bk-label { display:block; font-size:.78rem; font-weight:600; color:var(--ks); margin-bottom:5px; }
.bk-label .opt { font-weight:400; color:var(--km); }
.bk-input, .bk-select, .bk-textarea {
    width:100%; border:1.5px solid rgba(0,0,0,.1); border-radius:10px;
    padding:11px 14px; font-family:'Poppins',sans-serif; font-size:.91rem; color:var(--k);
    background:var(--bg); outline:none;
    transition:border-color 200ms ease, box-shadow 200ms ease;
    -webkit-appearance:none;
}
.bk-input:focus,.bk-select:focus,.bk-textarea:focus {
    border-color:rgba(47,84,67,.4); box-shadow:0 0 0 3px rgba(47,84,67,.08);
}
.bk-textarea { resize:vertical; min-height:80px; }
.bk-field { margin-bottom:0; }

/* Package recap */
.pkg-recap { display:flex; align-items:center; gap:14px; }
.pkg-recap-img { width:80px; height:80px; border-radius:12px; object-fit:cover; flex-shrink:0; background:#d8d5cf; }
.pkg-recap-studio { font-size:.7rem; font-weight:600; color:var(--gl); text-transform:uppercase; letter-spacing:.08em; margin-bottom:3px; }
.pkg-recap-name   { font-family:'Playfair Display',serif; font-size:1.05rem; font-weight:700; color:var(--k); margin-bottom:4px; }
.pkg-recap-meta   { font-size:.82rem; color:var(--ks); }

/* Add-ons */
.addon-card {
    background:var(--bg); border:1px solid var(--br); border-radius:14px;
    padding:16px; height:100%; display:flex; flex-direction:column;
    transition:border-color 200ms ease;
}
.addon-card:hover { border-color:rgba(47,84,67,.2); }
.addon-header { display:flex; gap:12px; align-items:flex-start; }
.addon-icon {
    width:40px; height:40px; border-radius:10px; background:var(--gp); color:var(--g);
    display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; flex-shrink:0;
}
.addon-name      { font-family:'Playfair Display',serif; font-size:1rem; font-weight:700; color:var(--k); line-height:1.2; }
.addon-price-tag { font-size:.82rem; color:var(--ks); margin-top:2px; }
.addon-desc      { font-size:.84rem; color:var(--ks); line-height:1.6; margin:12px 0; flex:1; }
.addon-footer    { margin-top:auto; display:flex; align-items:center; justify-content:space-between; gap:8px; }
.addon-subtotal  { font-family:'Playfair Display',serif; font-size:1rem; font-weight:700; color:var(--g); }

/* Qty control */
.qty-control { display:inline-flex; align-items:center; gap:6px; border:1px solid var(--br); border-radius:10px; padding:3px 8px; background:var(--bg); }
.qty-control button { width:26px; height:26px; border:0; border-radius:50%; font-weight:700; line-height:1; cursor:pointer; transition:background 140ms ease; }
.qty-control .minus { background:#f0f0ec; color:var(--ks); }
.qty-control .minus:hover { background:#e0e0da; }
.qty-control .plus  { background:var(--g); color:#fff; }
.qty-control .plus:hover  { background:var(--gd); }
.qty-control .addon-qty   { min-width:18px; text-align:center; font-weight:700; font-size:.9rem; }

/* Payment method cards */
.pay-method-grid { display:flex; flex-wrap:wrap; gap:10px; }
.pay-option { position:relative; width:180px; }
.pay-option input { position:absolute; width:0; height:0; opacity:0; pointer-events:none; }
.pay-card {
    display:block;
    border:1.5px solid rgba(0,0,0,.1); border-radius:12px; padding:16px 18px;
    text-align:center; cursor:pointer; user-select:none;
    transition:all 160ms ease; background:var(--bg);
}
.pay-card .logo  { font-family:'Poppins',sans-serif; font-size:1.5rem; font-weight:800; letter-spacing:2px; color:var(--k); line-height:1; display:block; }
.pay-card .hint  { font-size:.7rem; margin-top:6px; color:var(--km); display:block; line-height:1.4; }
.pay-option input:checked + .pay-card { border-color:var(--g); background:var(--gp); }
.pay-option input:checked + .pay-card .logo  { color:var(--g); }
.pay-option input:checked + .pay-card .hint  { color:var(--gl); }

/* Summary sticky */
.bk-sum-panel { position:sticky; top:88px; background:var(--bg); border:1px solid var(--br); border-radius:18px; padding:22px 24px; box-shadow:0 2px 10px rgba(0,0,0,.05); }
.bk-sum-h2    { font-family:'Playfair Display',serif; font-size:1.2rem; font-weight:700; color:var(--k); margin-bottom:16px; }
.sum-row      { display:flex; justify-content:space-between; align-items:center; gap:8px; font-size:.9rem; color:var(--ks); margin-bottom:8px; }
.sum-row strong { color:var(--k); }
.sum-hr       { border:none; border-top:1px solid var(--br); margin:12px 0; }
.sum-total    { font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700; color:var(--g); }
.bk-pay-btn {
    display:block; width:100%; text-align:center;
    background:var(--g); color:#fff; border:none; border-radius:999px;
    padding:14px; font-family:'Poppins',sans-serif; font-size:.9rem; font-weight:600;
    cursor:pointer; margin-top:16px; transition:background 180ms ease, transform 180ms ease;
}
.bk-pay-btn:hover { background:var(--gd); transform:translateY(-1px); }

.sum-terms { font-size:.75rem; color:var(--km); line-height:1.6; margin-top:12px; padding-left:14px; }
.sum-terms li { margin-bottom:4px; }

.bk-back { font-size:.82rem; color:var(--ks); text-decoration:none; display:inline-block; margin-bottom:24px; transition:color 140ms ease; }
.bk-back:hover { color:var(--g); }
.bk-sec-title { font-size:.78rem; font-weight:600; color:var(--km); text-transform:uppercase; letter-spacing:.1em; margin-bottom:14px; }

@media (max-width:991.98px) { .bk { --w: min(1200px,calc(100% - 32px)); } .bk-sum-panel { position:static; } }
@media (max-width:575.98px) { .bk { --w: calc(100% - 24px); } .pay-method-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="bk">

{{-- STEP INDICATOR: Step 1 done, Step 2 active --}}
<div class="bk-steps-bar">
    <div class="bk-steps">
        <div class="bk-step done">
            <div class="bk-num">✓</div>
            <span class="bk-lbl">Pilih Jadwal</span>
        </div>
        <div class="bk-conn done"></div>
        <div class="bk-step active">
            <div class="bk-num">2</div>
            <span class="bk-lbl">Detail Pesanan</span>
        </div>
        <div class="bk-conn"></div>
        <div class="bk-step">
            <div class="bk-num">3</div>
            <span class="bk-lbl">Pembayaran</span>
        </div>
    </div>
</div>

<div class="bkc" style="padding:36px 0 72px;">

    <a href="{{ route('frontend.booking.package-detail', ['servicePackage'=>$servicePackage->id,'date'=>$bookingDate,'time'=>$startTime]) }}" class="bk-back">
        &larr; Kembali ke Jadwal
    </a>

    <form method="post" action="{{ route('frontend.booking.store') }}" id="orderForm">
        @csrf
        <input type="hidden" name="service_package_id" value="{{ $servicePackage->id }}">
        <input type="hidden" name="studio_id"          value="{{ $servicePackage->studio_id }}">
        <input type="hidden" name="booking_date"       value="{{ $bookingDate }}">
        <input type="hidden" name="start_time"         value="{{ $startTime }}">
        <input type="hidden" name="add_on_amount" id="add_on_amount" value="0">

        <div class="row g-4">

            {{-- ── LEFT ── --}}
            <div class="col-lg-8">

                {{-- Data Pemesan --}}
                <div class="bk-panel">
                    <h2>Data Pemesan</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="bk-label" for="guest_name">Nama Lengkap</label>
                            <input type="text" id="guest_name" name="guest_name" class="bk-input" value="{{ old('guest_name') }}" placeholder="Nama lengkap kamu" required>
                        </div>
                        <div class="col-md-6">
                            <label class="bk-label" for="guest_phone">Nomor HP</label>
                            <input type="text" id="guest_phone" name="guest_phone" class="bk-input" value="{{ old('guest_phone') }}" placeholder="08xx xxxx xxxx" required>
                        </div>
                        <div class="col-md-6">
                            <label class="bk-label" for="guest_email">Email</label>
                            <input type="email" id="guest_email" name="guest_email" class="bk-input" value="{{ old('guest_email') }}" placeholder="email@contoh.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="bk-label" for="payment_type">Jenis Pembayaran</label>
                            <select name="payment_type" id="payment_type" class="bk-select" required>
                                <option value="LUNAS" @selected(old('payment_type','LUNAS')==='LUNAS')>LUNAS (100%)</option>
                                <option value="DP"    @selected(old('payment_type')==='DP')>DP (30%)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Detail Paket --}}
                <div class="bk-panel">
                    <h2>Detail Paket</h2>
                    <div class="pkg-recap">
                        <img src="{{ $packageImage }}" alt="{{ $servicePackage->name }}" class="pkg-recap-img">
                        <div>
                            <div class="pkg-recap-studio">{{ $servicePackage->studio->name }}</div>
                            <div class="pkg-recap-name">{{ $servicePackage->name }}</div>
                            <div class="pkg-recap-meta">
                                {{ $bookingDateText }} &bull; {{ $startTime }}<br>
                                {{ $peopleLabel }} &bull; {{ $servicePackage->duration_minutes }} menit
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Add-ons --}}
                <div class="mb-2">
                    <p class="bk-sec-title">Add-ons (Opsional)</p>
                </div>
                <div class="row g-3 mb-4">
                    @foreach($addOnCatalog as $addOn)
                        <div class="col-md-6">
                            <div class="addon-card" data-addon-id="{{ $addOn['id'] }}" data-addon-price="{{ $addOn['price'] }}">
                                <div class="addon-header">
                                    <div class="addon-icon">{{ $addOn['icon'] ?? 'AD' }}</div>
                                    <div>
                                        <div class="addon-name">{{ $addOn['name'] }}</div>
                                        <div class="addon-price-tag">Rp{{ number_format($addOn['price'],0,',','.') }}{{ $addOn['unit'] }}</div>
                                    </div>
                                </div>
                                <p class="addon-desc">{{ $addOn['description'] }}</p>
                                <div class="addon-footer">
                                    <span class="addon-subtotal">Rp 0</span>
                                    <div class="qty-control">
                                        <button type="button" class="minus">−</button>
                                        <strong class="addon-qty">{{ (int)old('add_ons.'.$addOn['id'].'.qty',0) }}</strong>
                                        <button type="button" class="plus">+</button>
                                    </div>
                                </div>
                                <input type="hidden" name="add_ons[{{ $addOn['id'] }}][qty]" value="{{ (int)old('add_ons.'.$addOn['id'].'.qty',0) }}" class="addon-qty-input">
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Lain-lain --}}
                <div class="bk-panel">
                    <h2>Preferensi Sesi</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="bk-label" for="background_choice">Background yang diinginkan</label>
                            <select name="background_choice" id="background_choice" class="bk-select" required>
                                @foreach(['Abu-abu muda','Sky blue','Mocca','Merah (khusus Identity Photo)','Biru (khusus Identity Photo)','Putih'] as $bg)
                                    <option value="{{ $bg }}" @selected(old('background_choice')===$bg)>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="bk-label" for="social_consent">Upload ke sosial media?</label>
                            <select name="social_consent" id="social_consent" class="bk-select" required>
                                <option value="DENY"  @selected(old('social_consent','DENY')==='DENY')>Tidak boleh</option>
                                <option value="ALLOW" @selected(old('social_consent')==='ALLOW')>Boleh</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="bk-label">Metode Pembayaran</label>
                            <div class="pay-method-grid">
                                <label class="pay-option" for="payment_method_qris">
                                    <input id="payment_method_qris" type="radio" name="payment_method" value="QRIS" @checked(old('payment_method')==='QRIS')>
                                    <span class="pay-card" id="payment_card_qris" role="button" tabindex="0" aria-label="Pilih QRIS">
                                        <span class="logo">QRIS</span>
                                        <span class="hint">Klik pilih, klik lagi untuk bayar</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="bk-label" for="notes">Catatan <span class="opt">(opsional)</span></label>
                            <textarea name="notes" id="notes" class="bk-textarea" placeholder="Tulis catatan khusus untuk tim kami...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: Summary ── --}}
            <div class="col-lg-4">
                <div class="bk-sum-panel">
                    <div class="bk-sum-h2">Ringkasan Pembayaran</div>

                    <div class="sum-row">
                        <span>Metode</span>
                        <strong id="summary_payment_method">{{ old('payment_method') ?: '-' }}</strong>
                    </div>
                    <div class="sum-row">
                        <span>Jenis</span>
                        <strong id="summary_payment_type_label">LUNAS</strong>
                    </div>
                    <div class="sum-row">
                        <span style="max-width:60%;font-size:.82rem;">{{ $servicePackage->name }}</span>
                        <strong>Rp{{ number_format($servicePackage->price,0,',','.') }}</strong>
                    </div>
                    <div class="sum-row">
                        <span>Add-ons</span>
                        <strong id="summary_addon_total">Rp 0</strong>
                    </div>
                    <hr class="sum-hr">
                    <div class="sum-row mb-0">
                        <span>Total Order</span>
                        <strong id="summary_order_total">Rp{{ number_format($servicePackage->price,0,',','.') }}</strong>
                    </div>
                    <div class="sum-row mt-1">
                        <span style="font-size:.78rem;">Bayar Sekarang</span>
                        <span class="sum-total" id="summary_pay_now">Rp{{ number_format($servicePackage->price,0,',','.') }}</span>
                    </div>

                    <ul class="sum-terms">
                        <li>Reschedule maks. 1x, 24 jam sebelum sesi.</li>
                        <li>Cancel tidak dapat refund.</li>
                        <li>Dengan bayar, Anda setuju S&amp;K berlaku.</li>
                    </ul>

                    <button type="submit" class="bk-pay-btn">Bayar Sekarang &rarr;</button>
                </div>
            </div>

        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
    const packagePrice = Number({{ $servicePackage->price }});
    const orderForm = document.getElementById('orderForm');
    const addOnAmountInput = document.getElementById('add_on_amount');
    const paymentTypeSelect = document.getElementById('payment_type');
    const qrisMethodInput = document.getElementById('payment_method_qris');
    const qrisMethodCard = document.getElementById('payment_card_qris');

    const summaryAddonTotal = document.getElementById('summary_addon_total');
    const summaryOrderTotal = document.getElementById('summary_order_total');
    const summaryPayNow = document.getElementById('summary_pay_now');
    const summaryPaymentMethod = document.getElementById('summary_payment_method');
    const summaryPaymentTypeLabel = document.getElementById('summary_payment_type_label');

    function formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    }

    function calculatePaymentNow(orderTotal, paymentType) {
        if (paymentType === 'DP') {
            return Math.round(orderTotal * 0.3);
        }
        return orderTotal;
    }

    function refreshSummary() {
        let addOnTotal = 0;
        document.querySelectorAll('[data-addon-id]').forEach((card) => {
            const price = Number(card.getAttribute('data-addon-price'));
            const qty = Number(card.querySelector('.addon-qty-input').value || 0);
            const subtotal = price * qty;
            card.querySelector('.addon-subtotal').textContent = formatRupiah(subtotal);
            addOnTotal += subtotal;
        });
        const orderTotal = packagePrice + addOnTotal;
        const paymentType = paymentTypeSelect.value;
        const payNow = calculatePaymentNow(orderTotal, paymentType);
        addOnAmountInput.value = addOnTotal;
        summaryAddonTotal.textContent = formatRupiah(addOnTotal);
        summaryOrderTotal.textContent = formatRupiah(orderTotal);
        summaryPayNow.textContent = formatRupiah(payNow);
        summaryPaymentTypeLabel.textContent = paymentType === 'DP' ? 'DP' : 'LUNAS';
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        summaryPaymentMethod.textContent = selectedMethod ? selectedMethod.value : '-';
    }

    function handleQrisCardClick() {
        if (!qrisMethodInput || !orderForm) return;
        const wasChecked = qrisMethodInput.checked;
        qrisMethodInput.checked = true;
        refreshSummary();
        if (!wasChecked) return;
        if (orderForm.checkValidity()) { orderForm.requestSubmit(); return; }
        orderForm.reportValidity();
    }

    document.querySelectorAll('[data-addon-id]').forEach((card) => {
        const qtyEl = card.querySelector('.addon-qty');
        const qtyInput = card.querySelector('.addon-qty-input');
        const minusBtn = card.querySelector('.minus');
        const plusBtn = card.querySelector('.plus');
        function setQty(newQty) {
            const qty = Math.max(0, Math.min(50, newQty));
            qtyEl.textContent = qty;
            qtyInput.value = qty;
            refreshSummary();
        }
        minusBtn.addEventListener('click', () => setQty(Number(qtyInput.value) - 1));
        plusBtn.addEventListener('click', () => setQty(Number(qtyInput.value) + 1));
    });

    paymentTypeSelect.addEventListener('change', refreshSummary);
    document.querySelectorAll('input[name="payment_method"]').forEach((input) => {
        input.addEventListener('change', refreshSummary);
    });
    if (qrisMethodCard) {
        qrisMethodCard.addEventListener('click', (event) => { event.preventDefault(); handleQrisCardClick(); });
        qrisMethodCard.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter' && event.key !== ' ') return;
            event.preventDefault(); handleQrisCardClick();
        });
    }
    refreshSummary();
</script>
@endpush
