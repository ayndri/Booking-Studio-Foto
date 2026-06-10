@extends('layouts.dashboard')

@section('title', 'Tambah Paket Layanan')

@section('content')
<h1 class="h4 mb-3">Tambah Paket Layanan</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="post" action="{{ route('admin.service-packages.store') }}" class="row g-3" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Studio</label>
                <select name="studio_id" class="form-select" required>
                    <option value="">Pilih Studio</option>
                    @foreach($studios as $studio)
                        <option value="{{ $studio->id }}" @selected(old('studio_id') == $studio->id)>{{ $studio->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Paket</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Gambar Paket</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                <small class="text-muted">Opsional. Format JPG/PNG/WEBP, maksimal 4 MB.</small>
            </div>
            <div class="col-md-4">
                <label class="form-label">Harga</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Durasi (menit)</label>
                <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes') }}" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <a href="{{ route('admin.service-packages.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
