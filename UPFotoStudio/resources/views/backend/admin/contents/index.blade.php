@extends('layouts.dashboard')

@section('title', $sectionMeta['title'] . ' - Admin')

@section('content')
@php
    $contentRouteParams = ['section' => $section];
    if (!empty($homeMenu)) {
        $contentRouteParams['home_menu'] = $homeMenu;
    }
@endphp
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h1 class="h4 mb-1">{{ $sectionMeta['title'] }}</h1>
        <p class="text-muted mb-0">{{ $sectionMeta['description'] }}</p>
    </div>
    <a href="{{ route('admin.contents.create', $contentRouteParams) }}" class="btn btn-primary btn-sm">{{ $sectionMeta['addLabel'] }}</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
            <tr>
                <th style="min-width: 210px;">Jenis Konten</th>
                <th style="width: 110px;">Gambar</th>
                <th style="min-width: 180px;">Judul</th>
                <th>Teks</th>
                <th width="180">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($contents as $content)
                <tr>
                    <td>{{ $content->admin_label }}</td>
                    <td>
                        @if($content->admin_image_url)
                            <img src="{{ $content->admin_image_url }}" alt="{{ $content->title }}" style="width: 92px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid #dee2e6;">
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td>{{ $content->title }}</td>
                    <td class="text-muted">{{ \Illuminate\Support\Str::limit((string) $content->admin_text, 120) }}</td>
                    <td>
                        <a href="{{ route('admin.contents.edit', array_merge([$content], $contentRouteParams)) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form method="post" action="{{ route('admin.contents.destroy', $content) }}" class="d-inline" onsubmit="return confirm('Hapus konten ini?')">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="section" value="{{ $section }}">
                            @if(!empty($homeMenu))
                                <input type="hidden" name="home_menu" value="{{ $homeMenu }}">
                            @endif
                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Belum ada konten.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $contents->links() }}</div>
@endsection
