@extends('layouts.dashboard')
@section('title', 'Studio - Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title">🏢 Data Studio</h1>
    <a href="{{ route('admin.studios.create') }}" class="btn-g">+ Tambah Studio</a>
</div>

<div class="d-card">
    <div class="table-responsive">
        <table class="d-table">
            <thead>
                <tr>
                    <th>Nama Studio</th>
                    <th>Slug</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($studios as $studio)
                <tr>
                    <td style="font-weight:600">{{ $studio->name }}</td>
                    <td><code style="font-size:.78rem;color:#888;background:#f4f3f0;padding:2px 6px;border-radius:4px">{{ $studio->slug }}</code></td>
                    <td style="color:#666">{{ $studio->location }}</td>
                    <td>
                        <span class="dbadge {{ $studio->is_active ? 'dbadge-active' : 'dbadge-inactive' }}">
                            {{ $studio->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.studios.edit', $studio) }}" class="btn-edit">Edit</a>
                            <form method="post" action="{{ route('admin.studios.destroy', $studio) }}" class="d-inline" onsubmit="return confirm('Hapus studio ini?')">
                                @csrf @method('delete')
                                <button class="btn-del" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:#bbb;padding:32px">Belum ada data studio.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $studios->links() }}</div>
@endsection
