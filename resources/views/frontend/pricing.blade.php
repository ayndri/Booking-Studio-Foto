@extends('layouts.frontend')
@section('title', 'Paket Harga - UPFotoStudio')

@push('styles')
<style>
/* ── Base ─────────────────────────────────── */
.prc {
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
.prc h1,.prc h2,.prc h3 { font-family: 'Playfair Display', serif; letter-spacing: -.02em; }
.pc { width: var(--w); margin-inline: auto; }

/* ── HERO ─────────────────────────────────── */
.prc-hero {
    background: var(--bg);
    padding: clamp(52px,6vw,80px) 0 clamp(36px,4vw,52px);
    border-bottom: 1px solid var(--br);
}
.prc-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--gp); color: var(--g);
    border-radius: 999px; padding: 6px 14px;
    font-size: .7rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    margin-bottom: 18px;
}
.prc-badge i { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: var(--gl); }
.prc-h1 {
    font-size: clamp(2.4rem,4.5vw,4rem);
    line-height: 1.06; color: var(--k); margin-bottom: 14px;
}
.prc-lead {
    font-size: clamp(.97rem,1.25vw,1.06rem);
    color: var(--ks); max-width: 520px; line-height: 1.74; margin-bottom: 28px;
}

/* filter chips */
.prc-filters {
    display: flex; flex-wrap: wrap; gap: 8px;
}
.filter-chip {
    display: inline-block;
    border: 1.5px solid rgba(0,0,0,.12);
    color: var(--ks);
    border-radius: 999px;
    padding: 8px 18px;
    text-decoration: none;
    font-weight: 500;
    font-size: .84rem;
    font-family: 'Poppins', sans-serif;
    transition: border-color 180ms ease, background 180ms ease, color 180ms ease;
}
.filter-chip:hover {
    border-color: var(--g);
    color: var(--g);
}
.filter-chip.active {
    background: var(--g);
    border-color: var(--g);
    color: #fff;
    font-weight: 600;
}

/* ── PACKAGES GRID ────────────────────────── */
.prc-main {
    background: var(--bg3);
    padding: clamp(40px,5vw,64px) 0 clamp(56px,7vw,88px);
}

.pkg-card {
    background: var(--bg);
    border: 1px solid var(--br);
    border-radius: 20px;
    overflow: hidden;
    height: 100%;
    display: flex; flex-direction: column;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    transition: transform 240ms ease, box-shadow 240ms ease, border-color 240ms ease;
}
.pkg-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 44px rgba(0,0,0,.11);
    border-color: rgba(47,84,67,.22);
}

/* card body */
.pkg-body {
    padding: 20px 22px;
    flex: 1; display: flex; flex-direction: column;
}
.pkg-studio {
    display: inline-block;
    background: var(--gp); color: var(--g);
    border-radius: 999px; padding: 4px 12px;
    font-size: .66rem; font-weight: 600;
    letter-spacing: .08em; text-transform: uppercase;
    margin-bottom: 12px;
}
.pkg-name {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.1rem,1.6vw,1.5rem);
    font-weight: 700; color: var(--k);
    margin-bottom: 14px; line-height: 1.2;
}
.pkg-features {
    list-style: none; padding: 0; margin: 0 0 16px;
    display: grid; gap: 6px;
    flex: 1;
}
.pkg-features li {
    display: flex; align-items: flex-start; gap: 10px;
    font-size: .88rem; color: var(--ks); line-height: 1.5;
}
.pkg-features li::before {
    content: '';
    flex-shrink: 0; margin-top: 6px;
    width: 6px; height: 6px;
    border-radius: 50%; background: var(--gl);
}

/* card footer */
.pkg-footer {
    padding: 16px 22px 20px;
    border-top: 1px solid var(--br);
    background: var(--bg);
}
.pkg-price-label {
    font-size: .72rem; font-weight: 500; color: var(--km);
    text-transform: uppercase; letter-spacing: .1em;
    margin-bottom: 2px;
}
.pkg-price {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.5rem,2vw,2rem);
    font-weight: 700; color: var(--g);
    line-height: 1; margin-bottom: 14px;
}
.pkg-cta {
    display: block; text-align: center;
    background: var(--g); color: #fff;
    border-radius: 999px; padding: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: .84rem; font-weight: 600;
    text-decoration: none;
    transition: background 180ms ease, transform 180ms ease;
}
.pkg-cta:hover { background: var(--gd); color: #fff; transform: translateY(-1px); }

/* empty state */
.pkg-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: clamp(48px,6vw,80px) 0;
}
.pkg-empty-icon {
    width: 60px; height: 60px; border-radius: 50%;
    background: var(--gp);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px; font-size: 1.4rem;
}
.pkg-empty h3 { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: var(--k); margin-bottom: 8px; }
.pkg-empty p { font-size: .93rem; color: var(--ks); margin-bottom: 18px; }
.pkg-empty a {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--g); color: #fff; border-radius: 999px;
    padding: 10px 22px; font-size: .82rem; font-weight: 600;
    text-decoration: none; transition: background 180ms ease;
}
.pkg-empty a:hover { background: var(--gd); color: #fff; }

/* ── RESPONSIVE ───────────────────────────── */
@media (max-width: 991.98px) {
    .prc { --w: min(1240px,calc(100% - 32px)); }
}
@media (max-width: 575.98px) {
    .prc { --w: calc(100% - 24px); }
    .prc-h1 { font-size: clamp(2rem,8vw,2.8rem); }
}
</style>
@endpush

@section('content')
<div class="prc">

{{-- HERO + FILTERS --}}
<section class="prc-hero">
    <div class="pc">
        <div class="prc-badge"><i></i> Paket &amp; Harga</div>
        <h1 class="prc-h1">{{ $pricing['title'] }}</h1>
        <p class="prc-lead">{{ $pricing['content'] }}</p>

        <div class="prc-filters">
            <a href="{{ route('frontend.pricing') }}"
               class="filter-chip {{ !$selectedStudioId ? 'active' : '' }}">
                Semua Studio
            </a>
            @foreach($studios as $studio)
                <a href="{{ route('frontend.pricing', ['studio_id' => $studio->id]) }}"
                   class="filter-chip {{ (int)$selectedStudioId === (int)$studio->id ? 'active' : '' }}">
                    {{ $studio->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- PACKAGES --}}
<section class="prc-main">
    <div class="pc">
        <div class="row g-4">
            @forelse($packages as $package)
                @php
                    $nm  = strtolower($package->name);
                    $ppl = '1 – 5 orang';
                    if (str_contains($nm,'couple'))    { $ppl = '2 orang'; }
                    elseif (str_contains($nm,'group')) { $ppl = '3 – 15 orang'; }
                    elseif (str_contains($nm,'solo'))  { $ppl = '1 orang'; }

                    $benefits = ['Free semua soft file'];
                    if ($package->duration_minutes >= 15) { $benefits[] = 'Free 1 print photo'; }
                    if ($package->duration_minutes >= 45) { $benefits[] = 'Bonus 1 background setup'; }
                @endphp

                <div class="col-md-6 col-xl-3 d-flex">
                    <div class="pkg-card w-100">

                        {{-- body --}}
                        <div class="pkg-body">
                            <span class="pkg-studio">{{ $package->studio->name ?? 'Studio' }}</span>
                            <h2 class="pkg-name">{{ $package->name }}</h2>
                            <ul class="pkg-features">
                                <li>{{ $ppl }}</li>
                                <li>{{ $package->duration_minutes }} menit sesi foto</li>
                                @foreach($benefits as $b)
                                    <li>{{ $b }}</li>
                                @endforeach
                                @if(!empty($package->description))
                                    <li>{{ $package->description }}</li>
                                @endif
                            </ul>
                        </div>

                        {{-- footer --}}
                        <div class="pkg-footer">
                            <p class="pkg-price-label">Mulai dari</p>
                            <div class="pkg-price">Rp{{ number_format($package->price, 0, ',', '.') }}</div>
                            <a href="{{ route('frontend.booking.package-detail', ['servicePackage' => $package->id, 'date' => now()->toDateString()]) }}"
                               class="pkg-cta">
                                Booking Sekarang &rarr;
                            </a>
                        </div>

                    </div>
                </div>

            @empty
                <div class="pkg-empty">
                    <div class="pkg-empty-icon">&#128247;</div>
                    <h3>Paket tidak tersedia</h3>
                    <p>Belum ada paket layanan untuk filter ini.</p>
                    <a href="{{ route('frontend.pricing') }}">Lihat Semua Paket &rarr;</a>
                </div>
            @endforelse
        </div>
    </div>
</section>

</div>
@endsection
