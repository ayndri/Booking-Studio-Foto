@extends('layouts.dashboard')

@section('title', 'Studio - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Data Studio</h1>
    <a href="{{ route('admin.studios.create') }}" class="btn btn-primary btn-sm">Tambah Studio</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Nama</th>
                <th>Slug</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th width="180">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($studios as $studio)
                <tr>
                    <td>{{ $studio->name }}</td>
                    <td>{{ $studio->slug }}</td>
                    <td>{{ $studio->location }}</td>
                    <td>
                        <span class="badge {{ $studio->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                            {{ $studio->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.studios.edit', $studio) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form method="post" action="{{ route('admin.studios.destroy', $studio) }}" class="d-inline" onsubmit="return confirm('Hapus studio ini?')">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Belum ada data studio.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $studios->links() }}</div>
@endsection
