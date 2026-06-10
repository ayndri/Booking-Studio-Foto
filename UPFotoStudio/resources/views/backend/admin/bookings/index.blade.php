@extends('layouts.dashboard')

@section('title', 'Booking - Admin')

@section('content')
<h1 class="h4 mb-3">Data Booking</h1>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['PENDING_PAYMENT', 'CONFIRMED', 'CANCELLED', 'COMPLETED'] as $status)
                        <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="booking_date" class="form-control" value="{{ $selectedDate }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Guest</th>
                <th>Studio</th>
                <th>Jadwal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Transaksi</th>
                <th width="220">Update Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->booking_code }}</td>
                    <td>{{ $booking->guest->full_name }}</td>
                    <td>{{ $booking->studio->name }}</td>
                    <td>{{ $booking->booking_date->format('d-m-Y') }}<br><small>{{ $booking->start_time }} - {{ $booking->end_time }}</small></td>
                    <td>Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                    <td><span class="badge text-bg-secondary">{{ $booking->status }}</span></td>
                    <td>{{ $booking->paymentTransaction->status ?? '-' }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.bookings.update-status', $booking) }}" class="d-flex gap-1">
                            @csrf
                            @method('patch')
                            <select name="status" class="form-select form-select-sm">
                                @foreach(['PENDING_PAYMENT', 'CONFIRMED', 'CANCELLED', 'COMPLETED'] as $status)
                                    <option value="{{ $status }}" @selected($booking->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary" type="submit">Simpan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Belum ada data booking.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $bookings->links() }}</div>
@endsection
