@extends('layouts.frontend')

@section('title', 'Order Detail - UPFotoStudio')

@push('styles')
<style>
    .order-panel {
        border: 0;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        background: #fff;
    }

    .addon-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        height: 100%;
        background: #fff;
        display: flex;
        flex-direction: column;
    }

    .addon-header {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .addon-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #fbe9de;
        color: #c25c27;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .addon-name {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .addon-price-tag {
        color: #64748b;
        font-size: 1.45rem;
    }

    .addon-desc {
        color: #475569;
        font-size: 1.4rem;
        line-height: 1.45;
        margin-top: 16px;
        margin-bottom: 18px;
    }

    .addon-footer {
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .addon-subtotal {
        font-size: 1.55rem;
        font-weight: 700;
        color: #0f172a;
    }

    .qty-control {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 3px 8px;
        background: #fff;
    }

    .qty-control button {
        width: 28px;
        height: 28px;
        border: 0;
        border-radius: 50%;
        background: #355c9f;
        color: #fff;
        font-weight: 700;
        line-height: 1;
    }

    .qty-control .minus {
        background: #cbd5e1;
        color: #475569;
    }

    .qty-control .addon-qty {
        min-width: 16px;
        text-align: center;
    }

    .payment-method-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .payment-option {
        position: relative;
    }

    .payment-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .payment-card {
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        cursor: pointer;
        user-select: none;
        transition: all .15s ease;
    }

    .payment-card .logo {
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: 1px;
        line-height: 1;
    }

    .payment-card .label {
        font-size: .95rem;
        margin-top: 6px;
        font-weight: 700;
    }

    .payment-card .hint {
        margin-top: 6px;
        font-size: .78rem;
        color: #64748b;
    }

    .payment-card:focus-visible {
        outline: 3px solid rgba(53, 92, 159, 0.25);
        outline-offset: 2px;
    }

    .payment-option input:checked + .payment-card {
        border-color: #355c9f;
        background: #eaf1ff;
        color: #1e3a8a;
    }

    .sticky-summary {
        position: sticky;
        top: 86px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    @media (max-width: 991.98px) {
        .sticky-summary {
            position: static;
        }
    }

    @media (max-width: 575.98px) {
        .payment-method-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Order Detail</h1>
    <a href="{{ route('frontend.booking.package-detail', ['servicePackage' => $servicePackage->id, 'date' => $bookingDate, 'time' => $startTime]) }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
</div>

<form method="post" action="{{ route('frontend.booking.store') }}" id="orderForm">
    @csrf

    <input type="hidden" name="service_package_id" value="{{ $servicePackage->id }}">
    <input type="hidden" name="studio_id" value="{{ $servicePackage->studio_id }}">
    <input type="hidden" name="booking_date" value="{{ $bookingDate }}">
    <input type="hidden" name="start_time" value="{{ $startTime }}">
    <input type="hidden" name="add_on_amount" id="add_on_amount" value="0">

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="alert alert-info small mb-3">
                Ini halaman terakhir. Pastikan detail pesanan Anda sudah benar sebelum lanjut bayar.
            </div>

            <div class="order-panel p-3 p-lg-4 mb-3">
                <h2 class="h5 mb-3">Data Pemesan</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="guest_name" class="form-control" value="{{ old('guest_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="guest_phone" class="form-control" value="{{ old('guest_phone') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="guest_email" class="form-control" value="{{ old('guest_email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Pembayaran</label>
                        <select name="payment_type" id="payment_type" class="form-select" required>
                            <option value="LUNAS" @selected(old('payment_type', 'LUNAS') === 'LUNAS')>LUNAS</option>
                            <option value="DP" @selected(old('payment_type') === 'DP')>DP (30% / minimal Rp50.000)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="order-panel p-3 mb-4">
                <h2 class="h5 mb-3">Detail Paket</h2>
                <div class="d-flex gap-3 align-items-center">
                    <img src="{{ $packageImage }}" alt="{{ $servicePackage->name }}" style="width: 96px; height: 96px; border-radius: 10px; object-fit: cover;">
                    <div>
                        <div class="h5 mb-1">{{ $servicePackage->name }}</div>
                        <div class="text-secondary">{{ $servicePackage->studio->name }}</div>
                        <div class="text-secondary small">{{ $bookingDateText }} | {{ $startTime }}</div>
                        <div class="small mt-1">{{ $peopleLabel }} • {{ $servicePackage->duration_minutes }} menit</div>
                    </div>
                </div>
            </div>

            <h2 class="h5 mb-3">Add-ons</h2>
            <div class="row g-3 mb-4">
                @foreach($addOnCatalog as $addOn)
                    <div class="col-md-6">
                        <div class="addon-card" data-addon-id="{{ $addOn['id'] }}" data-addon-price="{{ $addOn['price'] }}">
                            <div class="addon-header">
                                <div class="addon-icon">{{ $addOn['icon'] ?? 'AD' }}</div>
                                <div>
                                    <div class="addon-name">{{ $addOn['name'] }}</div>
                                    <div class="addon-price-tag">Rp {{ number_format($addOn['price'], 0, ',', '.') }}{{ $addOn['unit'] }}</div>
                                </div>
                            </div>

                            <div class="addon-desc">{{ $addOn['description'] }}</div>

                            <div class="addon-footer">
                                <div class="addon-subtotal">Rp 0</div>

                                <div class="qty-control">
                                    <button type="button" class="minus">-</button>
                                    <strong class="addon-qty">{{ (int) old('add_ons.' . $addOn['id'] . '.qty', 0) }}</strong>
                                    <button type="button" class="plus">+</button>
                                </div>
                            </div>

                            <input
                                type="hidden"
                                name="add_ons[{{ $addOn['id'] }}][qty]"
                                value="{{ (int) old('add_ons.' . $addOn['id'] . '.qty', 0) }}"
                                class="addon-qty-input"
                            >
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="order-panel p-3 p-lg-4">
                <h2 class="h5 mb-2">Lain-lain</h2>
                <p class="text-secondary mb-3">Kita butuh sedikit info tambahan.</p>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Pilih background yang diinginkan</label>
                        <select name="background_choice" class="form-select" required>
                            <option value="Abu-abu muda" @selected(old('background_choice') === 'Abu-abu muda')>Abu-abu muda</option>
                            <option value="Sky blue" @selected(old('background_choice') === 'Sky blue')>Sky blue</option>
                            <option value="Mocca" @selected(old('background_choice') === 'Mocca')>Mocca</option>
                            <option value="Merah (khusus Identity Photo)" @selected(old('background_choice') === 'Merah (khusus Identity Photo)')>Merah (khusus Identity Photo)</option>
                            <option value="Biru (khusus Identity Photo)" @selected(old('background_choice') === 'Biru (khusus Identity Photo)')>Biru (khusus Identity Photo)</option>
                            <option value="Putih" @selected(old('background_choice') === 'Putih')>Putih</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Apakah boleh foto untuk upload di sosial media?</label>
                        <select name="social_consent" class="form-select" required>
                            <option value="DENY" @selected(old('social_consent', 'DENY') === 'DENY')>Ngga deh</option>
                            <option value="ALLOW" @selected(old('social_consent') === 'ALLOW')>Boleh</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Pilih metode pembayaran</label>
                        <div class="payment-method-grid">
                            <label class="payment-option" for="payment_method_qris">
                                <input id="payment_method_qris" type="radio" name="payment_method" value="QRIS" @checked(old('payment_method') === 'QRIS')>
                                <span class="payment-card" id="payment_card_qris" role="button" tabindex="0" aria-label="Pilih QRIS, klik lagi untuk lanjut ke payment gateway">
                                    <span class="logo">QRIS</span>
                                    <span class="label d-block">QRIS</span>
                                    <small class="hint d-block">Klik logo untuk pilih, klik lagi untuk lanjut bayar.</small>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="order-panel p-3 p-lg-4 sticky-summary">
                <h2 class="h4 mb-3">Detail Pembayaran</h2>

                <div class="summary-row">
                    <span class="text-secondary">Metode Pembayaran</span>
                    <strong id="summary_payment_method">{{ old('payment_method') ?: '-' }}</strong>
                </div>

                <div class="summary-row">
                    <span class="text-secondary">Jenis Pembayaran</span>
                    <strong id="summary_payment_type_label">LUNAS</strong>
                </div>

                <div class="summary-row">
                    <span class="text-secondary" style="max-width: 65%;">{{ $servicePackage->name }}</span>
                    <strong>Rp {{ number_format($servicePackage->price, 0, ',', '.') }}</strong>
                </div>

                <div class="summary-row mb-3">
                    <span class="text-secondary">Add-ons</span>
                    <strong id="summary_addon_total">Rp 0</strong>
                </div>

                <hr>

                <div class="summary-row mb-1">
                    <span class="text-secondary">Total Order</span>
                    <strong id="summary_order_total">Rp {{ number_format($servicePackage->price, 0, ',', '.') }}</strong>
                </div>
                <div class="summary-row mb-3">
                    <span class="text-secondary">Nominal Dibayar Sekarang</span>
                    <strong id="summary_pay_now">Rp {{ number_format($servicePackage->price, 0, ',', '.') }}</strong>
                </div>

                <ul class="small text-secondary ps-3 mb-3">
                    <li>Reschedule hanya bisa dilakukan sekali maksimal 24 jam sebelum sesi.</li>
                    <li>Reschedule H-1 karena permintaan customer dikenakan biaya tambahan.</li>
                    <li>Cancel tidak dapat refund.</li>
                    <li>Dengan membayar, Anda menyetujui seluruh syarat dan ketentuan yang berlaku.</li>
                </ul>

                <button type="submit" class="btn btn-primary btn-lg w-100">Bayar</button>
            </div>
        </div>
    </div>
</form>
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
            return Math.max(Math.round(orderTotal * 0.3), 50000);
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
        if (!qrisMethodInput || !orderForm) {
            return;
        }

        const wasChecked = qrisMethodInput.checked;
        qrisMethodInput.checked = true;
        refreshSummary();

        if (!wasChecked) {
            return;
        }

        if (orderForm.checkValidity()) {
            orderForm.requestSubmit();
            return;
        }

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
        qrisMethodCard.addEventListener('click', (event) => {
            event.preventDefault();
            handleQrisCardClick();
        });

        qrisMethodCard.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter' && event.key !== ' ') {
                return;
            }

            event.preventDefault();
            handleQrisCardClick();
        });
    }

    refreshSummary();
</script>
@endpush
