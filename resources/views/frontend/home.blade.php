@extends('layouts.frontend')
@section('title', 'Beranda - UPFotoStudio')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,700&family=Poppins:wght@400;500;600;700&display=swap');

/* ── Base ─────────────────────────────── */
.hp {
    --g:    #2f5443;
    --gd:   #1f3d30;
    --gl:   #3d7a5a;
    --gp:   #eef7f2;
    --k:    #111111;
    --ks:   #555555;
    --km:   #888888;
    --bg:   #ffffff;
    --bg2:  #fafaf8;
    --bg3:  #f4f3f0;
    --br:   rgba(0,0,0,.07);
    --w:    min(1240px, calc(100% - 48px));
    font-family: 'Poppins', sans-serif;
    color: var(--k);
    width: 100vw; max-width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    overflow-x: hidden;
}
.hp h1,.hp h2,.hp h3 {
    font-family: 'Playfair Display', serif;
    letter-spacing: -.02em;
}
.hc { width: var(--w); margin-inline: auto; }

/* Shared */
.stag {
    display: inline-block;
    font-size: .68rem; font-weight: 600;
    letter-spacing: .18em; text-transform: uppercase;
    color: var(--gl); margin-bottom: 10px;
}
.sh2 { font-size: clamp(1.9rem,3vw,2.8rem); color: var(--k); margin: 0 0 8px; }
.sp  { font-size: .97rem; color: var(--ks); max-width: 460px; margin: 0 auto; line-height: 1.7; }
.shead { text-align: center; margin-bottom: 44px; }

.lmore {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: .78rem; font-weight: 600;
    letter-spacing: .14em; text-transform: uppercase;
    text-decoration: none; color: var(--k);
    transition: color 160ms ease;
}
.lmore:hover { color: var(--g); }
.lmore .a { color: var(--gl); display: inline-block; transition: transform 180ms ease; }
.lmore:hover .a { transform: translateX(4px); }
.lwrap { text-align: center; margin-top: 32px; }

.bg { /* button green */
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--g); color: #fff;
    border-radius: 999px; padding: 12px 26px;
    font-size: .85rem; font-weight: 600;
    text-decoration: none;
    transition: background 200ms ease, transform 200ms ease, box-shadow 200ms ease;
}
.bg:hover { background: var(--gd); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 22px rgba(47,84,67,.28); }

.bo { /* button outline */
    display: inline-flex; align-items: center; gap: 8px;
    background: transparent; color: var(--k);
    border-radius: 999px; padding: 12px 26px;
    font-size: .85rem; font-weight: 600;
    text-decoration: none;
    border: 1.5px solid rgba(0,0,0,.15);
    transition: border-color 200ms ease, color 200ms ease, background 200ms ease, transform 200ms ease;
}
.bo:hover { border-color: var(--g); color: var(--g); background: var(--gp); transform: translateY(-2px); }

/* ── HERO ─────────────────────────────── */
.hero {
    background: var(--bg);
    padding: clamp(52px,7vw,88px) 0 clamp(44px,5vw,72px);
}
.hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: clamp(32px,5vw,72px);
    align-items: center;
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--gp); color: var(--g);
    border-radius: 999px; padding: 6px 14px;
    font-size: .7rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    margin-bottom: 22px;
}
.hero-badge i {
    display: inline-block; width: 6px; height: 6px;
    border-radius: 50%; background: var(--gl);
}
.hero-h1 {
    font-size: clamp(2.6rem,5.5vw,5rem);
    line-height: 1.04; color: var(--k);
    margin-bottom: 18px;
}
.hero-p {
    font-size: clamp(.97rem,1.3vw,1.07rem);
    color: var(--ks); max-width: 480px;
    line-height: 1.74; margin-bottom: 30px;
}
.hero-acts { display: flex; flex-wrap: wrap; gap: 12px; }

/* staggered image grid */
.hero-imgs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 230px 195px;
    gap: 10px;
}
.hi { border-radius: 16px; overflow: hidden; background: #ddd9d3; }
.hi img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 500ms ease; }
.hi:hover img { transform: scale(1.06); }
.hi-tall { grid-row: span 2; }

/* ── TICKER ───────────────────────────── */
.ticker {
    background: var(--g);
    padding: 13px 0;
    overflow: hidden;
}
.ticker-track {
    display: inline-flex;
    white-space: nowrap;
    animation: tick 32s linear infinite;
    will-change: transform;
}
.ticker-item {
    display: inline-flex; align-items: center; gap: 18px;
    padding: 0 20px;
    font-size: .78rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,.82);
}
.ticker-dot {
    display: inline-block; width: 4px; height: 4px;
    border-radius: 50%; background: rgba(255,255,255,.38); flex-shrink: 0;
}
@keyframes tick {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}

/* ── INTRO ────────────────────────────── */
.intro {
    background: var(--bg2);
    padding: clamp(56px,7vw,96px) 0;
}
.intro-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: clamp(36px,5vw,72px);
    align-items: center;
}
.intro-img {
    border-radius: 18px; overflow: hidden;
    aspect-ratio: 4/5; background: #d8d5cf;
    box-shadow: 0 18px 44px rgba(0,0,0,.1);
}
.intro-img img {
    width: 100%; height: 100%; object-fit: cover; object-position: top center; display: block;
    transition: transform 600ms ease;
}
.intro-img:hover img { transform: scale(1.04); }

.intro-copy .stag { display: block; }
.intro-h2 {
    font-size: clamp(2rem,3vw,3rem); color: var(--k);
    margin-bottom: 14px; line-height: 1.08;
}
.intro-p { color: var(--ks); line-height: 1.74; margin-bottom: 22px; }

.perks { list-style: none; padding: 0; margin: 0 0 28px; }
.perks li {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 10px 0; font-size: .96rem; color: #333;
    border-bottom: 1px solid var(--br);
}
.perks li:last-child { border-bottom: none; }
.perks li::before {
    content: '';
    flex-shrink: 0; margin-top: 7px;
    width: 7px; height: 7px;
    border-radius: 50%; background: var(--gl);
}

/* ── SERVICES ─────────────────────────── */
.svcs {
    background: var(--bg);
    padding: clamp(56px,7vw,96px) 0;
}
/* 4-column grid — no scroll */
.svc-scroll {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    overflow: visible;
}
.svc-col { position: relative; }

/* favorit badge */
.fav-badge {
    position: absolute; top: -10px; right: 14px; z-index: 2;
    background: #c49a3c; color: #fff;
    border-radius: 999px; padding: 4px 12px;
    font-size: .66rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    box-shadow: 0 4px 12px rgba(196,154,60,.35);
    white-space: nowrap;
}

.svc-card {
    background: var(--bg);
    border: 1px solid var(--br);
    border-radius: 18px; overflow: hidden;
    height: 100%; display: flex; flex-direction: column;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    transition: border-color 240ms ease, box-shadow 240ms ease, transform 240ms ease;
}
.svc-card:hover {
    border-color: rgba(47,84,67,.28);
    box-shadow: 0 16px 40px rgba(0,0,0,.09);
    transform: translateY(-5px);
}
.svc-top {
    background: var(--g);
    padding: 20px 22px 18px;
}
.svc-studio {
    font-size: .62rem; font-weight: 600;
    letter-spacing: .2em; text-transform: uppercase;
    color: rgba(196,228,204,.6); margin-bottom: 5px;
}
.svc-name {
    font-family: 'Playfair Display', serif;
    font-size: clamp(.95rem,1.4vw,1.25rem);
    font-weight: 700; color: #f2f0eb;
    margin: 0; line-height: 1.2;
}
.svc-body {
    padding: 18px 22px 20px;
    flex: 1; display: flex; flex-direction: column;
}
.svc-desc { color: var(--ks); font-size: .92rem; line-height: 1.64; margin-bottom: 14px; flex: 1; }
.svc-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: .91rem; color: var(--km);
    padding-top: 10px; border-top: 1px solid var(--br);
    margin-bottom: 4px;
}
.svc-price {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1rem,1.5vw,1.4rem);
    font-weight: 700; color: var(--g);
}
.svc-btn {
    display: block; text-align: center;
    background: var(--gp); color: var(--g);
    border-radius: 999px; padding: 10px;
    font-size: .76rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    text-decoration: none; margin-top: 14px;
    border: 1px solid rgba(47,84,67,.2);
    transition: background 160ms ease, color 160ms ease, transform 160ms ease;
}
.svc-btn:hover { background: var(--g); color: #fff; transform: translateY(-1px); }

/* ── GALLERY ──────────────────────────── */
.gal {
    background: var(--bg3);
    padding: clamp(56px,7vw,96px) 0;
}
.mosaic {
    display: grid;
    grid-template-columns: repeat(12,1fr);
    gap: 10px;
}
.mt {
    border-radius: 14px; overflow: hidden;
    background: #d4d1cb; position: relative; cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.mt img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    transition: transform 480ms cubic-bezier(.25,.46,.45,.94);
}
.mt:hover img { transform: scale(1.07); }
.mt-veil {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(17,17,17,.7) 0%, transparent 52%);
    opacity: 0; transition: opacity 260ms ease;
    display: flex; align-items: flex-end; padding: 14px 16px;
}
.mt:hover .mt-veil { opacity: 1; }
.mt-lbl { color: #f2f0eb; font-size: .7rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; }
.mt.xl { grid-column: span 6; min-height: 256px; }
.mt.md { grid-column: span 4; min-height: 210px; }

/* ── FAQ ──────────────────────────────── */
.faq {
    background: var(--bg);
    padding: clamp(56px,7vw,96px) 0;
    border-top: 1px solid var(--br);
}
.faq-inner { max-width: 820px; margin: 0 auto; }
.faq .accordion-item {
    border: 1px solid var(--br) !important;
    border-radius: 12px !important;
    overflow: hidden; background: transparent;
    margin-bottom: 8px;
}
.faq .accordion-button {
    font-family: 'Poppins', sans-serif;
    font-weight: 600; font-size: .96rem;
    color: var(--k); background: var(--bg2);
    box-shadow: none; border-radius: 12px !important;
    padding: 17px 22px;
    transition: background 200ms ease;
}
.faq .accordion-button:not(.collapsed) {
    color: var(--g); background: var(--gp);
    border-radius: 12px 12px 0 0 !important;
}
.faq .accordion-body {
    background: #fafaf8; color: #444;
    line-height: 1.74; padding: 14px 22px 18px;
}

/* ── RESPONSIVE ───────────────────────── */
@media (max-width: 991.98px) {
    .hp { --w: min(1240px,calc(100% - 32px)); }
    .hero-grid { grid-template-columns: 1fr; }
    .hero-imgs { max-width: 560px; grid-template-rows: 200px 170px; }
    .intro-grid { grid-template-columns: 1fr; }
    .intro-img { max-width: 520px; }
    .mt.xl,.mt.md { grid-column: span 6; }
    .svc-scroll { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 575.98px) {
    .hp { --w: calc(100% - 24px); }
    .hero { padding: 40px 0 36px; }
    .hero-h1 { font-size: clamp(2.2rem,8.5vw,3rem); }
    .hero-imgs { grid-template-rows: 170px 145px; }
    .bg,.bo { width: 100%; justify-content: center; }
    .hero-acts { flex-direction: column; }
    .mt.xl,.mt.md { grid-column: span 12; min-height: 185px; }
    .shead { margin-bottom: 28px; }
    .svc-scroll { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="hp">

{{-- HERO --}}
<section class="hero">
    <div class="hc">
        <div class="hero-grid">
            <div>
                <div class="hero-badge"><i></i> Studio Foto Profesional</div>
                <h1 class="hero-h1">{{ $hero['title'] }}</h1>
                <p class="hero-p">{{ $hero['content'] }}</p>
                <div class="hero-acts">
                    <a href="{{ route('frontend.pricing') }}" class="bg">Booking Sekarang</a>
                    <a href="{{ route('frontend.gallery') }}"  class="bo">Lihat Galeri</a>
                </div>
            </div>
            <div>
                @php
                    $hA = $galleryPreview[0]['image'] ?? asset('assets/images/home/gallery/gallery-1.svg');
                    $hB = $galleryPreview[1]['image'] ?? asset('assets/images/home/gallery/gallery-2.svg');
                    $hC = $galleryPreview[2]['image'] ?? asset('assets/images/home/gallery/gallery-3.svg');
                @endphp
                <div class="hero-imgs">
                    <div class="hi hi-tall"><img src="{{ $hA }}" alt="Studio"></div>
                    <div class="hi"><img src="{{ $hB }}" alt="Studio"></div>
                    <div class="hi"><img src="{{ $hC }}" alt="Studio"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TICKER --}}
<div class="ticker" aria-hidden="true">
    @php
        $tks = ['Family','Couple','Group','Headshot','Maternity','Pre-Wedding','Garden','Pas Foto','Produk'];
    @endphp
    <div class="ticker-track">
        @for($r=0;$r<2;$r++)
            @foreach($tks as $t)
                <span class="ticker-item">{{ $t }}<span class="ticker-dot"></span></span>
            @endforeach
        @endfor
    </div>
</div>

{{-- INTRO --}}
<section class="intro">
    <div class="hc">
        @php $introImg = $galleryPreview[3]['image'] ?? $galleryPreview[0]['image'] ?? asset('assets/images/home/gallery/gallery-1.svg'); @endphp
        <div class="intro-grid">
            <div class="intro-img">
                <img src="{{ $introImg }}" alt="Studio UPFotoStudio">
            </div>
            <div>
                <span class="stag">{{ $whyChooseSection['title'] ?: 'Mengapa Kami' }}</span>
                <h2 class="intro-h2">Kami Berikan<br>Layanan Terbaik</h2>
                <p class="intro-p">{{ $whyChooseSection['content'] }}</p>
                @if(!empty($whyChooseItems))
                <ul class="perks">
                    @foreach($whyChooseItems as $item)<li>{{ $item }}</li>@endforeach
                </ul>
                @endif
                <a href="{{ route('frontend.about') }}" class="bg">Tentang Kami <span aria-hidden="true">&#8594;</span></a>
            </div>
        </div>
    </div>
</section>

{{-- SERVICES --}}
<section class="svcs">
    <div class="hc">
        <div class="shead">
            <p class="stag">Paket Studio</p>
            <h2 class="sh2">{{ $serviceSection['title'] }}</h2>
        </div>
        @php $favIndex = 1; @endphp
        <div class="svc-scroll">
            @forelse($services as $i => $service)
                <div class="svc-col">
                    @if($i === $favIndex)
                        <div class="fav-badge">⭐ Paling Diminati</div>
                    @endif
                    <div class="svc-card">
                        <div class="svc-top">
                            <p class="svc-studio">{{ $service->studio->name ?? 'Studio' }}</p>
                            <h3 class="svc-name">{{ $service->name }}</h3>
                        </div>
                        <div class="svc-body">
                            <p class="svc-desc">{{ $service->description ?: 'Paket layanan studio dengan kualitas profesional dan peralatan lengkap.' }}</p>
                            <div class="svc-row"><span>Durasi</span><strong>{{ $service->duration_minutes }} menit</strong></div>
                            <div class="svc-row" style="margin-bottom:0"><span>Harga mulai</span><span class="svc-price">Rp{{ number_format($service->price,0,',','.') }}</span></div>
                            <a href="{{ route('frontend.booking.package-detail', ['servicePackage' => $service->id, 'date' => now()->toDateString()]) }}"
                               class="svc-btn">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
            @empty
                <p style="color:var(--ks);">Belum ada layanan aktif.</p>
            @endforelse
        </div>
        <div class="lwrap">
            <a href="{{ route('frontend.pricing') }}" class="lmore">Lihat Semua Paket <span class="a">&#8594;</span></a>
        </div>
    </div>
</section>

{{-- GALLERY --}}
<section class="gal">
    <div class="hc">
        <div class="shead">
            <p class="stag">Galeri Studio</p>
            <h2 class="sh2">{{ $gallerySection['title'] }}</h2>
        </div>
        @php $gm = ['xl','xl','md','md','md']; @endphp
        <div class="mosaic">
            @foreach($galleryPreview as $i => $item)
                <figure class="mt {{ $gm[$i] ?? 'md' }}">
                    <img src="{{ $item['image'] }}" alt="{{ $item['alt'] }}">
                    <div class="mt-veil"><span class="mt-lbl">{{ $item['alt'] ?? 'Studio' }}</span></div>
                </figure>
            @endforeach
        </div>
        <div class="lwrap">
            <a href="{{ route('frontend.gallery') }}" class="lmore">Lihat Semua Foto <span class="a">&#8594;</span></a>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="faq">
    <div class="hc">
        <div class="shead">
            <p class="stag">FAQ</p>
            <h2 class="sh2">{{ $faqSection['title'] }}</h2>
            @if(!empty($faqSection['content']))<p class="sp">{{ $faqSection['content'] }}</p>@endif
        </div>
        <div class="faq-inner">
            <div class="accordion" id="faqAcc">
                @foreach($faqItems as $i => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $i!==0?'collapsed':'' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#fc{{ $i }}"
                                    aria-expanded="{{ $i===0?'true':'false' }}">
                                {{ $faq['question'] }}
                            </button>
                        </h2>
                        <div id="fc{{ $i }}" class="accordion-collapse collapse {{ $i===0?'show':'' }}" data-bs-parent="#faqAcc">
                            <div class="accordion-body">{{ $faq['answer'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

</div>
@endsection
