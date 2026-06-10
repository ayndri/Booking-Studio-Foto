@extends('layouts.frontend')
@section('title', 'Syarat & Ketentuan - UPFotoStudio')

@push('styles')
<style>
.tnc{--g:#2f5443;--gd:#1f3d30;--gl:#3d7a5a;--gp:#eef7f2;--k:#111;--ks:#555;--bg:#fff;--bg2:#fafaf8;--bg3:#f4f3f0;--br:rgba(0,0,0,.07);--w:min(1100px,calc(100% - 48px));font-family:'Poppins',sans-serif;color:var(--k);width:100vw;max-width:100vw;margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);overflow-x:hidden;}
.tnc h1,.tnc h2,.tnc h3{font-family:'Playfair Display',serif;letter-spacing:-.02em;}
.tc{width:var(--w);margin-inline:auto;}
.stag{display:inline-block;font-size:.68rem;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:var(--gl);margin-bottom:10px;}
.sh2{font-size:clamp(1.8rem,2.8vw,2.4rem);color:var(--k);margin-bottom:26px;}

/* Hero */
.tnc-hero{background:var(--bg);padding:clamp(52px,6vw,80px) 0 clamp(36px,4vw,52px);border-bottom:1px solid var(--br);}
.tnc-badge{display:inline-flex;align-items:center;gap:8px;background:var(--gp);color:var(--g);border-radius:999px;padding:6px 14px;font-size:.7rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;margin-bottom:18px;}
.tnc-badge i{display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--gl);}
.tnc-h1{font-size:clamp(2.2rem,4.5vw,4rem);line-height:1.06;color:var(--k);margin-bottom:14px;}
.tnc-lead{font-size:clamp(.97rem,1.25vw,1.06rem);color:var(--ks);max-width:600px;line-height:1.74;margin-bottom:26px;}
.tnc-list{list-style:none;padding:0;margin:0;max-width:640px;}
.tnc-list li{display:flex;align-items:flex-start;gap:12px;font-size:.96rem;color:#333;padding:10px 0;border-bottom:1px solid var(--br);line-height:1.6;}
.tnc-list li:last-child{border-bottom:none;}
.tnc-list li::before{content:'';flex-shrink:0;margin-top:7px;width:7px;height:7px;border-radius:50%;background:var(--gl);}

/* Booking flow */
.tnc-flow{background:var(--g);padding:clamp(48px,6vw,72px) 0;}
.tnc-flow-ey{display:inline-block;font-size:.68rem;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:rgba(196,228,210,.7);margin-bottom:10px;}
.tnc-flow-h2{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,2.8vw,2.4rem);color:#f5f5f2;margin-bottom:32px;letter-spacing:-.02em;}
.flow-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;position:relative;}
.flow-grid::before{content:'';position:absolute;top:22px;left:calc(12.5% + 10px);right:calc(12.5% + 10px);height:1px;background:rgba(255,255,255,.18);z-index:0;}
.flow-item{position:relative;z-index:1;display:flex;flex-direction:column;align-items:center;text-align:center;padding:0 8px;}
.flow-num{width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:14px;flex-shrink:0;}
.flow-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:#f0ede4;margin-bottom:6px;}
.flow-desc{font-size:.82rem;color:rgba(210,232,215,.7);line-height:1.6;}

/* Extra terms */
.tnc-extra{background:var(--bg2);padding:clamp(52px,6vw,80px) 0;}
.extra-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.extra-item{background:var(--bg);border:1px solid var(--br);border-radius:14px;padding:16px 18px;display:flex;align-items:flex-start;gap:12px;}
.extra-dot{flex-shrink:0;margin-top:5px;width:7px;height:7px;border-radius:50%;background:var(--gl);}
.extra-text{font-size:.92rem;color:var(--ks);line-height:1.68;}

/* CTA */
.tnc-cta{background:var(--bg3);padding:clamp(40px,5vw,60px) 0;text-align:center;}
.tnc-cta p{font-size:.96rem;color:var(--ks);margin-bottom:16px;}
.btn-g-tnc{display:inline-flex;align-items:center;gap:8px;background:var(--g);color:#fff;border-radius:999px;padding:12px 26px;font-size:.84rem;font-weight:600;text-decoration:none;transition:background 180ms ease,transform 180ms ease;}
.btn-g-tnc:hover{background:var(--gd);color:#fff;transform:translateY(-2px);}

@media(max-width:991.98px){.tnc{--w:min(1100px,calc(100% - 32px))}.flow-grid{grid-template-columns:repeat(2,1fr)}.flow-grid::before{display:none}.extra-grid{grid-template-columns:1fr}}
@media(max-width:575.98px){.tnc{--w:calc(100% - 24px)}.tnc-h1{font-size:clamp(2rem,8vw,2.6rem)}}
</style>
@endpush

@section('content')
<div class="tnc">

{{-- HERO --}}
<section class="tnc-hero">
    <div class="tc">
        <div class="tnc-badge"><i></i> Legal</div>
        <h1 class="tnc-h1">{{ $terms['title'] }}</h1>
        <p class="tnc-lead">{{ $terms['content'] }}</p>
        @if(!empty($termItems))
        <ul class="tnc-list">
            @foreach($termItems as $item)<li>{{ $item }}</li>@endforeach
        </ul>
        @endif
    </div>
</section>

{{-- BOOKING FLOW --}}
@if(!empty($termFlow))
<section class="tnc-flow">
    <div class="tc">
        <p class="tnc-flow-ey">Cara Booking</p>
        <h2 class="tnc-flow-h2">Alur Singkat Booking</h2>
        <div class="flow-grid">
            @foreach($termFlow as $i => $step)
                <div class="flow-item">
                    <div class="flow-num">{{ $step['step'] ?? str_pad($i+1,2,'0',STR_PAD_LEFT) }}</div>
                    <h3 class="flow-title">{{ $step['title'] }}</h3>
                    <p class="flow-desc">{{ $step['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- EXTRA TERMS --}}
@if(!empty($termExtraItems))
<section class="tnc-extra">
    <div class="tc">
        <p class="stag">Ketentuan</p>
        <h2 class="sh2">Ketentuan Tambahan</h2>
        <div class="extra-grid">
            @foreach($termExtraItems as $item)
                <div class="extra-item">
                    <div class="extra-dot"></div>
                    <span class="extra-text">{{ $item }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<div class="tnc-cta">
    <div class="tc">
        <p>Sudah paham syarat & ketentuannya? Langsung booking sekarang.</p>
        <a href="{{ route('frontend.pricing') }}" class="btn-g-tnc">Lihat Paket Harga &rarr;</a>
    </div>
</div>

</div>
@endsection
