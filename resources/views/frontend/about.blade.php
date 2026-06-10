@extends('layouts.frontend')
@section('title', 'Tentang Kami - UPFotoStudio')

@push('styles')
<style>
.abt {
    --g:#2f5443;--gd:#1f3d30;--gl:#3d7a5a;--gp:#eef7f2;
    --k:#111;--ks:#555;--bg:#fff;--bg2:#fafaf8;--bg3:#f4f3f0;--br:rgba(0,0,0,.07);
    --w:min(1100px,calc(100% - 48px));
    font-family:'Poppins',sans-serif;color:var(--k);
    width:100vw;max-width:100vw;margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);
    overflow-x:hidden;
}
.abt h1,.abt h2,.abt h3{font-family:'Playfair Display',serif;letter-spacing:-.02em;}
.ac{width:var(--w);margin-inline:auto;}

/* Hero */
.abt-hero{background:var(--bg);padding:clamp(52px,6vw,80px) 0 clamp(36px,4vw,52px);border-bottom:1px solid var(--br);}
.abt-hero-grid{display:grid;grid-template-columns:1fr 320px;gap:clamp(36px,5vw,64px);align-items:center;}
.abt-badge{display:inline-flex;align-items:center;gap:8px;background:var(--gp);color:var(--g);border-radius:999px;padding:6px 14px;font-size:.7rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;margin-bottom:18px;}
.abt-badge i{display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--gl);}
.abt-h1{font-size:clamp(2.2rem,4.5vw,4rem);line-height:1.06;color:var(--k);margin-bottom:14px;}
.abt-lead{font-size:clamp(.97rem,1.25vw,1.06rem);color:var(--ks);max-width:540px;line-height:1.76;}
.abt-visual{border-radius:20px;background:var(--gp);aspect-ratio:1/1;display:flex;flex-direction:column;align-items:center;justify-content:center;position:relative;overflow:hidden;box-shadow:0 16px 40px rgba(47,84,67,.12);}
.abt-init{font-family:'Playfair Display',serif;font-size:clamp(6rem,10vw,10rem);font-weight:800;color:rgba(47,84,67,.14);line-height:1;user-select:none;}
.abt-vis-lbl{position:absolute;bottom:22px;left:22px;font-size:.7rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--g);}

/* Stories */
.abt-stories{background:var(--bg2);padding:clamp(52px,6vw,80px) 0;}
.stag{display:inline-block;font-size:.68rem;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:var(--gl);margin-bottom:10px;}
.sh2{font-size:clamp(1.8rem,2.8vw,2.6rem);color:var(--k);margin-bottom:28px;}
.stories-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.story-card{background:var(--bg);border:1px solid var(--br);border-radius:16px;padding:22px 20px 18px;font-size:.97rem;color:var(--ks);line-height:1.76;position:relative;}
.story-card::before{content:'\201C';font-family:'Playfair Display',serif;font-size:3rem;line-height:1;color:var(--gp);position:absolute;top:8px;left:14px;pointer-events:none;}

/* Values */
.abt-values{background:var(--bg);padding:clamp(52px,6vw,80px) 0;}
.val-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;}
.val-card{border:1px solid var(--br);border-radius:16px;padding:22px 24px;transition:border-color 200ms ease,transform 200ms ease;}
.val-card:hover{border-color:rgba(47,84,67,.25);transform:translateY(-2px);}
.val-dot{width:9px;height:9px;border-radius:50%;background:var(--gl);margin-bottom:13px;}
.val-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--k);margin-bottom:7px;}
.val-desc{font-size:.9rem;color:var(--ks);line-height:1.68;margin:0;}

/* CTA */
.abt-cta{background:var(--g);padding:clamp(44px,5vw,64px) 0;text-align:center;}
.abt-cta h2{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,3vw,2.6rem);color:#f5f5f2;margin-bottom:12px;letter-spacing:-.02em;}
.abt-cta p{color:rgba(210,235,210,.76);font-size:.97rem;margin-bottom:26px;max-width:400px;margin-inline:auto;line-height:1.7;}
.btn-wh{display:inline-flex;align-items:center;gap:8px;background:#fff;color:var(--g);border-radius:999px;padding:12px 26px;font-size:.84rem;font-weight:600;text-decoration:none;transition:background 180ms ease,transform 180ms ease;}
.btn-wh:hover{background:#f0f7f3;transform:translateY(-2px);color:var(--gd);}

@media(max-width:991.98px){.abt{--w:min(1100px,calc(100% - 32px))}.abt-hero-grid{grid-template-columns:1fr}.abt-visual{max-width:240px}.stories-grid{grid-template-columns:1fr}.val-grid{grid-template-columns:1fr}}
@media(max-width:575.98px){.abt{--w:calc(100% - 24px)}.abt-h1{font-size:clamp(2rem,8vw,2.6rem)}}
</style>
@endpush

@section('content')
<div class="abt">

{{-- HERO --}}
<section class="abt-hero">
    <div class="ac">
        <div class="abt-hero-grid">
            <div>
                <div class="abt-badge"><i></i> Mengenal Kami</div>
                <h1 class="abt-h1">{{ $about['title'] }}</h1>
                <p class="abt-lead">{{ $about['content'] }}</p>
            </div>
            <div class="abt-visual">
                <span class="abt-init">UP</span>
                <span class="abt-vis-lbl">UPFotoStudio</span>
            </div>
        </div>
    </div>
</section>

{{-- STORIES --}}
@if(!empty($aboutStories))
<section class="abt-stories">
    <div class="ac">
        <p class="stag">Cerita Kami</p>
        <h2 class="sh2">Perjalanan UPFotoStudio</h2>
        <div class="stories-grid">
            @foreach($aboutStories as $story)
                <p class="story-card">{{ $story }}</p>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CORE VALUES --}}
@if(!empty($aboutValues))
<section class="abt-values">
    <div class="ac">
        <p class="stag">Core Values</p>
        <h2 class="sh2">Nilai yang Kami Pegang</h2>
        <div class="val-grid">
            @foreach($aboutValues as $val)
                <div class="val-card">
                    <div class="val-dot"></div>
                    <h3 class="val-title">{{ $val['title'] }}</h3>
                    <p class="val-desc">{{ $val['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="abt-cta">
    <div class="ac">
        <h2>Siap Booking Studio?</h2>
        <p>Pilih paket yang sesuai dan booking sekarang dengan mudah.</p>
        <a href="{{ route('frontend.pricing') }}" class="btn-wh">Lihat Paket Harga &rarr;</a>
    </div>
</section>

</div>
@endsection
