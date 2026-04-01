@extends('layouts.store')

@section('title', 'K-Q Store - Trang chủ')

@section('content')

    <!-- Hero Section -->
    <header class="hero-section mb-5">
        <div class="container bg-primary text-white rounded-5 overflow-hidden shadow-lg" style="background: linear-gradient(135deg, #5c62ec 0%, #4b52d1 100%); position: relative;">
            <div class="row align-items-center p-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <span class="badge bg-white text-primary mb-3 py-2 px-3 fw-bold rounded-pill">Chào mừng bạn!</span>
                    <h1 class="display-4 fw-bold mb-4">Mua sắm thông minh <br> cùng Khoa Quyên Store</h1>
                    <p class="lead mb-4 opacity-75">Chúng tôi cung cấp các sản phẩm chất lượng hàng đầu với giá cả cạnh tranh nhất thị trường. Chất lượng là cam kết hàng đầu của chúng tôi.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#featured" class="btn btn-light btn-lg rounded-pill px-4 fw-bold">Khám phá ngay</a>
                        <a href="#" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold">Xem ưu đãi</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center position-relative">
                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1000&auto=format&fit=crop" class="img-fluid rounded-4 shadow-sm hero-image" style="max-height: 400px; object-fit: cover;" alt="Hero Image">
                    <div class="hero-decoration-1"></div>
                    <div class="hero-decoration-2"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Categories / Featured Sections -->
    <div class="container mb-5" id="featured">
        <div class="text-center mb-5">
            <h2 class="fw-bold section-title">Sản phẩm nổi bật</h2>
            <div class="title-underline mx-auto"></div>
            <p class="text-secondary mt-3">Khám phá bộ sưu tập những sản phẩm tốt nhất của chúng tôi được tuyển chọn kỹ lưỡng.</p>
        </div>

        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card h-100">
                    <div class="product-img-wrapper">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                        <div class="product-actions">
                            <button class="btn btn-sm btn-light rounded-circle shadow-sm mx-1"><i class="far fa-heart text-danger"></i></button>
                            <button class="btn btn-sm btn-light rounded-circle shadow-sm mx-1"><i class="fas fa-expand text-primary"></i></button>
                        </div>
                    </div>
                    <div class="p-3">
                        <span class="text-secondary small mb-1 d-block">Danh mục</span>
                        <h5 class="fw-bold mb-2 h6"><a href="{{ route('store.products.show', $product['slug']) }}" class="text-dark text-decoration-none">{{ $product['name'] }}</a></h5>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold text-primary">{{ number_format($product['price']) }}đ</span>
                            <button class="btn btn-primary-custom btn-sm py-1 px-3">
                                <i class="fas fa-plus small"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Newsletter / Subscribe -->
    <section class="container mb-5">
        <div class="bg-white p-5 rounded-4 shadow-sm text-center">
            <h3 class="fw-bold mb-3">Đăng ký nhận tin mới nhất</h3>
            <p class="text-secondary mb-4 mx-auto" style="max-width: 600px;">Đừng bỏ lỡ các ưu đãi độc quyền và các sản phẩm mới nhất từ cửa hàng của chúng tôi. Nhận thông báo trực tiếp qua email của bạn.</p>
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <form class="d-flex gap-2">
                        <input type="email" class="form-control form-control-lg rounded-pill px-4" placeholder="Nhập email của bạn...">
                        <button class="btn btn-primary-custom px-4 rounded-pill">Gửi</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Styles -->
    <style>
        .hero-section .container {
            border: none;
            overflow: visible;
        }

        .hero-image {
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .hero-image:hover {
            transform: scale(1.05) rotate(2deg);
        }

        .section-title {
            position: relative;
            z-index: 1;
        }

        .title-underline {
            width: 80px;
            height: 4px;
            background-color: var(--primary);
            border-radius: 2px;
        }

        .product-actions {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            transition: bottom 0.3s ease;
            padding-bottom: 20px;
        }

        .product-card:hover .product-actions {
            bottom: 0;
        }

        /* Hero Decorations */
        .hero-decoration-1 {
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -50px;
            right: -20px;
            z-index: 0;
        }

        .hero-decoration-2 {
            position: absolute;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: 30px;
            right: 150px;
            z-index: 0;
        }
    </style>

@endsection
