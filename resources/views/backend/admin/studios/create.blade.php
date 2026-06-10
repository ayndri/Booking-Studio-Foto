@extends('layouts.dashboard')
@section('title', 'Tambah Studio')
@section('content')

<div class="page-header">
    <h1 class="page-title">🏢 Tambah Studio</h1>
    <a href="{{ route('admin.studios.index') }}" class="btn-back">← Kembali</a>
</div>

<div class="d-form" style="max-width:720px">
    <form method="post" action="{{ route('admin.studios.store') }}">
        @csrf

        <div class="d-row2">
            <div class="d-field">
                <label class="d-label">Nama Studio <span style="color:#dc2626">*</span></label>
                <input type="text" name="name" class="d-input" value="{{ old('name') }}" placeholder="e.g. Studio Foto Grup" required>
            </div>
            <div class="d-field">
                <label class="d-label">Slug <span style="color:#dc2626">*</span></label>
                <input type="text" name="slug" class="d-input" value="{{ old('slug') }}" placeholder="e.g. studio-foto-grup" required>
                <p class="d-hint">Gunakan huruf kecil dan tanda hubung.</p>
            </div>
        </div>

        <div class="d-row2">
            <div class="d-field">
                <label class="d-label">Lokasi</label>
                <input type="text" name="location" class="d-input" value="{{ old('location') }}" placeholder="e.g. Lantai 2, UPFotoStudio">
            </div>
            <div class="d-field" style="display:flex;align-items:flex-end;padding-bottom:4px">
                <label class="d-check">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span>Studio aktif dan bisa dibooking</span>
                </label>
            </div>
        </div>

        <div class="d-field">
            <label class="d-label">Deskripsi</label>
            <textarea name="description" class="d-textarea" placeholder="Deskripsi singkat tentang studio ini...">{{ old('description') }}</textarea>
        </div>

        <div style="display:flex;gap:10px;margin-top:4px">
            <button type="submit" class="btn-g">Simpan Studio</button>
            <a href="{{ route('admin.studios.index') }}" class="btn-back">Batal</a>
        </div>
    </form>
</div>
@endsection
