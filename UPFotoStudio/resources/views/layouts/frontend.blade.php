<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'UPFotoStudio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap');

        body {
            background: linear-gradient(180deg, #f8fafc 0%, #eef5ff 100%);
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            overflow-x: hidden;
        }
        html {
            overflow-x: hidden;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .navbar {
            backdrop-filter: blur(8px);
            position: relative;
            z-index: 1080;
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
        }
        .navbar .nav-link {
            padding-top: 0.55rem;
            padding-bottom: 0.55rem;
        }
        .navbar .dropdown-menu {
            z-index: 1081;
        }
        main {
            position: relative;
            z-index: 1;
        }
        .site-footer {
            margin-top: 0;
            background: linear-gradient(135deg, #2f5443 0%, #335947 45%, #294b3d 100%);
            color: #e6ecdf;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
        }
        .site-footer .footer-wrap {
            padding-top: 44px;
            padding-bottom: 26px;
        }
        .site-footer .footer-grid {
            --bs-gutter-x: clamp(2rem, 4vw, 4.6rem);
            --bs-gutter-y: 1.4rem;
            align-items: flex-start;
        }
        .site-footer .footer-brand {
            letter-spacing: 0.24em;
            font-size: 1.95rem;
            font-weight: 800;
            margin-bottom: 12px;
            color: #f1f4e7;
        }
        .site-footer .footer-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.95rem;
            margin-bottom: 14px;
            color: #f1f4e7;
        }
        .site-footer .footer-text {
            color: rgba(232, 239, 229, 0.88);
            line-height: 1.8;
        }
        .site-footer .footer-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 10px;
        }
        .site-footer .footer-col-links {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .site-footer .footer-col-links .footer-title {
            width: max-content;
            text-align: center;
        }
        .site-footer .footer-col-links .footer-links {
            width: max-content;
            text-align: left;
        }
        .site-footer .footer-links a {
            color: #e6ecdf;
            text-decoration: none;
            font-size: 1.08rem;
            transition: opacity 160ms ease;
        }
        .site-footer .footer-links a:hover {
            opacity: 0.8;
        }
        .site-footer .footer-contact {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 12px;
            color: rgba(232, 239, 229, 0.92);
            font-size: 1.1rem;
        }
        .site-footer .footer-col-contact {
            padding-left: clamp(0px, 1vw, 14px);
        }
        .site-footer .social-list {
            display: flex;
            gap: 10px;
            margin-top: 18px;
            flex-wrap: wrap;
        }
        .site-footer .social-item {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid rgba(232, 239, 229, 0.5);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #e6ecdf;
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 700;
        }
        .site-footer .footer-bottom {
            margin-top: 28px;
            border-top: 1px solid rgba(232, 239, 229, 0.22);
            padding-top: 16px;
            text-align: center;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-size: 0.78rem;
            color: rgba(232, 239, 229, 0.86);
        }
        @media (max-width: 767.98px) {
            .navbar {
                padding-top: 0.65rem;
                padding-bottom: 0.65rem;
            }
            .site-footer .footer-col-links {
                align-items: flex-start;
            }
            .site-footer .footer-col-links .footer-title,
            .site-footer .footer-col-links .footer-links {
                width: auto;
                text-align: left;
            }
            .site-footer .footer-brand {
                font-size: 1.5rem;
            }
            .site-footer .footer-title {
                font-size: 1.55rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('frontend.home') }}">UPFotoStudio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.about') }}">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.gallery') }}">Galeri</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.pricing') }}">Paket Harga</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Lainnya</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('frontend.terms') }}">S&amp;K</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.contact') }}">Kontak</a></li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-primary btn-sm mt-1" href="{{ route('frontend.pricing') }}">Booking Sekarang</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="pt-0 pb-0">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<footer class="site-footer">
    @php
        $footerContactLines = collect(preg_split('/\r\n|\r|\n|\|/', (string) ($footerContent['contact'] ?? '')))
            ->map(fn ($line) => trim((string) $line))
            ->filter(fn ($line) => $line !== '')
            ->values();
    @endphp
    <div class="container footer-wrap">
        <div class="row footer-grid">
            <div class="col-lg-4 footer-col-brand">
                <div class="footer-brand">{{ $footerContent['brand'] ?? 'UPStudio' }}</div>
                <p class="footer-text mb-0">{{ $footerContent['description'] ?? '' }}</p>
                <div class="social-list">
                    <a class="social-item" href="#" aria-label="Facebook">Fb</a>
                    <a class="social-item" href="#" aria-label="Twitter">Tw</a>
                    <a class="social-item" href="#" aria-label="Instagram">Ig</a>
                    <a class="social-item" href="#" aria-label="Pinterest">Pi</a>
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
                <h3 class="footer-title">Tetap bersama kami</h3>
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
            {{ $footerContent['copyright'] ?? ('Copyright ' . now()->year . ' UPFotoStudio.') }}
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
