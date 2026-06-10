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
            display: flex; flex-direction: column; align-items: center;
        }
        .site-footer .footer-col-links .footer-title,
        .site-footer .footer-col-links .footer-links { width: max-content; }
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
            color: #d4d4d4;
            text-decoration: none;
            font-size: .78rem; font-weight: 600;
            transition: background 160ms ease, border-color 160ms ease, transform 160ms ease;
        }
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
                    <a class="social-item" href="#" aria-label="Instagram">Ig</a>
                    <a class="social-item" href="#" aria-label="TikTok">Tk</a>
                    <a class="social-item" href="#" aria-label="WhatsApp">Wa</a>
                    <a class="social-item" href="#" aria-label="Facebook">Fb</a>
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
