@extends('layouts.dashboard')

@section('title', $sectionMeta['addLabel'])

@section('content')
@php
    $contentRouteParams = ['section' => $section];
    if (!empty($homeMenu)) {
        $contentRouteParams['home_menu'] = $homeMenu;
    }
@endphp
<h1 class="h4 mb-3">{{ $sectionMeta['addLabel'] }}</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="post" action="{{ route('admin.contents.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="section" value="{{ $section }}">
            @if(!empty($homeMenu))
                <input type="hidden" name="home_menu" value="{{ $homeMenu }}">
            @endif

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-3">
                    <tbody>
                    @if($requiresTypeSelection)
                        <tr>
                            <th style="width: 220px;">Jenis Konten</th>
                            <td>
                                <select name="content_type" class="form-select" required>
                                    <option value="">Pilih jenis konten</option>
                                    @foreach($contentTypes as $type)
                                        <option value="{{ $type['value'] }}" @selected(old('content_type') === $type['value'])>
                                            {{ $type['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <th style="width: 220px;">Jenis Konten</th>
                            <td>
                                <input type="hidden" name="content_type" value="{{ $defaultContentType }}">
                                <span class="badge text-bg-secondary">
                                    {{ $contentTypes[0]['label'] ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th>Judul</th>
                        <td>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </td>
                    </tr>
                    <tr>
                        <th>Teks</th>
                        <td>
                            <textarea name="text_content" rows="5" class="form-control">{{ old('text_content') }}</textarea>
                            <small class="text-muted">Isi teks/deskripsi konten. Tidak perlu format JSON.</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Gambar</th>
                        <td>
                            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                            <small class="text-muted">Opsional (kecuali slide promo/item galeri). Format JPG/PNG/WEBP maks 4MB.</small>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('admin.contents.index', $contentRouteParams) }}" class="btn btn-outline-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
