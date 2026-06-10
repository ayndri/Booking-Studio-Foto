@extends('layouts.frontend')

@section('title', 'Detail Paket - UPFotoStudio')

@push('styles')
<style>
    .package-detail-image {
        width: 100%;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        height: 430px;
        object-fit: cover;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 8px;
    }

    .calendar-cell {
        text-align: center;
        border-radius: 10px;
        padding: 10px 4px;
        border: 1px solid transparent;
        text-decoration: none;
        display: block;
        font-weight: 600;
        color: #0f172a;
    }

    .calendar-cell.muted {
        color: #94a3b8;
    }

    .calendar-cell.past {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .calendar-cell.active {
        background: #355c9f;
        color: #fff;
    }

    .slot-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .slot-btn {
        text-align: center;
        border-radius: 10px;
        padding: 10px;
        border: 1px solid #cbd5e1;
        text-decoration: none;
        color: #334155;
        font-weight: 700;
        background: #fff;
    }

    .slot-btn.unavailable {
        background: #cbd5e1;
        border-color: #cbd5e1;
        color: #64748b;
        pointer-events: none;
    }

    .slot-btn.active {
        background: #355c9f;
        color: #fff;
        border-color: #355c9f;
    }

    .summary-box {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #fff;
        padding: 18px;
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Detail Paket</h1>
    <a href="{{ route('frontend.pricing', ['studio_id' => $servicePackage->studio_id]) }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <img src="{{ $packageImage }}" alt="{{ $servicePackage->name }}" class="package-detail-image mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h4 mb-3">{{ $servicePackage->name }}</h2>
                <div class="text-secondary">{{ $servicePackage->studio->name }}</div>
                <div class="mt-3">
                    <div>• {{ $peopleLabel }}</div>
                    <div>• {{ $servicePackage->duration_minutes }} mins photo session</div>
                    @foreach($benefits as $benefit)
                        <div>• {{ $benefit }}</div>
                    @endforeach
                </div>
                <div class="alert alert-info mt-3 mb-0 small">
                    Harga paket bersifat flat sesuai detail layanan yang dipilih.
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Pilih tanggal dan waktu</h2>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('frontend.booking.package-detail', ['servicePackage' => $servicePackage->id, 'month' => $prevMonth, 'date' => \Carbon\Carbon::createFromFormat('Y-m', $prevMonth)->startOfMonth()->toDateString()]) }}" class="btn btn-sm btn-outline-secondary">&lsaquo;</a>
                    <strong>{{ $monthCursor->translatedFormat('F Y') }}</strong>
                    <a href="{{ route('frontend.booking.package-detail', ['servicePackage' => $servicePackage->id, 'month' => $nextMonth, 'date' => \Carbon\Carbon::createFromFormat('Y-m', $nextMonth)->startOfMonth()->toDateString()]) }}" class="btn btn-sm btn-outline-secondary">&rsaquo;</a>
                </div>

                <div class="calendar-grid mb-2 text-muted small fw-semibold">
                    <div class="text-center">Sen</div>
                    <div class="text-center">Sel</div>
                    <div class="text-center">Rab</div>
                    <div class="text-center">Kam</div>
                    <div class="text-center">Jum</div>
                    <div class="text-center">Sab</div>
                    <div class="text-center">Min</div>
                </div>

                @foreach($calendarWeeks as $week)
                    <div class="calendar-grid mb-1">
                        @foreach($week as $day)
                            @if($day['is_past'])
                                <span class="calendar-cell past">{{ $day['day'] }}</span>
                            @else
                                <a href="{{ route('frontend.booking.package-detail', ['servicePackage' => $servicePackage->id, 'month' => $monthCursor->format('Y-m'), 'date' => $day['date']]) }}"
                                   class="calendar-cell {{ !$day['is_current_month'] ? 'muted' : '' }} {{ $day['is_selected'] ? 'active' : '' }}">
                                    {{ $day['day'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endforeach

                <div class="slot-grid mt-4">
                    @foreach($availableSlots as $slot)
                        <a href="{{ route('frontend.booking.package-detail', ['servicePackage' => $servicePackage->id, 'month' => $monthCursor->format('Y-m'), 'date' => $selectedDate, 'time' => $slot['time']]) }}"
                           class="slot-btn {{ !$slot['available'] ? 'unavailable' : '' }} {{ $selectedTime === $slot['time'] ? 'active' : '' }}">
                            {{ $slot['time'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="summary-box sticky-top" style="top: 86px;">
            <div class="text-secondary small mb-2">Tanggal dan waktu yang dipilih</div>
            <div class="fw-semibold mb-3">
                {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('j F Y') }}
                {{ $selectedTime ? '| ' . $selectedTime : '' }}
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-secondary">Harga paket</span>
                <strong>Rp {{ number_format($servicePackage->price, 0, ',', '.') }}</strong>
            </div>

            @if($selectedTime)
                <form method="get" action="{{ route('frontend.booking.order') }}">
                    <input type="hidden" name="package_id" value="{{ $servicePackage->id }}">
                    <input type="hidden" name="booking_date" value="{{ $selectedDate }}">
                    <input type="hidden" name="start_time" value="{{ $selectedTime }}">
                    <button type="submit" class="btn btn-primary w-100">Selanjutnya</button>
                </form>
            @else
                <button class="btn btn-secondary w-100" type="button" disabled>Pilih jam terlebih dahulu</button>
            @endif
        </div>
    </div>
</div>
@endsection
