@extends('layouts.dashboard')
@section('title', $sectionMeta['title'] . ' - Admin')
@section('content')
@php
    $contentRouteParams = ['section' => $section];
    if (!empty($homeMenu)) { $contentRouteParams['home_menu'] = $homeMenu; }
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">🗂️ {{ $sectionMeta['title'] }}</h1>
        <p style="font-size:.82rem;color:#888;margin:4px 0 0">{{ $sectionMeta['description'] }}</p>
    </div>
    <a href="{{ route('admin.contents.create', $contentRouteParams) }}" class="btn-g">+ {{ $sectionMeta['addLabel'] }}</a>
</div>

<div class="d-card">
    <div class="table-responsive">
        <table class="d-table">
            <thead>
                <tr>
                    <th style="min-width:200px">Jenis Konten</th>
                    <th width="110">Gambar</th>
                    <th>Judul</th>
                    <th>Teks</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($contents as $content)
                <tr>
                    <td>
                        <span style="font-size:.76rem;background:#eef7f2;color:#2f5443;padding:3px 9px;border-radius:999px;font-weight:600">
                            {{ $content->admin_label }}
                        </span>
                    </td>
                    <td>
                        @if($content->admin_image_url)
                            <img src="{{ $content->admin_image_url }}" alt="{{ $content->title }}"
                                 style="width:88px;height:54px;object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.08)">
                        @else
                            <span style="color:#ccc;font-size:.8rem">–</span>
                        @endif
                    </td>
                    <td style="font-weight:600;font-size:.88rem">{{ $content->title }}</td>
                    <td style="color:#888;font-size:.84rem">{{ \Illuminate\Support\Str::limit((string)$content->admin_text, 100) }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.contents.edit', array_merge([$content], $contentRouteParams)) }}" class="btn-edit">Edit</a>
                            <form method="post" action="{{ route('admin.contents.destroy', $content) }}" class="d-inline" onsubmit="return confirm('Hapus konten ini?')">
                                @csrf @method('delete')
                                <input type="hidden" name="section" value="{{ $section }}">
                                @if(!empty($homeMenu))<input type="hidden" name="home_menu" value="{{ $homeMenu }}">@endif
                                <button class="btn-del" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:#bbb;padding:32px">Belum ada konten untuk bagian ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $contents->links() }}</div>
@endsection
