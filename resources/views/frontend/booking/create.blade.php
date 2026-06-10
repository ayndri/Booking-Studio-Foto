@extends('layouts.frontend')

@section('title', 'Form Booking - UPFotoStudio')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Form Booking Studio</h1>
                <form method="post" action="{{ route('frontend.booking.store') }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="guest_name" class="form-control" value="{{ old('guest_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="guest_email" class="form-control" value="{{ old('guest_email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="guest_phone" class="form-control" value="{{ old('guest_phone') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Studio</label>
                        <select name="studio_id" id="studio_id" class="form-select" required>
                            <option value="">Pilih Studio</option>
                            @foreach($studios as $studio)
                                <option value="{{ $studio->id }}" @selected(old('studio_id', $selectedStudioId) == $studio->id)>
                                    {{ $studio->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Booking</label>
                        <input type="date" name="booking_date" class="form-control" min="{{ now()->toDateString() }}" value="{{ old('booking_date') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Paket Layanan</label>
                        <select name="service_package_id" id="service_package_id" class="form-select" required>
                            <option value="">Pilih Paket</option>
                            @foreach($packages as $package)
                                <option
                                    value="{{ $package->id }}"
                                    data-studio-id="{{ $package->studio_id }}"
                                    data-duration="{{ $package->duration_minutes }}"
                                    data-price="{{ $package->price }}"
                                    @selected(old('service_package_id') == $package->id)
                                >
                                    {{ $package->name }} - Rp{{ number_format($package->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Biaya Add-on (opsional)</label>
                        <input type="number" name="add_on_amount" id="add_on_amount" class="form-control" min="0" value="{{ old('add_on_amount', 0) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_type" id="payment_type" class="form-select" required>
                            <option value="DP" @selected(old('payment_type') === 'DP')>DP</option>
                            <option value="LUNAS" @selected(old('payment_type') === 'LUNAS')>LUNAS</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Catatan</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Buat Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5>Ringkasan Otomatis</h5>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Durasi Paket</span>
                        <strong id="summary_duration">-</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Jam Selesai</span>
                        <strong id="summary_end_time">-</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Total Biaya</span>
                        <strong id="summary_total">Rp0</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Nominal Bayar</span>
                        <strong id="summary_pay">Rp0</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const studioSelect = document.getElementById('studio_id');
    const packageSelect = document.getElementById('service_package_id');
    const startTimeInput = document.getElementById('start_time');
    const addOnInput = document.getElementById('add_on_amount');
    const paymentTypeSelect = document.getElementById('payment_type');

    const summaryDuration = document.getElementById('summary_duration');
    const summaryEndTime = document.getElementById('summary_end_time');
    const summaryTotal = document.getElementById('summary_total');
    const summaryPay = document.getElementById('summary_pay');

    const packageUrlTemplate = "{{ route('frontend.booking.packages-by-studio', ['studio' => '__STUDIO__']) }}";

    function formatRupiah(number) {
        return 'Rp' + Number(number || 0).toLocaleString('id-ID');
    }

    function calculateSummary() {
        const selectedOption = packageSelect.options[packageSelect.selectedIndex];
        const duration = Number(selectedOption?.dataset.duration || 0);
        const price = Number(selectedOption?.dataset.price || 0);
        const addOn = Number(addOnInput.value || 0);
        const paymentType = paymentTypeSelect.value;

        const total = price + addOn;
        const dpAmount = Math.max(Math.round(total * 0.3), 50000);
        const payAmount = paymentType === 'DP' ? dpAmount : total;

        summaryDuration.textContent = duration ? duration + ' menit' : '-';
        summaryTotal.textContent = formatRupiah(total);
        summaryPay.textContent = formatRupiah(payAmount);

        const startTime = startTimeInput.value;
        if (duration && startTime) {
            const [hour, minute] = startTime.split(':').map(Number);
            const startDate = new Date();
            startDate.setHours(hour, minute, 0, 0);
            startDate.setMinutes(startDate.getMinutes() + duration);
            const endHour = String(startDate.getHours()).padStart(2, '0');
            const endMinute = String(startDate.getMinutes()).padStart(2, '0');
            summaryEndTime.textContent = endHour + ':' + endMinute;
        } else {
            summaryEndTime.textContent = '-';
        }
    }

    async function loadPackagesByStudio() {
        const studioId = studioSelect.value;
        if (!studioId) {
            packageSelect.innerHTML = '<option value="">Pilih Paket</option>';
            calculateSummary();
            return;
        }

        const endpoint = packageUrlTemplate.replace('__STUDIO__', studioId);
        const response = await fetch(endpoint);
        const data = await response.json();

        packageSelect.innerHTML = '<option value="">Pilih Paket</option>';
        data.packages.forEach((pkg) => {
            const option = document.createElement('option');
            option.value = pkg.id;
            option.textContent = `${pkg.name} - ${formatRupiah(pkg.price)}`;
            option.dataset.duration = pkg.duration_minutes;
            option.dataset.price = pkg.price;
            packageSelect.appendChild(option);
        });

        calculateSummary();
    }

    studioSelect.addEventListener('change', loadPackagesByStudio);
    packageSelect.addEventListener('change', calculateSummary);
    startTimeInput.addEventListener('change', calculateSummary);
    addOnInput.addEventListener('input', calculateSummary);
    paymentTypeSelect.addEventListener('change', calculateSummary);

    calculateSummary();

    if (studioSelect.value) {
        loadPackagesByStudio();
    }
</script>
@endpush
