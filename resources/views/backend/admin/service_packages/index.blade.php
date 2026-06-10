@extends('layouts.dashboard')
@section('title', 'Paket Layanan - Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title">📦 Paket Layanan</h1>
    <a href="{{ route('admin.service-packages.create') }}" class="btn-g">+ Tambah Paket</a>
</div>

<div class="d-card">
    <div class="table-responsive">
        <table class="d-table">
            <thead>
                <tr>
                    <th>Studio</th>
                    <th>Foto</th>
                    <th>Nama Paket</th>
                    <th>Durasi</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($packages as $package)
                <tr>
                    <td>
                        <span style="font-size:.72rem;font-weight:600;color:#2f5443;background:#eef7f2;padding:3px 9px;border-radius:999px;white-space:nowrap">
                            {{ $package->studio->name }}
                        </span>
                    </td>
                    <td>
                        <img src="{{ $package->image_url }}" alt="{{ $package->name }}"
                             style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.08)">
                    </td>
                    <td style="font-weight:600">{{ $package->name }}</td>
                    <td style="color:#888;white-space:nowrap">{{ $package->duration_minutes }} menit</td>
                    <td style="font-family:'Playfair Display',serif;font-weight:700;color:#2f5443">
                        Rp{{ number_format($package->price,0,',','.') }}
                    </td>
                    <td>
                        <span class="dbadge {{ $package->is_active ? 'dbadge-active' : 'dbadge-inactive' }}">
                            {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.service-packages.edit', $package) }}" class="btn-edit">Edit</a>
                            <form method="post" action="{{ route('admin.service-packages.destroy', $package) }}" class="d-inline" onsubmit="return confirm('Hapus paket ini?')">
                                @csrf @method('delete')
                                <button class="btn-del" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#bbb;padding:32px">Belum ada paket layanan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $packages->links() }}</div>
@endsection
