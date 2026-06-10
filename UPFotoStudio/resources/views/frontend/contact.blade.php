@extends('layouts.frontend')

@section('title', 'Kontak - UPFotoStudio')

@push('styles')
<style>
    .contact-page {
        padding-top: 28px;
        padding-bottom: 56px;
    }

    .contact-map-wrap {
        border: 1px solid #dbe4f2;
        border-radius: 12px;
        overflow: hidden;
        min-height: 420px;
        background: #fff;
    }

    .contact-map-wrap iframe {
        width: 100%;
        height: 100%;
        min-height: 420px;
        border: 0;
    }

    .contact-form-card {
        border: 1px solid #dbe4f2;
        border-radius: 12px;
        background: #fff;
    }

    @media (max-width: 767.98px) {
        .contact-page {
            padding-top: 20px;
            padding-bottom: 40px;
        }

        .contact-map-wrap,
        .contact-map-wrap iframe {
            min-height: 300px;
        }
    }
</style>
@endpush

@section('content')
<section class="contact-page">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">{{ $contact['title'] }}</h1>
                    <p class="text-secondary mb-0">{{ $contact['content'] }}</p>
                </div>
            </div>

            <div class="contact-map-wrap shadow-sm">
                <iframe
                    title="Lokasi UPFotoStudio"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q=Surabaya%2C%20Indonesia&output=embed">
                </iframe>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="contact-form-card shadow-sm p-3 p-md-4">
                <h2 class="h5 mb-3">Kirim Pesan</h2>
                <p class="text-secondary small mb-3">Pesan yang Anda kirim akan masuk langsung ke dashboard admin.</p>

                <form method="post" action="{{ route('frontend.contact.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="full_name" class="form-label">Nama Lengkap</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Nomor HP (opsional)</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="subject" class="form-label">Subjek</label>
                        <input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject') }}" required>
                    </div>
                    <div class="col-12">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea id="message" name="message" class="form-control" rows="6" required>{{ old('message') }}</textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
