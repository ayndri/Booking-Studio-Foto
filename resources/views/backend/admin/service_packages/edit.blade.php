@extends('layouts.dashboard')
@section('title', 'Edit Paket Layanan')
@section('content')

<div class="page-header">
    <h1 class="page-title">📦 Edit Paket Layanan</h1>
    <a href="{{ route('admin.service-packages.index') }}" class="btn-back">← Kembali</a>
</div>

<div class="d-form" style="max-width:760px">
    <form method="post" action="{{ route('admin.service-packages.update', $servicePackage) }}" enctype="multipart/form-data">
        @csrf @method('put')

        <div class="d-row2">
            <div class="d-field">
                <label class="d-label">Studio <span style="color:#dc2626">*</span></label>
                <select name="studio_id" class="d-select" required>
                    <option value="">— Pilih Studio —</option>
                    @foreach($studios as $studio)
                        <option value="{{ $studio->id }}" @selected(old('studio_id',$servicePackage->studio_id)==$studio->id)>{{ $studio->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-field">
                <label class="d-label">Nama Paket <span style="color:#dc2626">*</span></label>
                <input type="text" name="name" class="d-input" value="{{ old('name',$servicePackage->name) }}" required>
            </div>
        </div>

        <div class="d-row3">
            <div class="d-field">
                <label class="d-label">Harga (Rp) <span style="color:#dc2626">*</span></label>
                <input type="number" name="price" class="d-input" value="{{ old('price',$servicePackage->price) }}" required>
            </div>
            <div class="d-field">
                <label class="d-label">Durasi (menit) <span style="color:#dc2626">*</span></label>
                <input type="number" name="duration_minutes" class="d-input" value="{{ old('duration_minutes',$servicePackage->duration_minutes) }}" required>
            </div>
            <div class="d-field" style="display:flex;align-items:flex-end;padding-bottom:4px">
                <label class="d-check">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active',$servicePackage->is_active))>
                    <span>Paket aktif</span>
                </label>
            </div>
        </div>

        <div class="d-field">
            <label class="d-label">Gambar Paket</label>
            <input type="file" name="image" class="d-input" accept=".jpg,.jpeg,.png,.webp" style="padding:7px 13px">
            <p class="d-hint">Biarkan kosong jika tidak ingin mengganti gambar.</p>

            @if($servicePackage->image_path)
                <div style="display:flex;align-items:center;gap:16px;margin-top:12px;padding:12px;background:#fafaf8;border:1px solid rgba(0,0,0,.07);border-radius:10px">
                    <img src="{{ $servicePackage->image_url }}" alt="{{ $servicePackage->name }}"
                         style="width:72px;height:72px;object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.08)">
                    <label class="d-check">
                        <input type="checkbox" name="remove_image" id="remove_image" value="1" @checked(old('remove_image'))>
                        <span style="color:#be123c">Hapus gambar saat update</span>
                    </label>
                </div>
            @endif
        </div>

        <div class="d-field">
            <label class="d-label">Deskripsi</label>
            <textarea name="description" class="d-textarea">{{ old('description',$servicePackage->description) }}</textarea>
        </div>

        <div style="display:flex;gap:10px;margin-top:4px">
            <button type="submit" class="btn-g">Update Paket</button>
            <a href="{{ route('admin.service-packages.index') }}" class="btn-back">Batal</a>
        </div>
    </form>
</div>
@endsection
