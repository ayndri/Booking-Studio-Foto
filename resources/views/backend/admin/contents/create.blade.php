@extends('layouts.dashboard')
@section('title', $sectionMeta['addLabel'])
@section('content')
@php
    $contentRouteParams = ['section' => $section];
    if (!empty($homeMenu)) { $contentRouteParams['home_menu'] = $homeMenu; }
@endphp

<div class="page-header">
    <h1 class="page-title">➕ {{ $sectionMeta['addLabel'] }}</h1>
    <a href="{{ route('admin.contents.index', $contentRouteParams) }}" class="btn-back">← Kembali</a>
</div>

<div class="d-form" style="max-width:680px">
    <form method="post" action="{{ route('admin.contents.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="section" value="{{ $section }}">
        @if(!empty($homeMenu))<input type="hidden" name="home_menu" value="{{ $homeMenu }}">@endif

        @if($requiresTypeSelection)
            <div class="d-field">
                <label class="d-label">Jenis Konten <span style="color:#dc2626">*</span></label>
                <select name="content_type" class="d-select" required>
                    <option value="">— Pilih jenis konten —</option>
                    @foreach($contentTypes as $type)
                        <option value="{{ $type['value'] }}" @selected(old('content_type')===$type['value'])>{{ $type['label'] }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <input type="hidden" name="content_type" value="{{ $defaultContentType }}">
            <div class="d-field">
                <label class="d-label">Jenis Konten</label>
                <div style="font-size:.86rem;background:#eef7f2;color:#2f5443;padding:8px 13px;border-radius:9px;font-weight:600;display:inline-block">
                    {{ $contentTypes[0]['label'] ?? '–' }}
                </div>
            </div>
        @endif

        <div class="d-field">
            <label class="d-label">Judul <span style="color:#dc2626">*</span></label>
            <input type="text" name="title" class="d-input" value="{{ old('title') }}" required>
        </div>

        <div class="d-field">
            <label class="d-label">Teks / Deskripsi</label>
            <textarea name="text_content" class="d-textarea" rows="5">{{ old('text_content') }}</textarea>
            <p class="d-hint">Isi teks/deskripsi konten. Tidak perlu format JSON.</p>
        </div>

        <div class="d-field">
            <label class="d-label">Gambar</label>
            <input type="file" name="image" class="d-input" accept=".jpg,.jpeg,.png,.webp" style="padding:7px 13px">
            <p class="d-hint">Opsional (kecuali slide promo/item galeri). Format JPG/PNG/WEBP, maks 4MB.</p>
        </div>

        <div style="display:flex;gap:10px;margin-top:4px">
            <button type="submit" class="btn-g">Simpan Konten</button>
            <a href="{{ route('admin.contents.index', $contentRouteParams) }}" class="btn-back">Batal</a>
        </div>
    </form>
</div>
@endsection
