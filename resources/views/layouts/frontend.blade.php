<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'UPFotoStudio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,700&family=Poppins:wght@400;500;600;700&display=swap');

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: #ffffff;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            color: #111111;
        }
        html { overflow-x: hidden; }

        /* ── Navbar ─────────────────────────── */
        .navbar {
            background: rgba(255,255,255,.97) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0,0,0,.06) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,.06);
            position: relative;
            z-index: 1080;
            padding: .9rem clamp(40px, 8vw, 120px);
            /* top accent bar */
            border-top: 3px solid #2f5443 !important;
        }

        /* brand */
        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -.02em;
            color: #111 !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: opacity 180ms ease;
        }
        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px; height: 28px;
            background: #2f5443;
            border-radius: 7px;
            flex-shrink: 0;
        }
        .brand-mark svg { display: block; }
        .navbar-brand .brand-up   { color: #2f5443; }
        .navbar-brand .brand-rest { color: #111; }
        .navbar-brand:hover { opacity: .78; }

        /* nav links — pill hover */
        .navbar .nav-link {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: .87rem;
            color: #555 !important;
            padding: .5rem 1.1rem;
            border-radius: 10px;
            transition: background 180ms ease, color 180ms ease;
        }
        .navbar .nav-link:hover {
            background: rgba(47,84,67,.07);
            color: #2f5443 !important;
        }
        .navbar .nav-link.active {
            background: rgba(47,84,67,.1);
            color: #2f5443 !important;
            font-weight: 600;
        }

        /* booking button — standalone, no nav-link side-effects */
        .navbar .btn-book {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #2f5443;
            color: #fff;
            border-radius: 999px;
            padding: .52rem 1.3rem;
            font-family: 'Poppins', sans-serif;
            font-size: .84rem;
            font-weight: 600;
            text-decoration: none;
            letter-spacing: .01em;
            border: 2px solid transparent;
            box-shadow: 0 4px 14px rgba(47,84,67,.22);
            transition: background 200ms ease, box-shadow 200ms ease, transform 200ms ease, border-color 200ms ease;
        }
        .navbar .btn-book:hover {
            background: #fff;
            color: #2f5443;
            border-color: #2f5443;
            box-shadow: 0 6px 20px rgba(47,84,67,.18);
            transform: translateY(-1px);
        }
        .navbar .btn-book .arr {
            display: inline-block;
            transition: transform 200ms ease;
        }
        .navbar .btn-book:hover .arr { transform: translateX(3px); }

        .navbar-toggler {
            border-color: rgba(0,0,0,.14);
            padding: .3rem .55rem;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280%2C0%2C0%2C0.7%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        @media (max-width: 767.98px) { .navbar { padding: .7rem 0; } }

        /* ── Main ────────────────────────────── */
        main { position: relative; z-index: 1; }

        /* ── Footer ──────────────────────────── */
        .site-footer {
            background: #111111;
            color: #d4d4d4;
            border-top: 1px solid rgba(255,255,255,.06);
        }
        .site-footer .footer-wrap { padding: 52px 0 28px; }
        .site-footer .footer-grid {
            --bs-gutter-x: clamp(2rem,4vw,4.6rem);
            --bs-gutter-y: 1.6rem;
            align-items: flex-start;
        }
        .site-footer .footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -.01em;
            color: #ffffff;
            margin-bottom: 12px;
        }
        .site-footer .footer-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 18px;
        }
        .site-footer .footer-text {
            color: rgba(212,212,212,.72);
            line-height: 1.75;
            font-size: .95rem;
        }
        .site-footer .footer-links {
            list-style: none; margin: 0; padding: 0;
            display: grid; gap: 10px;
        }
        .site-footer .footer-col-links {
            display: flex; flex-direction: column; align-items: flex-start;
        }
        .site-footer .footer-col-links .footer-title,
        .site-footer .footer-col-links .footer-links { width: auto; }
        .site-footer .footer-links a {
            color: rgba(212,212,212,.75);
            text-decoration: none;
            font-size: .95rem;
            display: inline-block;
            transition: color 160ms ease, padding-left 160ms ease;
        }
        .site-footer .footer-links a:hover { color: #fff; padding-left: 4px; }
        .site-footer .footer-contact {
            list-style: none; margin: 0; padding: 0;
            display: grid; gap: 11px;
            color: rgba(212,212,212,.8);
            font-size: .95rem; line-height: 1.6;
        }
        .site-footer .footer-col-contact {
            padding-left: clamp(0px,1vw,14px);
        }
        .site-footer .social-list {
            display: flex; gap: 8px; margin-top: 18px; flex-wrap: wrap;
        }
        .site-footer .social-item {
            width: 36px; height: 36px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.18);
            display: inline-flex; align-items: center; justify-content: center;
            color: rgba(212,212,212,.85);
            text-decoration: none;
            transition: background 160ms ease, border-color 160ms ease, transform 160ms ease, color 160ms ease;
        }
        .site-footer .social-item svg { width: 17px; height: 17px; fill: currentColor; }
        .site-footer .social-item:hover {
            background: rgba(255,255,255,.1);
            border-color: rgba(255,255,255,.5);
            transform: translateY(-2px);
            color: #fff;
        }
        .site-footer .footer-bottom {
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,.08);
            padding-top: 16px;
            text-align: center;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-size: .72rem;
            color: rgba(212,212,212,.45);
        }

        @media (max-width: 767.98px) {
            .navbar { padding: .65rem 0; }
            .site-footer .footer-col-links { align-items: flex-start; }
            .site-footer .footer-col-links .footer-title,
            .site-footer .footer-col-links .footer-links { width: auto; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('frontend.home') }}">
            <span class="brand-mark" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <rect x="1" y="4" width="12" height="9" rx="2" fill="white" fill-opacity=".9"/>
                    <circle cx="7" cy="8.5" r="2.5" fill="#2f5443"/>
                    <rect x="4" y="1" width="6" height="3" rx="1" fill="white" fill-opacity=".7"/>
                </svg>
            </span>
            <span><span class="brand-up">UP</span><span class="brand-rest">FotoStudio</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.home')    ? 'active' : '' }}" href="{{ route('frontend.home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.gallery') ? 'active' : '' }}" href="{{ route('frontend.gallery') }}">Galeri</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.pricing') ? 'active' : '' }}" href="{{ route('frontend.pricing') }}">Paket Harga</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.contact') ? 'active' : '' }}" href="{{ route('frontend.contact') }}">Kontak</a></li>
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <a class="btn-book" href="{{ route('frontend.pricing') }}">
                        Booking Sekarang <span class="arr">&#8594;</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="pt-0 pb-0">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>
</main>

<footer class="site-footer">
    @php
        $footerContactLines = collect(preg_split('/\r\n|\r|\n|\|/', (string)($footerContent['contact'] ?? '')))
            ->map(fn($l) => trim((string)$l))->filter(fn($l) => $l !== '')->values();
    @endphp
    <div class="container footer-wrap">
        <div class="row footer-grid">
            <div class="col-lg-4">
                <div class="footer-brand">{{ $footerContent['brand'] ?? 'UPFotoStudio' }}</div>
                <p class="footer-text mb-0">{{ $footerContent['description'] ?? '' }}</p>
                <div class="social-list">
                    <a class="social-item" href="#" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 01-1.38-.9 3.7 3.7 0 01-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16zm0 1.62c-3.15 0-3.52.01-4.76.07-.9.04-1.39.19-1.71.32-.43.17-.74.37-1.06.69-.32.32-.52.63-.69 1.06-.13.32-.28.81-.32 1.71-.06 1.24-.07 1.61-.07 4.76s.01 3.52.07 4.76c.04.9.19 1.39.32 1.71.17.43.37.74.69 1.06.32.32.63.52 1.06.69.32.13.81.28 1.71.32 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c.9-.04 1.39-.19 1.71-.32.43-.17.74-.37 1.06-.69.32-.32.52-.63.69-1.06.13-.32.28-.81.32-1.71.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.04-.9-.19-1.39-.32-1.71a2.85 2.85 0 00-.69-1.06 2.85 2.85 0 00-1.06-.69c-.32-.13-.81-.28-1.71-.32-1.24-.06-1.61-.07-4.76-.07zm0 2.76a5.3 5.3 0 110 10.6 5.3 5.3 0 010-10.6zm0 8.74a3.44 3.44 0 100-6.88 3.44 3.44 0 000 6.88zm6.74-8.94a1.24 1.24 0 11-2.48 0 1.24 1.24 0 012.48 0z"/></svg>
                    </a>
                    <a class="social-item" href="#" aria-label="TikTok">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16.6 5.82a4.28 4.28 0 01-1.05-2.82h-3.1v12.5a2.54 2.54 0 01-2.54 2.45 2.52 2.52 0 01-2.52-2.52 2.52 2.52 0 012.52-2.52c.26 0 .52.04.76.12v-3.16a5.7 5.7 0 00-.76-.05A5.66 5.66 0 003.8 15.43a5.66 5.66 0 005.66 5.66 5.66 5.66 0 005.66-5.66V9.01a7.32 7.32 0 004.27 1.37V7.27a4.28 4.28 0 01-2.79-1.45z"/></svg>
                    </a>
                    <a class="social-item" href="#" aria-label="WhatsApp">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 004.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm0 1.82c2.17 0 4.21.84 5.74 2.38a8.06 8.06 0 012.38 5.73c0 4.54-3.7 8.23-8.24 8.23-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.18 8.18 0 01-1.26-4.37c0-4.54 3.69-8.23 8.23-8.23zm-4.5 4.43c-.21 0-.55.08-.84.39-.29.31-1.1 1.08-1.1 2.63s1.13 3.05 1.29 3.26c.16.21 2.22 3.39 5.38 4.62.75.29 1.33.46 1.79.59.75.24 1.43.2 1.97.12.6-.09 1.85-.76 2.11-1.49.26-.73.26-1.36.18-1.49-.08-.13-.29-.21-.6-.37-.31-.16-1.85-.91-2.13-1.02-.29-.1-.5-.16-.71.16-.21.31-.81 1.02-1 1.23-.18.21-.37.24-.68.08-.31-.16-1.32-.49-2.51-1.55-.93-.83-1.55-1.85-1.74-2.16-.18-.31-.02-.48.14-.63.14-.14.31-.37.47-.55.16-.18.21-.31.31-.52.1-.21.05-.39-.03-.55-.08-.16-.7-1.7-.97-2.33-.25-.6-.51-.52-.71-.53-.18-.01-.39-.01-.6-.01z"/></svg>
                    </a>
                    <a class="social-item" href="#" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.51 1.49-3.9 3.78-3.9 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.78-1.63 1.57v1.88h2.78l-.44 2.91h-2.34V22c4.78-.76 8.44-4.92 8.44-9.94z"/></svg>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 footer-col-links">
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('frontend.home') }}">Beranda</a></li>
                    <li><a href="{{ route('frontend.about') }}">Tentang Kami</a></li>
                    <li><a href="{{ route('frontend.gallery') }}">Galeri</a></li>
                    <li><a href="{{ route('frontend.pricing') }}">Paket Harga</a></li>
                    <li><a href="{{ route('frontend.contact') }}">Kontak</a></li>
                    <li><a href="{{ route('frontend.terms') }}">Syarat &amp; Ketentuan</a></li>
                </ul>
            </div>
            <div class="col-lg-4 footer-col-contact">
                <h3 class="footer-title">Kontak Kami</h3>
                <ul class="footer-contact">
                    @forelse($footerContactLines as $line)
                        <li>{{ $line }}</li>
                    @empty
                        <li>Surabaya, Indonesia</li>
                        <li>hello@upfotostudio.test</li>
                        <li>(+62) 812 0000 0000</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            {{ $footerContent['copyright'] ?? ('Copyright ' . now()->year . ' UPFotoStudio. All rights reserved.') }}
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
