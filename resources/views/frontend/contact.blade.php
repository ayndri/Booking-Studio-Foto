@extends('layouts.frontend')
@section('title', 'Kontak - UPFotoStudio')

@push('styles')
<style>
/* ── Base ─────────────────────────────────── */
.ctc {
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
    --w: min(1200px, calc(100% - 48px));
    font-family: 'Poppins', sans-serif;
    color: var(--k);
    width: 100vw; max-width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    overflow-x: hidden;
}
.ctc h1,.ctc h2,.ctc h3 { font-family: 'Playfair Display', serif; letter-spacing: -.02em; }
.cc { width: var(--w); margin-inline: auto; }

/* ── HERO ─────────────────────────────────── */
.ctc-hero {
    background: var(--bg);
    padding: clamp(52px,6vw,80px) 0 clamp(36px,4vw,52px);
    border-bottom: 1px solid var(--br);
}
.ctc-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--gp); color: var(--g);
    border-radius: 999px; padding: 6px 14px;
    font-size: .7rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    margin-bottom: 18px;
}
.ctc-badge i { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: var(--gl); }
.ctc-h1 {
    font-size: clamp(2.4rem,4.5vw,4rem);
    line-height: 1.06; color: var(--k); margin-bottom: 12px;
}
.ctc-lead {
    font-size: clamp(.97rem,1.25vw,1.06rem);
    color: var(--ks); max-width: 520px; line-height: 1.74;
}

/* ── MAIN GRID ────────────────────────────── */
.ctc-main {
    background: var(--bg3);
    padding: clamp(44px,5vw,72px) 0 clamp(56px,7vw,88px);
}
.ctc-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: clamp(24px,3vw,48px);
    align-items: start;
}

/* ── LEFT: INFO + MAP ─────────────────────── */
.ctc-info-list {
    display: grid; gap: 12px; margin-bottom: 24px;
}
.ctc-info-item {
    display: flex; align-items: flex-start; gap: 14px;
    background: var(--bg);
    border: 1px solid var(--br);
    border-radius: 14px;
    padding: 16px 18px;
    transition: border-color 200ms ease;
}
.ctc-info-item:hover { border-color: rgba(47,84,67,.22); }
.ctc-info-icon {
    width: 38px; height: 38px; flex-shrink: 0;
    border-radius: 10px;
    background: var(--gp);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
}
.ctc-info-label {
    font-size: .7rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--gl); margin-bottom: 2px;
}
.ctc-info-val {
    font-size: .94rem; color: var(--k); line-height: 1.5;
}

.ctc-map {
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--br);
    box-shadow: 0 4px 16px rgba(0,0,0,.07);
    background: #d8d5cf;
}
.ctc-map iframe {
    width: 100%; height: 280px;
    border: 0; display: block;
}

/* ── RIGHT: FORM ──────────────────────────── */
.ctc-form-wrap {
    background: var(--bg);
    border: 1px solid var(--br);
    border-radius: 20px;
    padding: clamp(24px,3vw,36px);
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
}
.ctc-form-title {
    font-size: clamp(1.4rem,2vw,1.9rem);
    color: var(--k); margin-bottom: 6px;
}
.ctc-form-sub {
    font-size: .88rem; color: var(--km);
    margin-bottom: 24px; line-height: 1.6;
}

.ctc-field { margin-bottom: 18px; }
.ctc-label {
    display: block;
    font-size: .8rem; font-weight: 600;
    color: var(--ks);
    margin-bottom: 6px;
    letter-spacing: .02em;
}
.ctc-label span { color: var(--km); font-weight: 400; }

.ctc-input,
.ctc-textarea {
    width: 100%;
    border: 1.5px solid rgba(0,0,0,.1);
    border-radius: 10px;
    padding: 12px 16px;
    font-family: 'Poppins', sans-serif;
    font-size: .92rem;
    color: var(--k);
    background: var(--bg);
    transition: border-color 200ms ease, box-shadow 200ms ease;
    outline: none;
    -webkit-appearance: none;
}
.ctc-input:focus,
.ctc-textarea:focus {
    border-color: rgba(47,84,67,.4);
    box-shadow: 0 0 0 3px rgba(47,84,67,.08);
}
.ctc-input::placeholder,
.ctc-textarea::placeholder { color: var(--km); }
.ctc-textarea { resize: vertical; min-height: 130px; line-height: 1.6; }

.ctc-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

.ctc-submit {
    display: block; width: 100%;
    background: var(--g); color: #fff;
    border: none; border-radius: 999px;
    padding: 14px;
    font-family: 'Poppins', sans-serif;
    font-size: .88rem; font-weight: 600;
    cursor: pointer; text-align: center;
    transition: background 180ms ease, transform 180ms ease, box-shadow 180ms ease;
    margin-top: 4px;
}
.ctc-submit:hover {
    background: var(--gd);
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(47,84,67,.26);
}

/* error state */
.ctc-input.is-invalid,
.ctc-textarea.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220,53,69,.08);
}
.ctc-err {
    font-size: .78rem; color: #dc3545; margin-top: 4px;
}

/* ── RESPONSIVE ───────────────────────────── */
@media (max-width: 991.98px) {
    .ctc { --w: min(1200px,calc(100% - 32px)); }
    .ctc-grid { grid-template-columns: 1fr; }
    .ctc-map iframe { height: 240px; }
}
@media (max-width: 575.98px) {
    .ctc { --w: calc(100% - 24px); }
    .ctc-h1 { font-size: clamp(2rem,8vw,2.8rem); }
    .ctc-row { grid-template-columns: 1fr; gap: 0; }
}
</style>
@endpush

@section('content')
<div class="ctc">

{{-- HERO --}}
<section class="ctc-hero">
    <div class="cc">
        <div class="ctc-badge"><i></i> Hubungi Kami</div>
        <h1 class="ctc-h1">{{ $contact['title'] }}</h1>
        <p class="ctc-lead">{{ $contact['content'] }}</p>
    </div>
</section>

{{-- MAIN --}}
<section class="ctc-main">
    <div class="cc">
        <div class="ctc-grid">

            {{-- LEFT: info + map --}}
            <div>
                @php
                    $contactLines = collect(preg_split('/\r\n|\r|\n|\|/', (string)($footerContent['contact'] ?? '')))
                        ->map(fn($l) => trim((string)$l))->filter(fn($l) => $l !== '')->values();
                    $icons = ['📍', '✉️', '📱'];
                    $labels = ['Lokasi', 'Email', 'Telepon'];
                @endphp

                <div class="ctc-info-list">
                    @forelse($contactLines as $idx => $line)
                        <div class="ctc-info-item">
                            <div class="ctc-info-icon">{{ $icons[$idx] ?? '📋' }}</div>
                            <div>
                                <div class="ctc-info-label">{{ $labels[$idx] ?? 'Info' }}</div>
                                <div class="ctc-info-val">{{ $line }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="ctc-info-item">
                            <div class="ctc-info-icon">📍</div>
                            <div>
                                <div class="ctc-info-label">Lokasi</div>
                                <div class="ctc-info-val">Surabaya, Indonesia</div>
                            </div>
                        </div>
                        <div class="ctc-info-item">
                            <div class="ctc-info-icon">✉️</div>
                            <div>
                                <div class="ctc-info-label">Email</div>
                                <div class="ctc-info-val">hello@upfotostudio.test</div>
                            </div>
                        </div>
                        <div class="ctc-info-item">
                            <div class="ctc-info-icon">📱</div>
                            <div>
                                <div class="ctc-info-label">Telepon / WhatsApp</div>
                                <div class="ctc-info-val">(+62) 812 0000 0000</div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="ctc-map">
                    <iframe
                        title="Lokasi UPFotoStudio"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps?q=Surabaya%2C%20Indonesia&output=embed">
                    </iframe>
                </div>
            </div>

            {{-- RIGHT: form --}}
            <div>
                <div class="ctc-form-wrap">
                    <h2 class="ctc-form-title">Kirim Pesan</h2>
                    <p class="ctc-form-sub">Pesan Anda akan langsung masuk ke dashboard admin kami.</p>

                    <form method="post" action="{{ route('frontend.contact.store') }}" id="contactForm" novalidate>
                        @csrf
                        <input type="hidden" name="recaptcha_token" id="contact_recaptcha_token">

                        <div class="ctc-row">
                            <div class="ctc-field">
                                <label class="ctc-label" for="full_name">Nama Lengkap</label>
                                <input
                                    type="text" id="full_name" name="full_name"
                                    class="ctc-input {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                                    value="{{ old('full_name') }}"
                                    placeholder="Nama kamu"
                                    required>
                                @error('full_name')<p class="ctc-err">{{ $message }}</p>@enderror
                            </div>
                            <div class="ctc-field">
                                <label class="ctc-label" for="email">Email</label>
                                <input
                                    type="email" id="email" name="email"
                                    class="ctc-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    value="{{ old('email') }}"
                                    placeholder="email@contoh.com"
                                    required>
                                @error('email')<p class="ctc-err">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="ctc-row">
                            <div class="ctc-field">
                                <label class="ctc-label" for="phone">Nomor HP <span>(opsional)</span></label>
                                <input
                                    type="text" id="phone" name="phone"
                                    class="ctc-input"
                                    value="{{ old('phone') }}"
                                    placeholder="08xx xxxx xxxx">
                            </div>
                            <div class="ctc-field">
                                <label class="ctc-label" for="subject">Subjek</label>
                                <input
                                    type="text" id="subject" name="subject"
                                    class="ctc-input {{ $errors->has('subject') ? 'is-invalid' : '' }}"
                                    value="{{ old('subject') }}"
                                    placeholder="Topik pesan"
                                    required>
                                @error('subject')<p class="ctc-err">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="ctc-field">
                            <label class="ctc-label" for="message">Pesan</label>
                            <textarea
                                id="message" name="message"
                                class="ctc-textarea {{ $errors->has('message') ? 'is-invalid' : '' }}"
                                placeholder="Tulis pesanmu di sini..."
                                required>{{ old('message') }}</textarea>
                            @error('message')<p class="ctc-err">{{ $message }}</p>@enderror
                        </div>

                        @error('recaptcha_token')<p class="ctc-err">{{ $message }}</p>@enderror

                        <button type="submit" class="ctc-submit">Kirim Pesan &rarr;</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

</div>
@endsection

@push('scripts')
@if(config('services.recaptcha.site_key'))
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script>
        const contactForm = document.getElementById('contactForm');
        const contactSiteKey = @json(config('services.recaptcha.site_key'));
        let contactRecaptchaResolved = false;
        if (contactForm && contactSiteKey) {
            contactForm.addEventListener('submit', function (event) {
                if (contactRecaptchaResolved) return;
                event.preventDefault();
                grecaptcha.ready(function () {
                    grecaptcha.execute(contactSiteKey, { action: 'contact' }).then(function (token) {
                        document.getElementById('contact_recaptcha_token').value = token;
                        contactRecaptchaResolved = true;
                        contactForm.submit();
                    });
                });
            });
        }
    </script>
@endif
@endpush
