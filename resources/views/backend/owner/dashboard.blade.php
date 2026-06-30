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

<div class="row g-3 mt-1">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">📊 Pendapatan per Bulan (6 Bulan Terakhir)</h6>
                <div style="position:relative;height:320px;"><canvas id="monthlyRevenueChart"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">🏢 Pendapatan per Studio</h6>
                <div style="position:relative;height:320px;"><canvas id="studioRevenueChart"></canvas></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function () {
    const rupiah = (value) => 'Rp' + Number(value).toLocaleString('id-ID');
    const palette = ['#2f5443', '#0d9488', '#f59e0b', '#2563eb', '#7c3aed', '#dc2626', '#16a34a', '#d97706'];

    // Bar pendapatan per bulan dengan gradien.
    const barCtx = document.getElementById('monthlyRevenueChart');
    if (barCtx) {
        const gradient = barCtx.getContext('2d').createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(47,84,67,.95)');
        gradient.addColorStop(1, 'rgba(13,148,136,.55)');

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($monthlyData),
                    backgroundColor: gradient,
                    borderRadius: 10,
                    maxBarThickness: 48,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (item) => rupiah(item.parsed.y) } },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: (value) => rupiah(value) } },
                    x: { grid: { display: false } },
                },
            },
        });
    }

    // Donut pendapatan per studio.
    const donutCtx = document.getElementById('studioRevenueChart');
    if (donutCtx) {
        const labels = @json($studioRevenueLabels);
        const data = @json($studioRevenueData);

        if (data.length && data.some((value) => value > 0)) {
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: palette,
                        borderWidth: 2,
                        borderColor: '#fff',
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } },
                        tooltip: { callbacks: { label: (item) => item.label + ': ' + rupiah(item.parsed) } },
                    },
                },
            });
        } else {
            donutCtx.parentElement.innerHTML =
                '<div class="d-flex align-items-center justify-content-center h-100 text-muted small">Belum ada pendapatan.</div>';
        }
    }
})();
</script>
@endpush
