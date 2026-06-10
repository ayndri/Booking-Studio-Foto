@extends('layouts.frontend')

@section('title', 'S&K - UPFotoStudio')

@push('styles')
<style>
    .terms-page {
        padding-top: 28px;
        padding-bottom: 56px;
    }

    .terms-section-card {
        border: 1px solid #dbe4f2;
        border-radius: 12px;
        background: #fff;
    }

    .terms-flow-item {
        border: 1px solid #dbe4f2;
        border-radius: 12px;
        padding: 12px;
        height: 100%;
        background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
    }

    .terms-flow-step {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #1f3b73;
        color: #fff;
        font-size: 0.78rem;
        font-weight: 700;
    }

    @media (max-width: 767.98px) {
        .terms-page {
            padding-top: 20px;
            padding-bottom: 40px;
        }
    }
</style>
@endpush

@section('content')
<section class="terms-page">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h1 class="h4 mb-3">{{ $terms['title'] }}</h1>
            <p class="mb-3">{{ $terms['content'] }}</p>
            <ul class="mb-0">
                @foreach($termItems as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="terms-section-card p-4 h-100">
                <h2 class="h5 mb-3">Ketentuan Tambahan (Konten Dummy Acak)</h2>
                <ul class="mb-0">
                    @foreach($termExtraItems as $item)
                        <li class="mb-2">{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="terms-section-card p-4 h-100">
                <h2 class="h5 mb-3">Alur Singkat Booking</h2>
                <div class="row g-3">
                    @foreach($termFlow as $flow)
                        <div class="col-sm-6">
                            <div class="terms-flow-item">
                                <span class="terms-flow-step">{{ $flow['step'] }}</span>
                                <h3 class="h6 mt-2 mb-1">{{ $flow['title'] }}</h3>
                                <p class="text-secondary small mb-0">{{ $flow['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="terms-section-card p-4">
        <h2 class="h5 mb-3">FAQ Syarat & Ketentuan (Dummy)</h2>
        <div class="row g-3">
            @foreach($termFaqs as $faq)
                <div class="col-md-6">
                    <div class="terms-flow-item">
                        <h3 class="h6 mb-1">{{ $faq['question'] }}</h3>
                        <p class="text-secondary small mb-0">{{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
