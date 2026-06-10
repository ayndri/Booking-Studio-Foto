@extends('layouts.frontend')

@section('title', 'Galeri - UPFotoStudio')

@push('styles')
<style>
    .gallery-page {
        padding-top: 28px;
        padding-bottom: 56px;
    }

    @media (max-width: 767.98px) {
        .gallery-page {
            padding-top: 20px;
            padding-bottom: 40px;
        }
    }
</style>
@endpush

@section('content')
<section class="gallery-page">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h1 class="h4 mb-2">{{ $gallery['title'] }}</h1>
            <p class="mb-0">{{ $gallery['content'] }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <form method="get" action="{{ route('frontend.gallery') }}" class="row g-2 align-items-center">
                <div class="col-md">
                    <label for="gallery_search" class="visually-hidden">Cari galeri</label>
                    <input
                        type="search"
                        id="gallery_search"
                        name="q"
                        class="form-control"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari judul atau deskripsi singkat..."
                    >
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    @if(!empty($search))
                        <a href="{{ route('frontend.gallery') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
            <small class="text-muted d-block mt-2">
                @if(!empty($search))
                    Hasil pencarian untuk "<strong>{{ $search }}</strong>".
                @endif
                Menampilkan {{ count($galleryItems) }} item.
            </small>
        </div>
    </div>

    <div class="row g-3">
        @forelse($galleryItems as $item)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                    <div class="card-body">
                        <h2 class="h6 mb-2">{{ $item['title'] }}</h2>
                        @if(!empty($item['caption']))
                            <p class="text-muted small mb-0">{{ $item['caption'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border mb-0">
                    Tidak ada hasil galeri yang cocok dengan kata kunci pencarian Anda.
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection
