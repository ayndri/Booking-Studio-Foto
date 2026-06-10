@extends('layouts.frontend')
@section('title', 'Galeri - UPFotoStudio')

@push('styles')
<style>
/* ── Base ─────────────────────────────────── */
.glr {
    --g:  #2f5443;
    --gd: #1f3d30;
    --gl: #3d7a5a;
    --gp: #eef7f2;
    --k:  #111111;
    --ks: #555555;
    --km: #999999;
    --bg:  #ffffff;
    --bg2: #fafaf8;
    --bg3: #f4f3f0;
    --br:  rgba(0,0,0,.07);
    --w: min(1240px, calc(100% - 48px));
    font-family: 'Poppins', sans-serif;
    color: var(--k);
    width: 100vw; max-width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    overflow-x: hidden;
}
.glr h1,.glr h2,.glr h3 {
    font-family: 'Playfair Display', serif;
    letter-spacing: -.02em;
}
.gc { width: var(--w); margin-inline: auto; }

/* ── HERO ─────────────────────────────────── */
.glr-hero {
    background: var(--bg);
    padding: clamp(52px,6vw,80px) 0 clamp(36px,4vw,52px);
    border-bottom: 1px solid var(--br);
}
.glr-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--gp); color: var(--g);
    border-radius: 999px; padding: 6px 14px;
    font-size: .7rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    margin-bottom: 18px;
}
.glr-badge i { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: var(--gl); }
.glr-h1 {
    font-size: clamp(2.4rem,4.5vw,4rem);
    line-height: 1.06; color: var(--k); margin-bottom: 14px;
}
.glr-lead {
    font-size: clamp(.97rem,1.25vw,1.06rem);
    color: var(--ks); max-width: 540px; line-height: 1.74;
}

/* ── SEARCH ───────────────────────────────── */
.glr-search-wrap {
    background: var(--bg2);
    padding: 28px 0;
    border-bottom: 1px solid var(--br);
}
.glr-search-row {
    display: flex; align-items: center; gap: 10px;
}
.glr-search-field {
    flex: 1;
    display: flex; align-items: center;
    background: var(--bg);
    border: 1.5px solid var(--br);
    border-radius: 999px;
    padding: 0 20px 0 18px;
    gap: 10px;
    transition: border-color 200ms ease, box-shadow 200ms ease;
}
.glr-search-field:focus-within {
    border-color: rgba(47,84,67,.38);
    box-shadow: 0 0 0 3px rgba(47,84,67,.08);
}
.glr-search-icon { color: var(--km); flex-shrink: 0; font-size: 1rem; }
.glr-search-field input {
    flex: 1; border: none; background: transparent;
    padding: 13px 0;
    font-family: 'Poppins', sans-serif;
    font-size: .93rem; color: var(--k);
    outline: none;
}
.glr-search-field input::placeholder { color: var(--km); }
.glr-btn {
    background: var(--g); color: #fff;
    border: none; border-radius: 999px;
    padding: 13px 24px;
    font-family: 'Poppins', sans-serif;
    font-size: .84rem; font-weight: 600;
    cursor: pointer; flex-shrink: 0;
    transition: background 180ms ease, transform 180ms ease;
}
.glr-btn:hover { background: var(--gd); transform: translateY(-1px); }
.glr-btn-reset {
    background: transparent; color: var(--ks);
    border: 1.5px solid var(--br); border-radius: 999px;
    padding: 12px 20px;
    font-family: 'Poppins', sans-serif;
    font-size: .84rem; font-weight: 500;
    cursor: pointer; flex-shrink: 0; text-decoration: none;
    display: inline-flex; align-items: center;
    transition: border-color 180ms ease, color 180ms ease;
}
.glr-btn-reset:hover { border-color: rgba(47,84,67,.3); color: var(--g); }
.glr-count {
    font-size: .82rem; color: var(--km);
    margin-top: 10px;
}
.glr-count strong { color: var(--g); font-weight: 600; }

/* ── GRID ─────────────────────────────────── */
.glr-main {
    background: var(--bg3);
    padding: clamp(40px,5vw,64px) 0 clamp(48px,6vw,80px);
}
.glr-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
.glr-card {
    border-radius: 16px;
    overflow: hidden;
    background: var(--bg);
    border: 1px solid var(--br);
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    transition: transform 240ms ease, box-shadow 240ms ease, border-color 240ms ease;
    display: flex; flex-direction: column;
}
.glr-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 36px rgba(0,0,0,.1);
    border-color: rgba(47,84,67,.2);
}
.glr-img-wrap {
    position: relative;
    overflow: hidden;
    aspect-ratio: 4/3;
    background: #ddd9d3;
    flex-shrink: 0;
}
.glr-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    transition: transform 500ms cubic-bezier(.25,.46,.45,.94);
}
.glr-card:hover .glr-img-wrap img { transform: scale(1.07); }

.glr-img-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(17,17,17,.72) 0%, transparent 50%);
    opacity: 0; transition: opacity 260ms ease;
    display: flex; align-items: flex-end;
    padding: 18px;
}
.glr-card:hover .glr-img-overlay { opacity: 1; }
.glr-overlay-title {
    color: #f5f5f2; font-size: .78rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
}

.glr-card-body {
    padding: 16px 18px 18px;
    flex: 1; display: flex; flex-direction: column;
}
.glr-card-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.02rem; font-weight: 700;
    color: var(--k); margin-bottom: 6px; line-height: 1.3;
}
.glr-card-caption {
    font-size: .85rem; color: var(--ks); line-height: 1.6;
    margin: 0; flex: 1;
}

/* ── EMPTY STATE ──────────────────────────── */
.glr-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: clamp(48px,6vw,80px) 0;
}
.glr-empty-icon {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: var(--gp);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px;
    font-size: 1.6rem;
}
.glr-empty h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem; color: var(--k);
    margin-bottom: 8px;
}
.glr-empty p { font-size: .95rem; color: var(--ks); max-width: 360px; margin: 0 auto 20px; }
.glr-empty a {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--g); color: #fff;
    border-radius: 999px; padding: 11px 24px;
    font-size: .82rem; font-weight: 600; text-decoration: none;
    transition: background 180ms ease;
}
.glr-empty a:hover { background: var(--gd); color: #fff; }

/* ── RESPONSIVE ───────────────────────────── */
@media (max-width: 991.98px) {
    .glr { --w: min(1240px,calc(100% - 32px)); }
    .glr-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 575.98px) {
    .glr { --w: calc(100% - 24px); }
    .glr-h1 { font-size: clamp(2rem,8vw,2.8rem); }
    .glr-grid { grid-template-columns: 1fr; gap: 12px; }
    .glr-search-row { flex-wrap: wrap; }
    .glr-btn, .glr-btn-reset { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="glr">

{{-- HERO --}}
<section class="glr-hero">
    <div class="gc">
        <div class="glr-badge"><i></i> Foto Studio</div>
        <h1 class="glr-h1">{{ $gallery['title'] }}</h1>
        <p class="glr-lead">{{ $gallery['content'] }}</p>
    </div>
</section>

{{-- SEARCH --}}
<div class="glr-search-wrap">
    <div class="gc">
        <form method="get" action="{{ route('frontend.gallery') }}">
            <div class="glr-search-row">
                <div class="glr-search-field">
                    <span class="glr-search-icon">&#128269;</span>
                    <input
                        type="search"
                        name="q"
                        id="gallery_search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari judul atau deskripsi foto..."
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="glr-btn">Cari</button>
                @if(!empty($search))
                    <a href="{{ route('frontend.gallery') }}" class="glr-btn-reset">&#215; Reset</a>
                @endif
            </div>
        </form>
        <p class="glr-count">
            @if(!empty($search))
                Hasil untuk <strong>"{{ $search }}"</strong> &mdash;
            @endif
            Menampilkan <strong>{{ count($galleryItems) }}</strong> foto
        </p>
    </div>
</div>

{{-- GALLERY GRID --}}
<section class="glr-main">
    <div class="gc">
        <div class="glr-grid">
            @forelse($galleryItems as $item)
                <div class="glr-card">
                    <div class="glr-img-wrap">
                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy">
                        <div class="glr-img-overlay">
                            <span class="glr-overlay-title">{{ $item['title'] }}</span>
                        </div>
                    </div>
                    <div class="glr-card-body">
                        <h2 class="glr-card-title">{{ $item['title'] }}</h2>
                        @if(!empty($item['caption']))
                            <p class="glr-card-caption">{{ $item['caption'] }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="glr-empty">
                    <div class="glr-empty-icon">&#128247;</div>
                    <h3>Foto tidak ditemukan</h3>
                    <p>
                        @if(!empty($search))
                            Tidak ada foto yang cocok dengan "<strong>{{ $search }}</strong>". Coba kata kunci lain.
                        @else
                            Belum ada foto galeri yang tersedia saat ini.
                        @endif
                    </p>
                    @if(!empty($search))
                        <a href="{{ route('frontend.gallery') }}">Lihat Semua Foto &rarr;</a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</section>

</div>
@endsection
