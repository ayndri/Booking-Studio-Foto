@extends('layouts.dashboard')
@section('title', 'Edit ' . $sectionMeta['title'])
@section('content')
@php
    $contentRouteParams = ['section' => $section];
    if (!empty($homeMenu)) { $contentRouteParams['home_menu'] = $homeMenu; }
@endphp

<div class="page-header">
    <h1 class="page-title">✏️ Edit {{ $sectionMeta['title'] }}</h1>
    <a href="{{ route('admin.contents.index', $contentRouteParams) }}" class="btn-back">← Kembali</a>
</div>

<div class="d-form" style="max-width:680px">
    <form method="post" action="{{ route('admin.contents.update', $content) }}" enctype="multipart/form-data">
        @csrf @method('put')
        <input type="hidden" name="section" value="{{ $section }}">
        @if(!empty($homeMenu))<input type="hidden" name="home_menu" value="{{ $homeMenu }}">@endif

        <div class="d-field">
            <label class="d-label">Jenis Konten</label>
            <div style="font-size:.86rem;background:#eef7f2;color:#2f5443;padding:8px 13px;border-radius:9px;font-weight:600;display:inline-block">
                {{ $contentLabel }}
            </div>
        </div>

        <div class="d-field">
            <label class="d-label">Judul <span style="color:#dc2626">*</span></label>
            <input type="text" name="title" class="d-input" value="{{ old('title', $content->title) }}" required>
        </div>

        <div class="d-field">
            <label class="d-label">Teks / Deskripsi</label>
            <textarea name="text_content" class="d-textarea" rows="5">{{ old('text_content', $textValue) }}</textarea>
            <p class="d-hint">Isi teks/deskripsi konten. Tidak perlu format JSON.</p>
        </div>

        <div class="d-field">
            <label class="d-label">Gambar</label>
            <input type="file" name="image" class="d-input" accept=".jpg,.jpeg,.png,.webp" style="padding:7px 13px">

            @if($imageUrl)
                <div style="display:flex;align-items:center;gap:16px;margin-top:12px;padding:12px;background:#fafaf8;border:1px solid rgba(0,0,0,.07);border-radius:10px">
                    <img src="{{ $imageUrl }}" alt="{{ $content->title }}"
                         style="width:180px;height:96px;object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.08)">
                    <label class="d-check">
                        <input type="checkbox" name="remove_image" id="remove_image" value="1" @checked(old('remove_image'))>
                        <span style="color:#be123c">Hapus gambar saat update</span>
                    </label>
                </div>
            @endif
            <p class="d-hint" style="margin-top:8px">Format JPG/PNG/WEBP, maksimal 4MB. Biarkan kosong jika tidak ingin mengganti.</p>
        </div>

        <div style="display:flex;gap:10px;margin-top:4px">
            <button type="submit" class="btn-g">Update Konten</button>
            <a href="{{ route('admin.contents.index', $contentRouteParams) }}" class="btn-back">Batal</a>
        </div>
    </form>
</div>
@endsection
