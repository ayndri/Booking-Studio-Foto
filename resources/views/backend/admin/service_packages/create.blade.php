@extends('layouts.dashboard')
@section('title', 'Tambah Paket Layanan')
@section('content')

<div class="page-header">
    <h1 class="page-title">📦 Tambah Paket Layanan</h1>
    <a href="{{ route('admin.service-packages.index') }}" class="btn-back">← Kembali</a>
</div>

<div class="d-form" style="max-width:760px">
    <form method="post" action="{{ route('admin.service-packages.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="d-row2">
            <div class="d-field">
                <label class="d-label">Studio <span style="color:#dc2626">*</span></label>
                <select name="studio_id" class="d-select" required>
                    <option value="">— Pilih Studio —</option>
                    @foreach($studios as $studio)
                        <option value="{{ $studio->id }}" @selected(old('studio_id')==$studio->id)>{{ $studio->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-field">
                <label class="d-label">Nama Paket <span style="color:#dc2626">*</span></label>
                <input type="text" name="name" class="d-input" value="{{ old('name') }}" placeholder="e.g. Family, Headshot, Garden" required>
            </div>
        </div>

        <div class="d-row3">
            <div class="d-field">
                <label class="d-label">Harga (Rp) <span style="color:#dc2626">*</span></label>
                <input type="number" name="price" class="d-input" value="{{ old('price') }}" placeholder="300000" required>
            </div>
            <div class="d-field">
                <label class="d-label">Durasi (menit) <span style="color:#dc2626">*</span></label>
                <input type="number" name="duration_minutes" class="d-input" value="{{ old('duration_minutes') }}" placeholder="60" required>
            </div>
            <div class="d-field" style="display:flex;align-items:flex-end;padding-bottom:4px">
                <label class="d-check">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span>Paket aktif</span>
                </label>
            </div>
        </div>

        <div class="d-field">
            <label class="d-label">Gambar Paket</label>
            <input type="file" name="image" class="d-input" accept=".jpg,.jpeg,.png,.webp" style="padding:7px 13px">
            <p class="d-hint">Opsional. Format JPG/PNG/WEBP, maksimal 4 MB.</p>
        </div>

        <div class="d-field">
            <label class="d-label">Deskripsi</label>
            <textarea name="description" class="d-textarea" placeholder="Deskripsi singkat tentang paket ini...">{{ old('description') }}</textarea>
        </div>

        <div style="display:flex;gap:10px;margin-top:4px">
            <button type="submit" class="btn-g">Simpan Paket</button>
            <a href="{{ route('admin.service-packages.index') }}" class="btn-back">Batal</a>
        </div>
    </form>
</div>
@endsection
