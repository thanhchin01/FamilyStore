@extends('client.layout')

@section('title', 'K-Q Store - Nâng tầm không gian sống')

@section('content')

    <!-- Premium Hero Section -->
    <header class="hero-section mb-5">
        <div class="container overflow-hidden">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 mb-5 mb-lg-0 order-2 order-lg-1">
                    <span class="badge bg-primary-light text-white mb-4 py-2 px-3 fw-bold rounded-pill shadow-sm">UY TÍN & CHẤT LƯỢNG</span>
                    <h1 class="display-3 fw-800 mb-4 line-height-tight" style="color: #0f172a;">Nâng tầm <span class="text-primary-color">không gian sống</span> cùng Khoa Quyên</h1>
                    <p class="lead mb-5 opacity-75 pe-lg-5">Chúng tôi cung cấp các sản phẩm thiết bị điện máy, gia dụng cao cấp từ những thương hiệu hàng đầu thế giới. Trải nghiệm dịch vụ bảo hành và hậu mãi vượt trội.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('client.products.index') }}" class="btn btn-primary-custom px-5 py-3 fw-bold shadow-lg">KHÁM PHÁ NGAY</a>
                        <a href="#" class="btn btn-outline-secondary px-5 py-3 rounded-pill fw-bold border-2">XEM ƯU ĐÃI</a>
                    </div>
                    
                    <div class="mt-5 d-flex align-items-center gap-4">
                        <div class="d-flex align-items-center">
                            <h4 class="fw-bold mb-0 me-2 text-primary-color">8K+</h4>
                            <span class="small text-muted">Khách hàng tin tưởng</span>
                        </div>
                        <div class="vr opacity-25" style="height: 30px;"></div>
                        <div class="d-flex align-items-center">
                            <h4 class="fw-bold mb-0 me-2 text-primary-color">15+</h4>
                            <span class="small text-muted">Năm kinh nghiệm</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 position-relative">
                    <div class="hero-image-wrapper p-3">
                        <img src="{{ asset('luxury_home_appliances_banner_1776356257888.png') }}" class="img-fluid rounded-4 shadow-xl main-hero-img" alt="Luxury Home Appliances">
                        <div class="hero-float-card shadow-lg p-3 py-4 rounded-4 bg-white d-flex align-items-center gap-3">
                            <div class="icon-circle bg-success text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Hàng chính hãng 100%</h6>
                                <p class="extra-small text-muted mb-0">Bảo hành 24 tháng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Brands Carousel (Swiper) -->
    <section class="mb-5 py-5 border-top border-bottom border-light">
        <div class="container container-brands overflow-hidden">
            <div class="swiper brands-swiper">
                <div class="swiper-wrapper opacity-50 align-items-center">
                    <div class="swiper-slide"><h4 class="fw-800 text-center">PANASONIC</h4></div>
                    <div class="swiper-slide"><h4 class="fw-800 text-center">SAMSUNG</h4></div>
                    <div class="swiper-slide"><h4 class="fw-800 text-center">LG</h4></div>
                    <div class="swiper-slide"><h4 class="fw-800 text-center">BOSCH</h4></div>
                    <div class="swiper-slide"><h4 class="fw-800 text-center">DYSON</h4></div>
                    <div class="swiper-slide"><h4 class="fw-800 text-center">PHILIPS</h4></div>
                    <div class="swiper-slide"><h4 class="fw-800 text-center">SONY</h4></div>
                    <div class="swiper-slide"><h4 class="fw-800 text-center">TEFAL</h4></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <div class="container mb-5 py-5" id="featured">
        <div class="text-center mb-5 section-header-animate">
            <span class="text-primary-color fw-700 text-uppercase tracking-wider">Bộ sưu tập độc quyền</span>
            <h2 class="fw-800 display-5 mt-2">Sản phẩm nổi bật</h2>
            <p class="text-muted mt-3 mx-auto" style="max-width: 600px;">Những lựa chọn tối ưu cho ngôi nhà hiện đại của bạn với công nghệ tiên tiến nhất.</p>
        </div>

        <div class="swiper featured-swiper pb-5">
            <div class="swiper-wrapper">
                @php
                $mockProducts = [
                    ['name' => 'Máy giặt Panasonic Inverter 10kg', 'price' => 12500000, 'img' => 'https://images.unsplash.com/photo-1545173168-9f1947eebb7f?q=80&w=400&auto=format&fit=crop'],
                    ['name' => 'Tủ lạnh Samsung Bespoke 400L', 'price' => 25900000, 'img' => 'https://images.unsplash.com/photo-1571175488180-ef9b64261bd0?q=80&w=400&auto=format&fit=crop'],
                    ['name' => 'Nồi chiên không dầu Philips XXL', 'price' => 4500000, 'img' => 'https://images.unsplash.com/photo-1584286595398-a59f21d313f5?q=80&w=400&auto=format&fit=crop'],
                    ['name' => 'Quạt cây điều khiển từ xa Hatari', 'price' => 1200000, 'img' => 'https://images.unsplash.com/photo-1585338107529-13afc5f02586?q=80&w=400&auto=format&fit=crop'],
                    ['name' => 'Smart TV LG Oled C3 55 inch', 'price' => 32000000, 'img' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?q=80&w=400&auto=format&fit=crop'],
                    ['name' => 'Máy hút bụi không dây Dyson V15', 'price' => 18900000, 'img' => 'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?q=80&w=400&auto=format&fit=crop'],
                    ['name' => 'Bếp từ Bosch 3 vùng nấu', 'price' => 15500000, 'img' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=400&auto=format&fit=crop'],
                    ['name' => 'Máy ép chậm Hurom H310', 'price' => 7800000, 'img' => 'https://images.unsplash.com/photo-1622597467827-02302307fc8b?q=80&w=400&auto=format&fit=crop'],
                ];
                @endphp

                @for($i = 1; $i <= 20; $i++)
                @php $p = $mockProducts[($i-1) % count($mockProducts)]; @endphp
                <div class="swiper-slide animate-product-card">
                    <div class="product-card h-100 mb-0">
                        <div class="product-img-wrapper">
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3 z-3">NEW</span>
                            <img src="{{ $p['img'] }}" alt="{{ $p['name'] }}">
                            <div class="product-actions">
                                <button class="btn btn-sm btn-white rounded-circle shadow-sm p-2"><i class="far fa-heart text-danger"></i></button>
                                <a href="#" class="btn btn-sm btn-white rounded-circle shadow-sm p-2"><i class="fas fa-expand text-primary-color"></i></a>
                            </div>
                        </div>
                        <div class="product-details text-center">
                            <span class="category-label">TOP SELLING</span>
                            <h5 class="fw-bold mb-2 h6"><a href="#" class="text-dark text-decoration-none">{{ $p['name'] }} #{{ $i }}</a></h5>
                            <div class="product-price mt-3">
                                {{ number_format($p['price']) }}đ
                            </div>
                            <button class="btn btn-primary-custom w-100 mt-3 py-2 extra-small fw-bold rounded-pill shadow-sm">THÊM VÀO GIỎ</button>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            
            <!-- Add Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        
        <div class="text-center mt-5 mt-lg-7">
            <a href="{{ route('client.products.index') }}" class="btn btn-outline-dark px-5 py-2 fw-bold text-uppercase" style="letter-spacing: 2px;">Xem thêm tất cả sản phẩm</a>
        </div>
    </div>

    <!-- Features Section -->
    <section class="bg-white py-5 mb-5 border-top border-bottom border-light shadow-sm">
        <div class="container py-5">
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="feature-icon mb-4 fs-1 text-primary-color"><i class="fas fa-truck-fast"></i></div>
                    <h5 class="fw-bold">Vận chuyển miễn phí</h5>
                    <p class="text-muted small">Cho mọi đơn hàng > 2 triệu</p>
                </div>
                <div class="col-md-3">
                    <div class="feature-icon mb-4 fs-1 text-primary-color"><i class="fas fa-clock-rotate-left"></i></div>
                    <h5 class="fw-bold">Hỗ trợ 24/7</h5>
                    <p class="text-muted small">Tận tâm, chu đáo mỗi ngày</p>
                </div>
                <div class="col-md-3">
                    <div class="feature-icon mb-4 fs-1 text-primary-color"><i class="fas fa-shield-halved"></i></div>
                    <h5 class="fw-bold">Thanh Toán An Toàn</h5>
                    <p class="text-muted small">Bảo mật thông tin khách hàng</p>
                </div>
                <div class="col-md-3">
                    <div class="feature-icon mb-4 fs-1 text-primary-color"><i class="fas fa-award"></i></div>
                    <h5 class="fw-bold">Chứng Nhận Chính Hãng</h5>
                    <p class="text-muted small">Nói không với hàng kém chất lượng</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Home Styles -->
    <style>
        .fw-800 { font-weight: 800; }
        .text-primary-color { color: #009688; }
        .bg-primary-light { background-color: #009688; }
        .line-height-tight { line-height: 1.1; }
        .tracking-wider { letter-spacing: 0.1em; }
        .shadow-xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        
        .main-hero-img {
            transition: transform 0.8s ease;
        }
        
        .hero-image-wrapper:hover .main-hero-img {
            transform: scale(1.02);
        }
        
        .hero-float-card {
            position: absolute;
            bottom: 20px;
            left: -30px;
            z-index: 10;
            width: 280px;
            animation: float 4s ease-in-out infinite;
        }
        
        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        .product-details {
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        
        .btn-white {
            background-color: white;
            color: #333;
        }
        
        .btn-white:hover {
            background-color: #f1f1f1;
        }

        @media (max-width: 991px) {
            .hero-float-card {
                left: 20px;
                bottom: -20px;
                width: 220px;
            }
        }
    </style>

@endsection
