@extends('layouts.frontend')

@section('title', 'Paket Harga - UPFotoStudio')

@push('styles')
<style>
    .pricing-page {
        padding-top: 28px;
        padding-bottom: 56px;
    }

    @media (max-width: 767.98px) {
        .pricing-page {
            padding-top: 20px;
            padding-bottom: 40px;
        }
    }

    .filter-chip {
        border: 1px solid #355c9f;
        color: #355c9f;
        border-radius: 999px;
        padding: 8px 16px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-block;
    }

    .filter-chip.active {
        background: #355c9f;
        color: #fff;
    }

    .package-card {
        border: 0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .package-card .cover {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .package-card .badge-studio {
        display: inline-block;
        align-self: flex-start;
        background: #1f3b73;
        color: #fff;
        border-radius: 999px;
        font-size: 13px;
        padding: 6px 12px;
        font-weight: 700;
    }

    .package-card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .package-title {
        font-size: clamp(1.45rem, 0.7vw + 1.1rem, 1.9rem);
        line-height: 1.3;
        letter-spacing: 0;
        min-height: 5.2rem;
        margin-top: 14px;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .package-feature {
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.45;
        min-height: 7.9rem;
    }

    .package-price {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .package-card-footer {
        margin-top: auto;
    }

    .btn-outline-primary-soft {
        border-radius: 12px;
        font-weight: 700;
        padding: 10px 14px;
        font-size: 1rem;
    }

    @media (max-width: 1199.98px) {
        .package-title,
        .package-feature {
            min-height: 0;
        }
    }

    @media (max-width: 767.98px) {
        .package-title {
            font-size: 1.35rem;
        }

        .package-price {
            font-size: 1.6rem;
        }
    }
</style>
@endpush

@section('content')
<section class="pricing-page">
    <div class="home-section mb-4">
        <h1 class="h5 mb-2">{{ $pricing['title'] }}</h1>
        <p class="text-secondary mb-2">{{ $pricing['content'] }}</p>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('frontend.pricing') }}" class="filter-chip {{ !$selectedStudioId ? 'active' : '' }}">All</a>
            @foreach($studios as $studio)
                <a href="{{ route('frontend.pricing', ['studio_id' => $studio->id]) }}"
                   class="filter-chip {{ (int) $selectedStudioId === (int) $studio->id ? 'active' : '' }}">
                    {{ $studio->name }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="row g-4">
        @forelse($packages as $package)
            @php
                $name = strtolower($package->name);
                $peopleLabel = '1 - 5 person(s)';
                if (str_contains($name, 'couple')) { $peopleLabel = '2 person(s)'; }
                elseif (str_contains($name, 'group')) { $peopleLabel = '3 - 15 person(s)'; }
                elseif (str_contains($name, 'solo')) { $peopleLabel = '1 person(s)'; }

                $benefits = ['Free All Soft File'];
                if ($package->duration_minutes >= 15) { $benefits[] = 'Free 1 Print Photo'; }
                if ($package->duration_minutes >= 45) { $benefits[] = 'Bonus 1 Background Setup'; }
            @endphp

            <div class="col-md-6 col-xl-3 d-flex">
                <div class="package-card">
                    <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="cover">
                    <div class="p-3 p-lg-4 package-card-body">
                        <span class="badge-studio">{{ $package->studio->name }}</span>

                        <h2 class="h4 package-title">{{ $package->name }}</h2>

                        <div class="package-feature mb-3">
                            <div>&bull; {{ $peopleLabel }}</div>
                            <div>&bull; {{ $package->duration_minutes }} mins photo session</div>
                            @foreach($benefits as $benefit)
                                <div>&bull; {{ $benefit }}</div>
                            @endforeach
                        </div>

                        <div class="package-card-footer">
                            <div class="package-price mb-3">Rp {{ number_format($package->price, 0, ',', '.') }}</div>

                            <a href="{{ route('frontend.booking.package-detail', ['servicePackage' => $package->id, 'date' => now()->toDateString()]) }}"
                               class="btn btn-outline-primary btn-outline-primary-soft w-100">
                                Pilih paket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">Belum ada paket layanan pada filter ini.</div>
            </div>
        @endforelse
    </div>
</section>
@endsection
