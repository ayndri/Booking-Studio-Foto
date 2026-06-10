<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — UPFotoStudio</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f3f0;
            color: #111;
            margin: 0;
        }

        /* ── TOPBAR ─────────────────────── */
        .topbar {
            position: sticky; top: 0; z-index: 100;
            background: #fff;
            border-bottom: 1px solid rgba(0,0,0,.07);
            box-shadow: 0 1px 10px rgba(0,0,0,.05);
            padding: 0 24px;
            height: 58px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-brand {
            display: flex; align-items: center; gap: 9px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700; font-size: 1rem;
            letter-spacing: -.01em; color: #111;
            text-decoration: none;
        }
        .topbar-brand .bmark {
            width: 30px; height: 30px; background: #2f5443; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .topbar-brand .bmark svg { display: block; }
        .topbar-brand .up { color: #2f5443; }

        .topbar-right {
            display: flex; align-items: center; gap: 14px;
        }
        .topbar-user {
            font-size: .8rem; color: #666;
        }
        .topbar-user strong { color: #111; font-weight: 600; }
        .btn-logout {
            font-family: 'Poppins', sans-serif;
            font-size: .78rem; font-weight: 600;
            color: #555; background: transparent;
            border: 1.5px solid rgba(0,0,0,.12); border-radius: 8px;
            padding: 5px 14px; cursor: pointer;
            transition: border-color 150ms ease, color 150ms ease;
        }
        .btn-logout:hover { border-color: #dc2626; color: #dc2626; }

        /* ── LAYOUT ─────────────────────── */
        .dash-wrap {
            display: flex; min-height: calc(100vh - 58px);
        }

        /* ── SIDEBAR ────────────────────── */
        .sidebar {
            width: 220px; flex-shrink: 0;
            background: #fff;
            border-right: 1px solid rgba(0,0,0,.07);
            padding: 18px 12px;
            position: sticky; top: 58px;
            height: calc(100vh - 58px);
            overflow-y: auto;
        }
        .sidebar-section {
            font-size: .64rem; font-weight: 700;
            letter-spacing: .14em; text-transform: uppercase;
            color: #bbb; padding: 0 8px;
            margin-top: 16px; margin-bottom: 6px;
        }
        .sidebar-section:first-child { margin-top: 0; }
        .sidebar .nav-link {
            font-family: 'Poppins', sans-serif;
            font-size: .84rem; font-weight: 500;
            color: #555; padding: 8px 10px;
            border-radius: 9px;
            transition: background 140ms ease, color 140ms ease;
            display: flex; justify-content: space-between; align-items: center;
        }
        .sidebar .nav-link:hover { background: #eef7f2; color: #2f5443; }
        .sidebar .nav-link.active { background: #eef7f2; color: #2f5443; font-weight: 600; }
        .sidebar .submenu .nav-link {
            font-size: .8rem; padding-left: 20px; color: #777;
        }
        .sidebar .submenu .nav-link.active { color: #2f5443; background: #eef7f2; }

        /* ── MAIN CONTENT ───────────────── */
        .dash-main {
            flex: 1; padding: 28px 28px 48px;
            min-width: 0;
        }
        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 24px;
        }
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.4rem, 2vw, 1.8rem);
            font-weight: 700; color: #111; margin: 0;
        }

        /* ── ALERT ──────────────────────── */
        .alert { border-radius: 10px; font-size: .88rem; margin-bottom: 20px; }

        /* ── SHARED CARD ────────────────── */
        .d-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,.07);
            border-radius: 14px;
            box-shadow: 0 1px 8px rgba(0,0,0,.04);
        }

        /* ── TABLE ──────────────────────── */
        .d-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .d-table thead th {
            font-size: .72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .06em; color: #888; background: #fafaf8;
            padding: 11px 14px; border-bottom: 1px solid rgba(0,0,0,.07);
        }
        .d-table thead th:first-child { border-radius: 0; }
        .d-table tbody td {
            padding: 13px 14px; border-bottom: 1px solid rgba(0,0,0,.05);
            font-size: .88rem; color: #333; vertical-align: middle;
        }
        .d-table tbody tr:last-child td { border-bottom: none; }
        .d-table tbody tr:hover td { background: #fafaf8; }

        /* ── BUTTONS ────────────────────── */
        .btn-g {
            display: inline-flex; align-items: center; gap: 6px;
            background: #2f5443; color: #fff; border: none;
            border-radius: 8px; padding: 7px 16px;
            font-family: 'Poppins', sans-serif; font-size: .82rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: background 160ms ease;
        }
        .btn-g:hover { background: #1f3d30; color: #fff; }
        .btn-edit {
            display: inline-flex; align-items: center; gap: 4px;
            background: #f4f3f0; color: #444; border: 1px solid rgba(0,0,0,.1);
            border-radius: 7px; padding: 5px 12px;
            font-family: 'Poppins', sans-serif; font-size: .78rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: background 140ms ease, border-color 140ms ease;
        }
        .btn-edit:hover { background: #e8e5e0; color: #111; border-color: rgba(0,0,0,.2); }
        .btn-del {
            display: inline-flex; align-items: center; gap: 4px;
            background: #fff1f2; color: #be123c; border: 1px solid #fecdd3;
            border-radius: 7px; padding: 5px 12px;
            font-family: 'Poppins', sans-serif; font-size: .78rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: background 140ms ease;
        }
        .btn-del:hover { background: #ffe4e6; }

        /* ── BADGE ──────────────────────── */
        .dbadge {
            display: inline-block; padding: 3px 10px; border-radius: 999px;
            font-size: .7rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
        }
        .dbadge-active   { background: #dcfce7; color: #15803d; }
        .dbadge-inactive { background: #f4f3f0; color: #888; }
        .dbadge-pending  { background: #fff7ed; color: #c2410c; }
        .dbadge-success  { background: #dcfce7; color: #15803d; }
        .dbadge-failed   { background: #fff1f2; color: #be123c; }
        .dbadge-expired  { background: #f4f3f0; color: #6b7280; }
        .dbadge-unread   { background: #fef3c7; color: #92400e; }
        .dbadge-read     { background: #f4f3f0; color: #888; }

        /* ── FILTER BAR ──────────────────── */
        .filter-bar { background:#fff;border:1px solid rgba(0,0,0,.07);border-radius:14px;padding:16px 20px;margin-bottom:20px;box-shadow:0 1px 8px rgba(0,0,0,.04);display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px; }
        .filter-field { display:flex;flex-direction:column;gap:4px;min-width:150px; }
        .filter-field label { font-size:.7rem;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.06em; }
        .filter-actions { display:flex;gap:8px;align-items:flex-end; }

        /* ── FORM STYLES ─────────────────── */
        .d-form { background:#fff;border:1px solid rgba(0,0,0,.07);border-radius:14px;padding:28px 30px;box-shadow:0 1px 8px rgba(0,0,0,.04); }
        .d-field { margin-bottom:20px; }
        .d-label { display:block;font-size:.78rem;font-weight:600;color:#555;margin-bottom:5px; }
        .d-input,.d-select,.d-textarea { width:100%;border:1.5px solid rgba(0,0,0,.1);border-radius:9px;padding:9px 13px;font-family:'Poppins',sans-serif;font-size:.88rem;color:#111;background:#fff;outline:none;transition:border-color 200ms ease,box-shadow 200ms ease; }
        .d-input:focus,.d-select:focus,.d-textarea:focus { border-color:rgba(47,84,67,.4);box-shadow:0 0 0 3px rgba(47,84,67,.08); }
        .d-textarea { resize:vertical;min-height:90px;line-height:1.6; }
        .d-hint { font-size:.72rem;color:#888;margin-top:4px; }
        .d-check { display:inline-flex;align-items:center;gap:8px;cursor:pointer; }
        .d-check input[type=checkbox] { width:16px;height:16px;accent-color:#2f5443;cursor:pointer;flex-shrink:0; }
        .d-check span { font-size:.88rem;font-weight:500;color:#333; }
        .d-row2 { display:grid;grid-template-columns:1fr 1fr;gap:20px; }
        .d-row3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px; }
        .btn-back { display:inline-flex;align-items:center;gap:6px;background:#f4f3f0;color:#555;border:1px solid rgba(0,0,0,.1);border-radius:8px;padding:7px 16px;font-family:'Poppins',sans-serif;font-size:.82rem;font-weight:500;cursor:pointer;text-decoration:none;transition:background 140ms ease; }
        .btn-back:hover { background:#e8e5e0;color:#111; }
        .btn-export { display:inline-flex;align-items:center;gap:6px;background:#fff1f2;color:#be123c;border:1px solid #fecdd3;border-radius:8px;padding:7px 16px;font-family:'Poppins',sans-serif;font-size:.82rem;font-weight:600;cursor:pointer;text-decoration:none;transition:background 140ms ease; }
        .btn-export:hover { background:#ffe4e6;color:#be123c; }
        @media(max-width:767.98px){ .d-row2,.d-row3{grid-template-columns:1fr;} .filter-bar{flex-direction:column;align-items:stretch;} .filter-field{min-width:0;} }
    </style>
    @stack('styles')
</head>
<body>

@php
    $isAdminArea = request()->routeIs('admin.*');
    $isOwnerArea = request()->routeIs('owner.*');
    $dashboardUser = $isOwnerArea ? auth('owner')->user() : auth('admin')->user();
    $logoutRoute = $isOwnerArea ? route('owner.logout') : route('admin.logout');
    $contentSection = request()->query('section', 'all');
    if (in_array($contentSection, ['promo', 'services'], true)) { $contentSection = 'home'; }
    $homeMenu = strtolower(trim((string) request()->query('home_menu')));
    if (!in_array($homeMenu, ['carousel','header','gallery','services','faq','footer'], true)) { $homeMenu = ''; }
    $activeHomeMenu = '';
    if ($contentSection === 'home') { $activeHomeMenu = $homeMenu; }
    elseif ($contentSection === 'footer') { $activeHomeMenu = 'footer'; }
    $isHomeContentOpen = request()->routeIs('admin.contents.*') && ($contentSection === 'home' || $contentSection === 'footer');
    $isOtherContentOpen = request()->routeIs('admin.contents.*') && $contentSection === 'terms';
@endphp

{{-- TOPBAR --}}
<header class="topbar">
    <a href="{{ $isOwnerArea ? route('owner.dashboard') : route('admin.dashboard') }}" class="topbar-brand">
        <div class="bmark">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <rect x="1" y="4.5" width="14" height="10" rx="2" fill="white" fill-opacity=".9"/>
                <circle cx="8" cy="9.5" r="3" fill="#2f5443"/>
                <rect x="4.5" y="1" width="7" height="3.5" rx="1" fill="white" fill-opacity=".7"/>
                <circle cx="8" cy="9.5" r="1.4" fill="#eef7f2"/>
            </svg>
        </div>
        <span><span class="up">UP</span>FotoStudio</span>
    </a>
    <div class="topbar-right">
        <span class="topbar-user">
            <strong>{{ $dashboardUser->name ?? 'User' }}</strong>
            · {{ strtoupper($dashboardUser->role ?? '-') }}
        </span>
        <form method="post" action="{{ $logoutRoute }}">
            @csrf
            <button class="btn-logout" type="submit">Logout</button>
        </form>
    </div>
</header>

{{-- BODY --}}
<div class="dash-wrap">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <nav>
            @if($isAdminArea)
                <p class="sidebar-section">Admin Area</p>
                <ul class="nav nav-pills flex-column gap-1">
                    <li><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">📊 Dashboard</a></li>
                    <li><a class="nav-link {{ request()->routeIs('admin.studios.*') ? 'active' : '' }}" href="{{ route('admin.studios.index') }}">🏢 Studio</a></li>
                    <li><a class="nav-link {{ request()->routeIs('admin.service-packages.*') ? 'active' : '' }}" href="{{ route('admin.service-packages.index') }}">📦 Paket Layanan</a></li>
                    <li><a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">📅 Booking</a></li>
                    <li><a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">💳 Transaksi</a></li>
                    <li><a class="nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}" href="{{ route('admin.contact-messages.index') }}">✉️ Pesan Kontak</a></li>
                    <li><a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">📈 Laporan</a></li>
                </ul>

                <p class="sidebar-section" style="margin-top:20px;">Konten Website</p>
                <ul class="nav nav-pills flex-column gap-1">
                    <li>
                        <a class="nav-link d-flex justify-content-between {{ $isHomeContentOpen ? 'active' : '' }}"
                           data-bs-toggle="collapse" href="#hmenu" role="button"
                           aria-expanded="{{ $isHomeContentOpen ? 'true' : 'false' }}">
                            <span>🏠 Beranda</span><span>▾</span>
                        </a>
                        <div class="collapse {{ $isHomeContentOpen ? 'show' : '' }}" id="hmenu">
                            <ul class="nav flex-column gap-1 submenu mt-1">
                                <li><a class="nav-link {{ $activeHomeMenu==='carousel'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'home','home_menu'=>'carousel']) }}">Carousel</a></li>
                                <li><a class="nav-link {{ $activeHomeMenu==='header'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'home','home_menu'=>'header']) }}">Header</a></li>
                                <li><a class="nav-link {{ $activeHomeMenu==='gallery'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'home','home_menu'=>'gallery']) }}">Galeri</a></li>
                                <li><a class="nav-link {{ $activeHomeMenu==='services'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'home','home_menu'=>'services']) }}">Layanan</a></li>
                                <li><a class="nav-link {{ $activeHomeMenu==='faq'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'home','home_menu'=>'faq']) }}">FAQ</a></li>
                                <li><a class="nav-link {{ $activeHomeMenu==='footer'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'footer','home_menu'=>'footer']) }}">Footer</a></li>
                            </ul>
                        </div>
                    </li>
                    <li><a class="nav-link {{ request()->routeIs('admin.contents.*')&&$contentSection==='about'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'about']) }}">👥 Tentang Kami</a></li>
                    <li><a class="nav-link {{ request()->routeIs('admin.contents.*')&&$contentSection==='gallery'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'gallery']) }}">🖼️ Galeri</a></li>
                    <li><a class="nav-link {{ request()->routeIs('admin.contents.*')&&$contentSection==='pricing'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'pricing']) }}">💰 Paket Harga</a></li>
                    <li><a class="nav-link {{ request()->routeIs('admin.contents.*')&&$contentSection==='contact'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'contact']) }}">📞 Kontak</a></li>
                    <li>
                        <a class="nav-link d-flex justify-content-between {{ $isOtherContentOpen?'active':'' }}"
                           data-bs-toggle="collapse" href="#omenu" role="button"
                           aria-expanded="{{ $isOtherContentOpen?'true':'false' }}">
                            <span>⋯ Lainnya</span><span>▾</span>
                        </a>
                        <div class="collapse {{ $isOtherContentOpen?'show':'' }}" id="omenu">
                            <ul class="nav flex-column gap-1 submenu mt-1">
                                <li><a class="nav-link {{ request()->routeIs('admin.contents.*')&&$contentSection==='terms'?'active':'' }}" href="{{ route('admin.contents.index', ['section'=>'terms']) }}">S&amp;K</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            @endif

            @if($isOwnerArea)
                <p class="sidebar-section">Owner Area</p>
                <ul class="nav nav-pills flex-column gap-1">
                    <li><a class="nav-link {{ request()->routeIs('owner.dashboard')?'active':'' }}" href="{{ route('owner.dashboard') }}">📊 Dashboard</a></li>
                    <li><a class="nav-link {{ request()->routeIs('owner.reports.*')?'active':'' }}" href="{{ route('owner.reports.index') }}">📈 Laporan</a></li>
                </ul>
            @endif
        </nav>
    </aside>

    {{-- MAIN --}}
    <main class="dash-main">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
