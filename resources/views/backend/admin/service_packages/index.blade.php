@extends('layouts.dashboard')
@section('title', 'Paket Layanan - Admin')

@section('content')
@php
    // Bangun URL sort: toggle arah bila kolom aktif, reset ke halaman 1.
    $sortLink = function ($col) use ($sort, $dir) {
        $newDir = ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
        return request()->fullUrlWithQuery(['sort' => $col, 'dir' => $newDir, 'page' => null]);
    };
    $arrow = fn ($col) => $sort === $col ? ($dir === 'asc' ? ' ▲' : ' ▼') : '';
@endphp

@push('styles')
<style>
    .th-sort a { color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 2px; }
    .th-sort a:hover { color: #2f5443; }
    .search-bar { display: flex; gap: 8px; align-items: center; margin-bottom: 16px; flex-wrap: wrap; }
    .search-bar input[type=text] {
        padding: 8px 12px; border: 1px solid rgba(0,0,0,.15); border-radius: 8px;
        font-size: .88rem; min-width: 260px; outline: none;
    }
    .search-bar input[type=text]:focus { border-color: #2f5443; }
    .search-bar .btn-reset { font-size: .82rem; color: #888; text-decoration: none; }
    .search-bar .btn-reset:hover { color: #dc2626; }
</style>
@endpush

<div class="page-header">
    <h1 class="page-title">📦 Paket Layanan</h1>
    <a href="{{ route('admin.service-packages.create') }}" class="btn-g">+ Tambah Paket</a>
</div>

<form method="GET" class="search-bar">
    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama paket atau studio...">
    <input type="hidden" name="sort" value="{{ $sort }}">
    <input type="hidden" name="dir" value="{{ $dir }}">
    <button type="submit" class="btn-g">Cari</button>
    @if($search !== '')
        <a href="{{ route('admin.service-packages.index') }}" class="btn-reset">✕ Reset</a>
    @endif
</form>

<div class="d-card">
    <div class="table-responsive">
        <table class="d-table">
            <thead>
                <tr>
                    <th>Studio</th>
                    <th>Foto</th>
                    <th class="th-sort"><a href="{{ $sortLink('name') }}">Nama Paket{{ $arrow('name') }}</a></th>
                    <th class="th-sort"><a href="{{ $sortLink('duration_minutes') }}">Durasi{{ $arrow('duration_minutes') }}</a></th>
                    <th class="th-sort"><a href="{{ $sortLink('price') }}">Harga{{ $arrow('price') }}</a></th>
                    <th class="th-sort"><a href="{{ $sortLink('is_active') }}">Status{{ $arrow('is_active') }}</a></th>
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
                <tr><td colspan="7" style="text-align:center;color:#bbb;padding:32px">
                    {{ $search !== '' ? 'Tidak ada paket yang cocok dengan pencarian.' : 'Belum ada paket layanan.' }}
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $packages->links() }}</div>
@endsection
