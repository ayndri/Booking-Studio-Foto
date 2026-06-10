@extends('layouts.dashboard')

@section('title', 'Edit ' . $sectionMeta['title'])

@section('content')
@php
    $contentRouteParams = ['section' => $section];
    if (!empty($homeMenu)) {
        $contentRouteParams['home_menu'] = $homeMenu;
    }
@endphp
<h1 class="h4 mb-3">Edit {{ $sectionMeta['title'] }}</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="post" action="{{ route('admin.contents.update', $content) }}" enctype="multipart/form-data">
            @csrf
            @method('put')
            <input type="hidden" name="section" value="{{ $section }}">
            @if(!empty($homeMenu))
                <input type="hidden" name="home_menu" value="{{ $homeMenu }}">
            @endif

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-3">
                    <tbody>
                    <tr>
                        <th style="width: 220px;">Jenis Konten</th>
                        <td>{{ $contentLabel }}</td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $content->title) }}" required>
                        </td>
                    </tr>
                    <tr>
                        <th>Teks</th>
                        <td>
                            <textarea name="text_content" rows="5" class="form-control">{{ old('text_content', $textValue) }}</textarea>
                            <small class="text-muted">Isi teks/deskripsi konten. Tidak perlu format JSON.</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Gambar</th>
                        <td>
                            <input type="file" name="image" class="form-control mb-2" accept=".jpg,.jpeg,.png,.webp">
                            @if($imageUrl)
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $imageUrl }}" alt="{{ $content->title }}" style="width: 210px; height: 110px; object-fit: cover; border-radius: 10px; border: 1px solid #dee2e6;">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1" @checked(old('remove_image'))>
                                        <label class="form-check-label" for="remove_image">Hapus gambar saat update</label>
                                    </div>
                                </div>
                            @endif
                            <small class="text-muted d-block mt-2">Format JPG/PNG/WEBP, maksimal 4MB.</small>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('admin.contents.index', $contentRouteParams) }}" class="btn btn-outline-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
