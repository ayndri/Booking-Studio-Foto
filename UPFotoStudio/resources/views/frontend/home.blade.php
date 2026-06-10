@extends('layouts.frontend')

@section('title', 'Beranda - UPFotoStudio')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap');

    .home-page {
        --ink: #202327;
        --sand: #efe9d8;
        --forest: #406748;
        --forest-dark: #2e4e39;
        --mist: #d8dde7;
        --panel: #f4f6fb;
        --panel-card: #e7ecf6;
        --home-content-width: min(1240px, calc(100% - 48px));
        font-family: 'Manrope', sans-serif;
        color: var(--ink);
        width: 100vw;
        width: 100dvw;
        max-width: 100vw;
        max-width: 100dvw;
        margin-left: calc(50% - 50vw);
        margin-left: calc(50% - 50dvw);
        margin-right: calc(50% - 50vw);
        margin-right: calc(50% - 50dvw);
        overflow-x: hidden;
    }

    .home-page h1,
    .home-page h2,
    .home-page h3 {
        font-family: 'Playfair Display', serif;
        letter-spacing: -0.02em;
    }

    .home-block {
        margin-bottom: 0;
        padding: clamp(34px, 5vw, 60px) 0;
    }

    .home-content {
        width: var(--home-content-width);
        margin-inline: auto;
    }

    .home-strip {
        position: relative;
    }

    .home-strip-carousel {
        background: linear-gradient(135deg, #2f5443 0%, #335947 45%, #294b3d 100%);
    }

    .home-strip-intro {
        background: linear-gradient(112deg, #f6efe1 0%, #edf2ff 52%, #dfe9ff 100%);
    }

    .home-strip-advantage {
        background: #8a6a50;
    }

    .home-strip-gallery {
        background: #f0ecde;
    }

    .home-strip-services {
        background: #d8dde7;
    }

    .home-strip-faq {
        background: #f4f2ea;
    }

    .hero-carousel {
        overflow: hidden;
        box-shadow: 0 20px 42px rgba(16, 24, 40, 0.16);
    }

    .hero-carousel .carousel-item img {
        width: 100%;
        height: clamp(360px, 50vw, 540px);
        object-fit: cover;
        filter: saturate(0.92) contrast(1.04);
    }

    .hero-intro-panel {
        position: relative;
        overflow: hidden;
        padding: clamp(14px, 2.4vw, 28px) 0;
    }

    .hero-intro-row {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 430px);
        align-items: start;
        gap: clamp(22px, 3vw, 44px);
    }

    .hero-copy {
        max-width: 760px;
    }

    .hero-eyebrow {
        text-transform: uppercase;
        letter-spacing: 0.24em;
        font-size: 0.72rem;
        margin-bottom: 14px;
        color: #5f6f89;
        font-weight: 800;
    }

    .hero-title {
        font-size: clamp(2.2rem, 4.2vw, 3.9rem);
        line-height: 1.08;
        margin-bottom: 14px;
        color: #1f2736;
    }

    .hero-description {
        font-size: clamp(1rem, 1.35vw, 1.16rem);
        color: #455367;
        max-width: 620px;
        margin-bottom: 22px;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 12px;
    }

    .hero-visual {
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(97, 121, 167, 0.26);
        box-shadow: 0 18px 36px rgba(19, 33, 58, 0.16);
        background: rgba(220, 228, 245, 0.82);
    }

    .hero-visual img {
        width: 100%;
        min-height: 300px;
        object-fit: cover;
        display: block;
    }

    .hero-btn {
        border-radius: 999px;
        padding: 12px 26px;
        font-size: 0.82rem;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        font-weight: 700;
        text-decoration: none;
        transition: transform 180ms ease, opacity 180ms ease, box-shadow 180ms ease;
    }

    .hero-btn:hover {
        transform: translateY(-2px);
        opacity: 0.95;
    }

    .hero-btn-primary {
        background: linear-gradient(135deg, #5f925d 0%, #406d44 100%);
        color: #f4f6ed;
        box-shadow: 0 12px 26px rgba(62, 96, 63, 0.32);
    }

    .hero-btn-secondary {
        background: rgba(227, 235, 248, 0.94);
        border: 1px solid rgba(43, 78, 125, 0.18);
        color: #29416a;
    }

    .hero-carousel .carousel-indicators {
        z-index: 2;
        justify-content: flex-end;
        margin-right: 24px;
        margin-left: 24px;
    }

    .hero-carousel .carousel-indicators [data-bs-target] {
        width: 11px;
        height: 11px;
        border-radius: 999px;
        border: 0;
        background-color: rgba(255, 255, 255, 0.62);
    }

    .hero-carousel .carousel-indicators .active {
        background-color: #ffffff;
    }

    .hero-carousel .carousel-control-prev,
    .hero-carousel .carousel-control-next {
        z-index: 2;
        width: 8%;
    }

    .hero-carousel .carousel-control-prev-icon,
    .hero-carousel .carousel-control-next-icon {
        width: 2.9rem;
        height: 2.9rem;
        border-radius: 999px;
        background-color: rgba(15, 23, 42, 0.36);
        background-size: 44% 44%;
        box-shadow: 0 10px 22px rgba(8, 18, 38, 0.28);
    }

    .advantage-panel {
        padding: clamp(6px, 1.5vw, 16px) 0;
        color: #f4ecdc;
        position: relative;
    }

    .advantage-row {
        --adv-gap: clamp(18px, 2.8vw, 44px);
    }

    .advantage-media-col {
        padding-right: var(--adv-gap);
    }

    .advantage-copy-col {
        padding-left: var(--adv-gap);
    }

    .advantage-media {
        max-width: 390px;
        margin: 0;
        border-radius: 24px;
        border: 4px solid rgba(255, 255, 255, 0.85);
        overflow: hidden;
        box-shadow: 0 16px 28px rgba(34, 19, 7, 0.28);
    }

    .advantage-media img {
        width: 100%;
        height: clamp(220px, 26vw, 280px);
        object-fit: cover;
        display: block;
    }

    .advantage-copy {
        max-width: none;
    }

    .advantage-label {
        text-transform: uppercase;
        letter-spacing: 0.2em;
        font-size: 0.72rem;
        margin-bottom: 10px;
        font-weight: 700;
        color: #e6dece;
    }

    .advantage-title {
        font-size: clamp(1.8rem, 2.8vw, 2.85rem);
        line-height: 1.2;
        margin-bottom: 12px;
        color: #f7efe1;
    }

    .advantage-desc {
        color: #ebdbc5;
        margin-bottom: 12px;
        max-width: 640px;
        line-height: 1.65;
    }

    .advantage-list {
        margin-bottom: 16px;
        padding-left: 18px;
        color: #f5e8d5;
    }

    .advantage-list li {
        margin-bottom: 6px;
    }

    .advantage-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        border-radius: 999px;
        background: #608f5d;
        color: #f4f6ed;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        font-weight: 700;
        padding: 11px 24px;
        transition: transform 180ms ease, opacity 180ms ease;
    }

    .advantage-link:hover {
        transform: translateY(-2px);
        opacity: 0.95;
    }

    .gallery-shell {
        padding: clamp(10px, 2vw, 20px) 0;
    }

    .section-eyebrow {
        text-transform: uppercase;
        letter-spacing: 0.2em;
        font-size: 0.7rem;
        color: #8aa17d;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .section-title {
        font-size: clamp(2rem, 3vw, 3rem);
        margin-bottom: 24px;
        color: #22262a;
    }

    .gallery-mosaic {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 16px;
    }

    .gallery-tile {
        border-radius: 22px;
        overflow: hidden;
        background: #ebe3d8;
        box-shadow: 0 18px 34px rgba(20, 28, 36, 0.08);
        margin: 0;
    }

    .gallery-tile img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .gallery-tile.tile-xl {
        grid-column: span 6;
        min-height: 252px;
    }

    .gallery-tile.tile-md {
        grid-column: span 4;
        min-height: 220px;
    }

    .gallery-link {
        font-size: 0.82rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        text-decoration: none;
        color: #24292e;
        font-weight: 700;
    }

    .gallery-link span {
        color: #608f5d;
        font-size: 1.18rem;
        line-height: 1;
        letter-spacing: 0;
        font-weight: 800;
        margin-left: 4px;
        display: inline-block;
        transform: translateY(-1px);
    }

    .services-shell {
        padding: clamp(10px, 2vw, 18px) 0;
    }

    .services-head {
        text-align: center;
        margin-bottom: 22px;
    }

    .services-head h2 {
        margin: 0;
        font-size: clamp(1.7rem, 2.4vw, 2.6rem);
    }

    .services-foot {
        text-align: center;
        margin-top: 22px;
    }

    .services-head-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #24292e;
        font-size: 0.82rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-weight: 700;
        transition: opacity 180ms ease;
    }

    .services-head-link:hover {
        opacity: 0.85;
    }

    .services-head-link span {
        color: #608f5d;
        font-size: 1.18rem;
        line-height: 1;
        letter-spacing: 0;
        font-weight: 800;
        margin-left: 4px;
        display: inline-block;
        transform: translateY(-1px);
    }

    .service-card {
        border: 0;
        border-radius: 18px;
        background: var(--panel-card);
        box-shadow: 0 14px 28px rgba(21, 41, 69, 0.08);
        height: 100%;
    }

    .service-card .card-body {
        padding: 22px;
    }

    .service-badge {
        display: inline-block;
        border-radius: 999px;
        background: #cdd9ef;
        color: #1d4fd8;
        padding: 6px 14px;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .service-title {
        margin-top: 14px;
        margin-bottom: 8px;
        font-size: clamp(1.35rem, 1.7vw, 2rem);
        line-height: 1.25;
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
    }

    .service-desc {
        color: #4f5f71;
        font-size: 1.02rem;
        margin-bottom: 18px;
    }

    .service-meta {
        display: flex;
        justify-content: space-between;
        color: #4f5f71;
        margin-bottom: 8px;
        font-size: 1.02rem;
    }

    .service-price {
        color: #1760ed;
        font-size: clamp(1.35rem, 1.9vw, 2rem);
        font-weight: 800;
    }

    .faq-shell {
        padding: clamp(10px, 2vw, 16px) 0;
    }

    .faq-title {
        margin-bottom: 10px;
        font-size: clamp(1.7rem, 2.2vw, 2.5rem);
        text-align: center;
    }

    .faq-subtitle {
        color: #5f6c7b;
        margin: 0 auto 20px;
        max-width: 760px;
        text-align: center;
    }

    .faq-shell .accordion {
        max-width: 1080px;
        margin: 0 auto;
        text-align: left;
    }

    .faq-shell .accordion-item {
        border: 1px solid #d6d5d0;
        border-radius: 12px;
        overflow: hidden;
    }

    .faq-shell .accordion-item + .accordion-item {
        margin-top: 12px;
    }

    .faq-shell .accordion-button {
        font-weight: 700;
        color: #202327;
        background: #ebe8dd;
        box-shadow: none;
    }

    .faq-shell .accordion-button:not(.collapsed) {
        background: #f3efe1;
    }

    .faq-shell .accordion-body {
        background: #e7e3d7;
    }

    @media (max-width: 991.98px) {
        .home-page {
            --home-content-width: min(1240px, calc(100% - 34px));
        }

        .hero-carousel .carousel-item img {
            height: 430px;
        }

        .hero-intro-row {
            grid-template-columns: 1fr;
        }

        .hero-visual {
            width: min(560px, 100%);
        }

        .advantage-media-col {
            padding-right: 0;
        }

        .advantage-copy-col {
            padding-left: 0;
        }

        .advantage-media {
            margin: 0 auto;
        }

        .advantage-media img {
            height: 240px;
        }

        .gallery-tile.tile-xl,
        .gallery-tile.tile-md {
            grid-column: span 6;
        }
    }

    @media (max-width: 575.98px) {
        .home-page {
            --home-content-width: calc(100% - 28px);
        }

        .hero-carousel .carousel-item img {
            height: 420px;
        }

        .hero-intro-panel {
            padding: 14px 0;
        }

        .hero-title {
            font-size: clamp(1.8rem, 9vw, 2.5rem);
        }

        .hero-btn {
            width: 100%;
            text-align: center;
        }

        .hero-actions {
            width: 100%;
        }

        .hero-visual img {
            min-height: 240px;
        }

        .gallery-tile.tile-xl,
        .gallery-tile.tile-md {
            grid-column: span 12;
            min-height: 210px;
        }

        .services-head {
            margin-bottom: 18px;
        }
    }
</style>
@endpush

@section('content')
<div class="home-page">
    <section class="home-block home-strip home-strip-carousel">
        <div class="home-content">
            <div id="promoCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach($promoSlides as $index => $slide)
                        <button type="button"
                                data-bs-target="#promoCarousel"
                                data-bs-slide-to="{{ $index }}"
                                class="{{ $index === 0 ? 'active' : '' }}"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>

                <div class="carousel-inner">
                    @foreach($promoSlides as $index => $slide)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}">
                        </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <section class="home-block home-strip home-strip-intro">
        <div class="home-content">
            <div class="hero-intro-panel">
                @php
                    $heroIntroImage = $galleryPreview[1]['image'] ?? ($promoSlides[0]['image'] ?? asset('assets/images/home/gallery/gallery-1.svg'));
                @endphp
                <div class="hero-intro-row">
                    <div class="hero-copy">
                        <p class="hero-eyebrow">Tempatnya sewa studio terbaik di Indonesia</p>
                        <h1 class="hero-title">{{ $hero['title'] }}</h1>
                        <p class="hero-description">{{ $hero['content'] }}</p>
                        <div class="hero-actions">
                            <a href="{{ route('frontend.pricing') }}" class="hero-btn hero-btn-primary">Pricelist Harga</a>
                            <a href="{{ route('frontend.contact') }}" class="hero-btn hero-btn-secondary">Hubungi Kami</a>
                        </div>
                    </div>
                    <div class="hero-visual">
                        <img src="{{ $heroIntroImage }}" alt="Studio unggulan UPFotoStudio">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-block home-strip home-strip-advantage">
        <div class="home-content">
            @php
                $featureImage = $galleryPreview[0]['image'] ?? ($promoSlides[0]['image'] ?? asset('assets/images/home/gallery/gallery-1.svg'));
            @endphp
            <div class="advantage-panel">
                <div class="row g-0 align-items-center advantage-row">
                    <div class="col-lg-4 advantage-media-col">
                        <div class="advantage-media">
                            <img src="{{ $featureImage }}" alt="Studio dan layanan terbaik">
                        </div>
                    </div>
                    <div class="col-lg-8 advantage-copy advantage-copy-col">
                        <p class="advantage-label">{{ $whyChooseSection['title'] ?: 'Layanan Terbaik' }}</p>
                        <h2 class="advantage-title">Kami Berikan Layanan Terbaik</h2>
                        <p class="advantage-desc">{{ $whyChooseSection['content'] }}</p>
                        <ul class="advantage-list mb-0">
                            @foreach($whyChooseItems as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('frontend.pricing') }}" class="advantage-link mt-3">Lihat Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-block home-strip home-strip-gallery">
        <div class="home-content gallery-shell">
            <p class="section-eyebrow text-center">Galeri Ruangan Studio</p>
            <h2 class="section-title text-center">{{ $gallerySection['title'] }}</h2>

            @php
                $galleryClassMap = ['tile-xl', 'tile-xl', 'tile-md', 'tile-md', 'tile-md'];
            @endphp
            <div class="gallery-mosaic">
                @foreach($galleryPreview as $index => $item)
                    <figure class="gallery-tile {{ $galleryClassMap[$index] ?? 'tile-md' }}">
                        <img src="{{ $item['image'] }}" alt="{{ $item['alt'] }}">
                    </figure>
                @endforeach
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('frontend.gallery') }}" class="gallery-link">
                    Lihat Ruangan Lainnya
                    <span aria-hidden="true">&#8594;</span>
                </a>
            </div>
        </div>
    </section>

    <section class="home-block home-strip home-strip-services">
        <div class="home-content services-shell">
            <div class="services-head">
                <h2>{{ $serviceSection['title'] }}</h2>
            </div>

            <div class="row g-4">
                @forelse($services as $service)
                    <div class="col-lg-4 col-md-6">
                        <div class="card service-card">
                            <div class="card-body">
                                <span class="service-badge">{{ $service->studio->name ?? 'Studio' }}</span>
                                <h3 class="service-title">{{ $service->name }}</h3>
                                <p class="service-desc">{{ $service->description ?: 'Paket layanan studio dengan kualitas profesional.' }}</p>
                                <div class="service-meta">
                                    <span>Durasi</span>
                                    <strong>{{ $service->duration_minutes }} menit</strong>
                                </div>
                                <div class="service-meta mb-0">
                                    <span>Harga mulai</span>
                                    <span class="service-price">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info mb-0">Belum ada layanan aktif.</div>
                    </div>
                @endforelse
            </div>

            <div class="services-foot">
                <a href="{{ route('frontend.pricing') }}" class="services-head-link">
                    Lihat Paket Harga Lainnya
                    <span aria-hidden="true">&#8594;</span>
                </a>
            </div>
        </div>
    </section>

    <section class="home-block home-strip home-strip-faq">
        <div class="home-content faq-shell">
            <h2 class="faq-title">{{ $faqSection['title'] }}</h2>
            @if(!empty($faqSection['content']))
                <p class="faq-subtitle">{{ $faqSection['content'] }}</p>
            @endif

            <div class="accordion" id="faqAccordion">
                @foreach($faqItems as $index => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $index }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-controls="collapse{{ $index }}">
                                {{ $faq['question'] }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}"
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                             aria-labelledby="heading{{ $index }}"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">{{ $faq['answer'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
