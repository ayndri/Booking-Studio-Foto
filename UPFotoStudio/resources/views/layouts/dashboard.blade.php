<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard UPFotoStudio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f6f8fb;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background: #0f172a;
        }
        .sidebar .nav-link {
            color: #cbd5e1;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
            border-radius: 8px;
        }
        .sidebar .submenu .nav-link {
            font-size: 0.92rem;
            padding-left: 1.25rem;
        }
    </style>
</head>
<body>
@php
    $isAdminArea = request()->routeIs('admin.*');
    $isOwnerArea = request()->routeIs('owner.*');
    $dashboardUser = $isOwnerArea ? auth('owner')->user() : auth('admin')->user();
    $logoutRoute = $isOwnerArea ? route('owner.logout') : route('admin.logout');
    $contentSection = request()->query('section', 'all');
    if (in_array($contentSection, ['promo', 'services'], true)) {
        $contentSection = 'home';
    }
    $homeMenu = strtolower(trim((string) request()->query('home_menu')));
    if (!in_array($homeMenu, ['carousel', 'header', 'gallery', 'services', 'faq', 'footer'], true)) {
        $homeMenu = '';
    }
    $activeHomeMenu = '';
    if ($contentSection === 'home') {
        $activeHomeMenu = $homeMenu;
    } elseif ($contentSection === 'footer') {
        $activeHomeMenu = 'footer';
    }
    $isHomeContentOpen = request()->routeIs('admin.contents.*') && ($contentSection === 'home' || $contentSection === 'footer');
    $isOtherContentOpen = request()->routeIs('admin.contents.*') && $contentSection === 'terms';
@endphp
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Dashboard UPFotoStudio</span>
        <div class="d-flex align-items-center gap-3 text-white">
            <small>{{ $dashboardUser->name ?? 'User' }} ({{ strtoupper($dashboardUser->role ?? '-') }})</small>
            <form method="post" action="{{ $logoutRoute }}">
                @csrf
                <button class="btn btn-sm btn-outline-light" type="submit">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-lg-2 sidebar p-3">
            <ul class="nav nav-pills flex-column gap-1">
                @if($isAdminArea)
                    <li class="text-uppercase text-secondary small mt-2 mb-1 px-2">Admin Area</li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.studios.*') ? 'active' : '' }}" href="{{ route('admin.studios.index') }}">Studio</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.service-packages.*') ? 'active' : '' }}" href="{{ route('admin.service-packages.index') }}">Paket Layanan</a></li>
                    <li class="text-uppercase text-secondary small mt-3 mb-1 px-2">Website Content</li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center {{ $isHomeContentOpen ? 'active' : '' }}"
                           data-bs-toggle="collapse"
                           href="#homeContentMenu"
                           role="button"
                           aria-expanded="{{ $isHomeContentOpen ? 'true' : 'false' }}"
                           aria-controls="homeContentMenu">
                            <span>Beranda</span>
                            <span class="small">v</span>
                        </a>
                        <div class="collapse {{ $isHomeContentOpen ? 'show' : '' }}" id="homeContentMenu">
                            <ul class="nav flex-column gap-1 submenu mt-1">
                                <li class="nav-item"><a class="nav-link {{ $activeHomeMenu === 'carousel' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'home', 'home_menu' => 'carousel']) }}">Carousel</a></li>
                                <li class="nav-item"><a class="nav-link {{ $activeHomeMenu === 'header' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'home', 'home_menu' => 'header']) }}">Header</a></li>
                                <li class="nav-item"><a class="nav-link {{ $activeHomeMenu === 'gallery' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'home', 'home_menu' => 'gallery']) }}">Galeri</a></li>
                                <li class="nav-item"><a class="nav-link {{ $activeHomeMenu === 'services' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'home', 'home_menu' => 'services']) }}">Layanan</a></li>
                                <li class="nav-item"><a class="nav-link {{ $activeHomeMenu === 'faq' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'home', 'home_menu' => 'faq']) }}">FAQ</a></li>
                                <li class="nav-item"><a class="nav-link {{ $activeHomeMenu === 'footer' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'footer', 'home_menu' => 'footer']) }}">Footer</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.contents.*') && $contentSection === 'about' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'about']) }}">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.contents.*') && $contentSection === 'gallery' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'gallery']) }}">Galeri Keseluruhan</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.contents.*') && $contentSection === 'pricing' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'pricing']) }}">Paket Harga</a></li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center {{ $isOtherContentOpen ? 'active' : '' }}"
                           data-bs-toggle="collapse"
                           href="#otherContentMenu"
                           role="button"
                           aria-expanded="{{ $isOtherContentOpen ? 'true' : 'false' }}"
                           aria-controls="otherContentMenu">
                            <span>Lainnya</span>
                            <span class="small">v</span>
                        </a>
                        <div class="collapse {{ $isOtherContentOpen ? 'show' : '' }}" id="otherContentMenu">
                            <ul class="nav flex-column gap-1 submenu mt-1">
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.contents.*') && $contentSection === 'terms' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'terms']) }}">S&amp;K</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.contents.*') && $contentSection === 'contact' ? 'active' : '' }}" href="{{ route('admin.contents.index', ['section' => 'contact']) }}">Kontak</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">Booking</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">Transaksi</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}" href="{{ route('admin.contact-messages.index') }}">Pesan Kontak</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">Laporan</a></li>
                @endif

                @if($isOwnerArea)
                    <li class="text-uppercase text-secondary small mt-2 mb-1 px-2">Owner Area</li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}" href="{{ route('owner.reports.index') }}">Laporan</a></li>
                @endif
            </ul>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
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
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
