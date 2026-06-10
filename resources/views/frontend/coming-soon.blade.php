@extends('layouts.frontend')
@section('title', 'Coming Soon - UPFotoStudio')

@push('styles')
<style>
.cs-page {
    --g: #2f5443; --gd: #1f3d30; --gl: #3d7a5a; --gp: #eef7f2;
    --k: #111; --ks: #555; --bg: #fff; --bg2: #fafaf8; --bg3: #f4f3f0;
    font-family: 'Poppins', sans-serif; color: var(--k);
    width: 100vw; max-width: 100vw;
    margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);
    min-height: 70vh;
}
.cs-hero {
    background: linear-gradient(135deg, #2f5443 0%, #1f3d30 100%);
    padding: clamp(60px,8vw,100px) 0;
    text-align: center;
}
.cs-wrap { width: min(900px, calc(100% - 48px)); margin-inline: auto; }

/* Logo landscape */
.logo-landscape {
    display: inline-flex; align-items: center; justify-content: center; gap: 14px;
    background: rgba(255,255,255,.08);
    border: 1.5px solid rgba(255,255,255,.18);
    border-radius: 16px;
    padding: 18px 36px;
    margin-bottom: 32px;
    backdrop-filter: blur(8px);
}
.logo-landscape .logo-icon {
    width: 44px; height: 44px;
    background: #fff;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.logo-landscape .logo-icon svg { display: block; }
.logo-landscape .logo-text .top {
    font-family: 'Poppins', sans-serif;
    font-weight: 800; font-size: 1.3rem;
    letter-spacing: -.01em; color: #fff; line-height: 1;
}
.logo-landscape .logo-text .top span { color: #86efac; }
.logo-landscape .logo-text .sub {
    font-size: .72rem; color: rgba(200,240,210,.7);
    letter-spacing: .14em; text-transform: uppercase;
    margin-top: 2px;
}

.cs-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.4rem,5vw,4.2rem);
    color: #f5f5f2; margin-bottom: 14px; line-height: 1.1;
    letter-spacing: -.02em;
}
.cs-sub {
    font-size: clamp(1rem,1.4vw,1.12rem);
    color: rgba(210,230,215,.78);
    max-width: 520px; margin: 0 auto 36px;
    line-height: 1.72;
}

/* Logo variants section */
.cs-logos {
    background: var(--bg2);
    padding: clamp(48px,6vw,80px) 0;
}
.logos-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    align-items: center;
}
.logo-card {
    background: var(--bg);
    border: 1px solid rgba(0,0,0,.07);
    border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
}
.logo-card.landscape { padding: 40px 48px; aspect-ratio: 16/7; }
.logo-card.square    { padding: 40px; aspect-ratio: 1/1; max-width: 280px; margin-inline: auto; }

/* Logo landscape format */
.lf-landscape {
    display: flex; align-items: center; gap: 14px;
}
.lf-icon {
    width: 52px; height: 52px;
    background: var(--g);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.lf-text .brand { font-family:'Poppins',sans-serif; font-weight:800; font-size:1.5rem; color:var(--k); letter-spacing:-.01em; line-height:1; }
.lf-text .brand span { color: var(--g); }
.lf-text .tagline { font-size:.7rem; color:var(--ks); letter-spacing:.14em; text-transform:uppercase; margin-top:3px; }

/* Logo square format */
.lf-square {
    display: flex; flex-direction: column; align-items: center; gap: 12px;
}
.lf-square .sq-icon {
    width: 80px; height: 80px;
    background: var(--g);
    border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
}
.lf-square .sq-brand { font-family:'Poppins',sans-serif; font-weight:800; font-size:1.2rem; color:var(--k); letter-spacing:-.01em; text-align:center; }
.lf-square .sq-brand span { color:var(--g); }
.lf-square .sq-tag { font-size:.62rem; color:var(--ks); letter-spacing:.14em; text-transform:uppercase; text-align:center; }

.logo-label { text-align:center; font-size:.72rem; font-weight:600; color:var(--ks); text-transform:uppercase; letter-spacing:.12em; margin-top:12px; }

/* Features coming */
.cs-features {
    background: var(--bg);
    padding: clamp(48px,6vw,80px) 0;
    border-top: 1px solid rgba(0,0,0,.07);
}
.feat-grid { display:grid; grid-template-columns: repeat(3,1fr); gap:20px; }
.feat-card {
    background: var(--bg2);
    border: 1px solid rgba(0,0,0,.07);
    border-radius: 16px; padding: 24px 20px;
    text-align: center;
}
.feat-icon { font-size:1.8rem; margin-bottom:12px; }
.feat-title { font-family:'Playfair Display',serif; font-size:1rem; font-weight:700; color:var(--k); margin-bottom:6px; }
.feat-desc  { font-size:.84rem; color:var(--ks); line-height:1.6; }

.cs-cta-strip { background:var(--bg3); padding:44px 0; text-align:center; }
.cs-cta-strip p { font-size:.95rem; color:var(--ks); margin-bottom:16px; }
.btn-g-cs {
    display:inline-flex; align-items:center; gap:8px;
    background:var(--g); color:#fff;
    border-radius:999px; padding:12px 28px;
    font-size:.84rem; font-weight:600; text-decoration:none;
    transition:background 180ms ease, transform 180ms ease;
}
.btn-g-cs:hover { background:var(--gd); color:#fff; transform:translateY(-2px); }

@media(max-width:767.98px){
    .logos-grid { grid-template-columns:1fr; }
    .logo-card.square { max-width:220px; }
    .feat-grid { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')
<div class="cs-page">

    {{-- HERO --}}
    <section class="cs-hero">
        <div class="cs-wrap">
            {{-- Logo landscape (hero version, white) --}}
            <div class="logo-landscape">
                <div class="logo-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect x="2" y="7" width="20" height="15" rx="3" fill="#2f5443"/>
                        <circle cx="12" cy="14.5" r="4" fill="white"/>
                        <rect x="7" y="2" width="10" height="5" rx="2" fill="#2f5443"/>
                        <circle cx="12" cy="14.5" r="2" fill="#eef7f2"/>
                    </svg>
                </div>
                <div class="logo-text">
                    <div class="top"><span>UP</span>FotoStudio</div>
                    <div class="sub">Professional Photo Studio</div>
                </div>
            </div>

            <h1 class="cs-heading">Sesuatu yang Spesial<br>Sedang Disiapkan</h1>
            <p class="cs-sub">Kami sedang mempersiapkan fitur dan layanan baru yang akan membuat pengalaman foto kamu semakin luar biasa.</p>

            <a href="{{ route('frontend.home') }}" class="btn-g-cs">← Kembali ke Beranda</a>
        </div>
    </section>

    {{-- LOGO VARIANTS --}}
    <section class="cs-logos">
        <div class="cs-wrap">
            <div style="text-align:center;margin-bottom:32px;">
                <p style="font-size:.68rem;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:#3d7a5a;margin-bottom:8px;">Identitas Brand</p>
                <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.6rem,2.5vw,2.2rem);color:#111;margin:0;">Logo UPFotoStudio</h2>
            </div>

            <div class="logos-grid">
                {{-- Landscape format --}}
                <div>
                    <div class="logo-card landscape">
                        <div class="lf-landscape">
                            <div class="lf-icon">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                                    <rect x="2" y="8" width="24" height="17" rx="3" fill="white"/>
                                    <circle cx="14" cy="16.5" r="5" fill="#2f5443"/>
                                    <rect x="8" y="2" width="12" height="6" rx="2" fill="white"/>
                                    <circle cx="14" cy="16.5" r="2.5" fill="#eef7f2"/>
                                </svg>
                            </div>
                            <div class="lf-text">
                                <div class="brand"><span>UP</span>FotoStudio</div>
                                <div class="tagline">Professional Photo Studio</div>
                            </div>
                        </div>
                    </div>
                    <p class="logo-label">Format Landscape</p>
                </div>

                {{-- Square format --}}
                <div>
                    <div class="logo-card square">
                        <div class="lf-square">
                            <div class="sq-icon">
                                <svg width="44" height="44" viewBox="0 0 44 44" fill="none">
                                    <rect x="4" y="12" width="36" height="26" rx="5" fill="white"/>
                                    <circle cx="22" cy="25" r="7.5" fill="#2f5443"/>
                                    <rect x="13" y="4" width="18" height="8" rx="3" fill="white"/>
                                    <circle cx="22" cy="25" r="3.5" fill="#eef7f2"/>
                                </svg>
                            </div>
                            <div class="sq-brand"><span>UP</span>Foto<br>Studio</div>
                            <div class="sq-tag">Photo Studio</div>
                        </div>
                    </div>
                    <p class="logo-label">Format Persegi</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FITUR YANG AKAN DATANG --}}
    <section class="cs-features">
        <div class="cs-wrap">
            <div style="text-align:center;margin-bottom:32px;">
                <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.6rem,2.5vw,2.2rem);color:#111;margin:0;">Yang Sedang Kami Siapkan</h2>
            </div>
            <div class="feat-grid">
                <div class="feat-card">
                    <div class="feat-icon">🖼️</div>
                    <div class="feat-title">Studio Baru</div>
                    <div class="feat-desc">Studio Garden, Pre-Wedding, dan Maternity dengan konsep tematik yang menawan.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon">📱</div>
                    <div class="feat-title">Booking Mobile</div>
                    <div class="feat-desc">Aplikasi mobile untuk kemudahan booking kapan saja dan di mana saja.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon">🎁</div>
                    <div class="feat-title">Program Loyalty</div>
                    <div class="feat-desc">Kumpulkan poin setiap sesi foto dan dapatkan reward eksklusif dari kami.</div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <div class="cs-cta-strip">
        <div class="cs-wrap">
            <p>Mau tahu lebih dulu saat fitur ini tersedia? Hubungi kami sekarang.</p>
            <a href="{{ route('frontend.contact') }}" class="btn-g-cs">Hubungi Kami &rarr;</a>
        </div>
    </div>

</div>
@endsection
