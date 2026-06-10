@extends('layouts.dashboard')

@section('title', 'Tambah Studio')

@section('content')
<h1 class="h4 mb-3">Tambah Studio</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="post" action="{{ route('admin.studios.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Nama Studio</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location" class="form-control" value="{{ old('location') }}">
            </div>
            <div class="col-md-6 d-flex align-items-end">
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
                <a href="{{ route('admin.studios.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
