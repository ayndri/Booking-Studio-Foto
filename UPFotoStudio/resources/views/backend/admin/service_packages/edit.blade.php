@extends('layouts.dashboard')

@section('title', 'Edit Paket Layanan')

@section('content')
<h1 class="h4 mb-3">Edit Paket Layanan</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="post" action="{{ route('admin.service-packages.update', $servicePackage) }}" class="row g-3" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="col-md-6">
                <label class="form-label">Studio</label>
                <select name="studio_id" class="form-select" required>
                    <option value="">Pilih Studio</option>
                    @foreach($studios as $studio)
                        <option value="{{ $studio->id }}" @selected(old('studio_id', $servicePackage->studio_id) == $studio->id)>{{ $studio->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Paket</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $servicePackage->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Gambar Paket</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>

                @if($servicePackage->image_path)
                    <div class="d-flex align-items-center gap-3 mt-2">
                        <img src="{{ $servicePackage->image_url }}" alt="{{ $servicePackage->name }}" style="width: 84px; height: 84px; object-fit: cover; border-radius: 10px; border: 1px solid #dee2e6;">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1" @checked(old('remove_image'))>
                            <label class="form-check-label" for="remove_image">Hapus gambar saat update</label>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                <label class="form-label">Harga</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $servicePackage->price) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Durasi (menit)</label>
                <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $servicePackage->duration_minutes) }}" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $servicePackage->is_active))>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $servicePackage->description) }}</textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Update</button>
                <a href="{{ route('admin.service-packages.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
