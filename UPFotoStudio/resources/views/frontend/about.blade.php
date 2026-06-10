@extends('layouts.frontend')

@section('title', 'Tentang Kami - UPFotoStudio')

@push('styles')
<style>
    .about-page {
        padding-top: 28px;
        padding-bottom: 56px;
    }

    .about-metric-card {
        border: 1px solid #dbe4f2;
        border-radius: 12px;
        background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
    }

    .about-metric-value {
        font-size: 1.45rem;
        font-weight: 800;
        line-height: 1.2;
        color: #1f3b73;
    }

    .about-metric-label {
        font-size: 0.95rem;
        font-weight: 700;
        margin-top: 6px;
        color: #1e293b;
    }

    .about-value-card {
        border: 1px solid #dbe4f2;
        border-radius: 12px;
        background: #fff;
        height: 100%;
    }

    .about-program-card {
        border: 1px solid #dbe4f2;
        border-radius: 12px;
        background: #fff;
        height: 100%;
    }

    .about-program-tag {
        display: inline-block;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: .02em;
        color: #1f3b73;
        background: #e9f1ff;
        border-radius: 999px;
        padding: 4px 10px;
    }

    @media (max-width: 767.98px) {
        .about-page {
            padding-top: 20px;
            padding-bottom: 40px;
        }
    }
</style>
@endpush

@section('content')
<section class="about-page">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h1 class="h4 mb-3">{{ $about['title'] }}</h1>
            <p class="mb-0">{{ $about['content'] }}</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Cerita Kami (Konten Dummy Acak)</h2>
                    @foreach($aboutStories as $story)
                        <p class="text-secondary mb-3">{{ $story }}</p>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Ringkasan Cepat (Dummy)</h2>
                    <div class="row g-3">
                        @foreach($aboutStats as $stat)
                            <div class="col-sm-6">
                                <div class="about-metric-card p-3 h-100">
                                    <div class="about-metric-value">{{ $stat['value'] }}</div>
                                    <div class="about-metric-label">{{ $stat['label'] }}</div>
                                    <div class="small text-secondary mt-1">{{ $stat['note'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">Nilai yang Kami Pegang</h2>
            <div class="row g-3">
                @foreach($aboutValues as $value)
                    <div class="col-md-6">
                        <div class="about-value-card p-3">
                            <h3 class="h6 mb-2">{{ $value['title'] }}</h3>
                            <p class="text-secondary small mb-0">{{ $value['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">Program & Aktivitas (Konten Dummy Acak)</h2>
            <div class="row g-3">
                @foreach($aboutPrograms as $program)
                    <div class="col-md-6 col-xl-4">
                        <div class="about-program-card p-3">
                            <span class="about-program-tag">{{ $program['tag'] }}</span>
                            <h3 class="h6 mt-2 mb-2">{{ $program['title'] }}</h3>
                            <p class="text-secondary small mb-0">{{ $program['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
