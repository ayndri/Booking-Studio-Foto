@extends('layouts.dashboard')

@section('title', 'Paket Layanan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Paket Layanan</h1>
    <a href="{{ route('admin.service-packages.create') }}" class="btn btn-primary btn-sm">Tambah Paket</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Studio</th>
                <th>Gambar</th>
                <th>Nama Paket</th>
                <th>Durasi</th>
                <th>Harga</th>
                <th>Status</th>
                <th width="180">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($packages as $package)
                <tr>
                    <td>{{ $package->studio->name }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $package->image_url }}" alt="{{ $package->name }}" style="width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid #dee2e6;">
                            <small class="text-muted">{{ $package->image_path ? 'Custom' : 'Default' }}</small>
                        </div>
                    </td>
                    <td>{{ $package->name }}</td>
                    <td>{{ $package->duration_minutes }} menit</td>
                    <td>Rp{{ number_format($package->price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $package->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                            {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.service-packages.edit', $package) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form method="post" action="{{ route('admin.service-packages.destroy', $package) }}" class="d-inline" onsubmit="return confirm('Hapus paket ini?')">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">Belum ada paket layanan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $packages->links() }}</div>
@endsection
