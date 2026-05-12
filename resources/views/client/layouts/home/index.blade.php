@extends('client.layout')

@section('title', 'Luxe Appliance - The Pinnacle of Visual Precision')

@section('content')
<div class="luxe-home">
    <!-- Hero Section -->
    <section class="luxe-hero" style="position: relative; height: 85vh; min-height: 600px; display: flex; align-items: center; overflow: hidden; background-color: #000;">
        <div class="luxe-hero__bg" style="position: absolute; inset: 0; z-index: 1;">
            <img src="{{ asset('images/client/hero.png') }}" alt="Hero Banner" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.85;">
            <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(0,0,0,0.4) 0%, transparent 60%);"></div>
        </div>
        
        <div class="container" style="position: relative; z-index: 2;">
            <div class="luxe-hero__content text-white" style="max-width: 600px;">
                <span class="text-uppercase fw-bold mb-3 d-block" style="letter-spacing: 0.1em; font-size: 0.75rem; color: rgba(255,255,255,0.8);">New Arrival</span>
                <h1 class="fw-800 display-3 mb-4">The Pinnacle of Visual Precision</h1>
                <p class="fs-5 mb-5 text-white-50">
                    Experience uncompromised clarity with the new Luxe Vision OLED Series. 
                    Engineered for those who demand perfection in every pixel.
                </p>
                <div class="d-flex gap-3">
                    <x-ui.button variant="secondary" size="lg" :href="route('client.products.index')">Shop Now</x-ui.button>
                    <x-ui.button variant="outline" size="lg" class="text-white border-white" :href="route('client.products.index')">View Specifications</x-ui.button>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Bar -->
    <div class="luxe-trust-bar py-4 bg-white border-bottom">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 border-end">
                    <div class="d-flex align-items-center justify-content-center gap-3 fw-800 text-uppercase fs-7">
                        <i class="fas fa-truck-fast fs-5"></i>
                        <span>Fast, White-Glove Delivery</span>
                    </div>
                </div>
                <div class="col-md-4 border-end">
                    <div class="d-flex align-items-center justify-content-center gap-3 fw-800 text-uppercase fs-7">
                        <i class="fas fa-shield-halved fs-5"></i>
                        <span>Premium 2-Year Warranty</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center justify-content-center gap-3 fw-800 text-uppercase fs-7">
                        <i class="fas fa-headset fs-5"></i>
                        <span>24/7 Expert Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Curated Spaces (Categories) -->
    <section class="luxe-section py-6 animate-fade-up">
        <div class="container">
            <x-ui.section-header 
                title="Curated Spaces" 
                action-text="Explore All Categories" 
                :action-href="route('client.products.index')" />

            <div class="curated-grid">
                <style>
                    .curated-grid {
                        display: grid;
                        grid-template-columns: 2fr 1fr;
                        grid-template-rows: repeat(2, 300px);
                        gap: 1.5rem;
                    }
                    .grid-item {
                        position: relative;
                        border-radius: 12px;
                        overflow: hidden;
                        transition: all 0.3s ease;
                    }
                    .grid-item img {
                        width: 100%; height: 100%; object-fit: cover;
                        transition: transform 0.6s ease;
                    }
                    .grid-item:hover img { transform: scale(1.05); }
                    .grid-item__content {
                        position: absolute; bottom: 0; left: 0; width: 100%; padding: 2rem;
                        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
                        color: #fff;
                    }
                    .grid-item--large { grid-row: span 2; }
                    @media (max-width: 991px) {
                        .curated-grid { grid-template-columns: 1fr; grid-template-rows: auto; }
                        .grid-item--large { grid-row: auto; height: 400px; }
                        .grid-item { height: 300px; }
                    }
                </style>

                <!-- Large Card (Kitchen) -->
                <a href="{{ route('client.products.index', ['category' => 'kitchen']) }}" class="grid-item grid-item--large shadow-sm">
                    <img src="{{ asset('images/client/kitchen.png') }}" alt="Kitchen">
                    <div class="grid-item__content">
                        <h3 class="fw-800 fs-2 mb-1">Kitchen</h3>
                        <p class="mb-0 opacity-75">Culinary excellence redefined.</p>
                    </div>
                </a>

                <!-- Small Card (Laundry) -->
                <a href="{{ route('client.products.index', ['category' => 'laundry']) }}" class="grid-item shadow-sm">
                    <img src="{{ asset('images/client/laundry.png') }}" alt="Laundry">
                    <div class="grid-item__content">
                        <h3 class="fw-800 fs-3 mb-0">Laundry</h3>
                    </div>
                </a>

                <!-- Small Card (Living Room) -->
                <a href="{{ route('client.products.index', ['category' => 'living-room']) }}" class="grid-item shadow-sm">
                    <img src="{{ asset('images/client/living_room.png') }}" alt="Living Room">
                    <div class="grid-item__content">
                        <h3 class="fw-800 fs-3 mb-0">Living Room</h3>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Trending Innovations (Featured Products) -->
    <section class="luxe-section py-6 bg-slate-50 animate-fade-up" style="background-color: #f8fafc; animation-delay: 0.2s;">
        <div class="container">
            <x-ui.section-header title="Trending Innovations" />

            <div class="row g-4">
                @foreach ($featuredProducts as $product)
                    <div class="col-sm-6 col-lg-3">
                        <x-ui.product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
// Home page specific scripts if needed
</script>
@endpush
@endsection
