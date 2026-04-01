@extends('layouts.store')

@section('title', $product['name'] . ' - K-Q Store')

@section('content')

    <div class="container mb-5">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('store.home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="#">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product['name'] }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Product Image -->
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="product-detail-img-container bg-white p-3 rounded-4 shadow-sm h-100 d-flex align-items-center justify-content-center overflow-hidden">
                    <img src="{{ $product['image'] }}" class="img-fluid rounded-3" alt="{{ $product['name'] }}" id="productMainImg">
                </div>
                <div class="mt-4 row g-2">
                    <div class="col-3">
                        <img src="{{ $product['image'] }}" class="img-fluid rounded-3 thumbnail opacity-50 active-thumbnail" alt="thumbnail" style="cursor: pointer; border: 2px solid transparent;">
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="product-info-card p-4 h-100 bg-white rounded-4 shadow-sm">
                    <span class="badge bg-soft-primary text-primary mb-3">Sản phẩm bán chạy</span>
                    <h1 class="fw-bold mb-3">{{ $product['name'] }}</h1>
                    <div class="d-flex align-items-center mb-4">
                        <div class="text-warning me-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-secondary small">(124 đánh giá)</span>
                        <span class="mx-3 text-secondary opacity-50">|</span>
                        <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i> Còn hàng</span>
                    </div>

                    <div class="mb-4">
                        <h2 class="display-6 fw-bold text-primary mb-0">{{ number_format($product['price']) }}đ</h2>
                        <p class="text-secondary small mt-1 text-decoration-line-through">{{ number_format($product['price'] * 1.2) }}đ</p>
                    </div>

                    <p class="text-secondary mb-4">{{ $product['description'] }}</p>

                    <div class="mb-4">
                        <label class="fw-bold mb-2">Số lượng:</label>
                        <div class="d-flex align-items-center gap-2" style="max-width: 150px;">
                            <button class="btn btn-outline-secondary btn-sm px-3 rounded-pill" onclick="changeQty(-1)"><i class="fas fa-minus small"></i></button>
                            <input type="number" class="form-control text-center rounded-pill border-secondary" id="productQty" value="1" min="1">
                            <button class="btn btn-outline-secondary btn-sm px-3 rounded-pill" onclick="changeQty(1)"><i class="fas fa-plus small"></i></button>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex mb-4">
                        <button class="btn btn-primary btn-lg rounded-3 flex-grow-1 fw-bold">
                            <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ
                        </button>
                        <button class="btn btn-outline-danger btn-lg rounded-3 px-4">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>

                    <hr class="my-4">

                    <div>
                        <h6 class="fw-bold mb-3">Thông tin chi tiết:</h6>
                        <ul class="list-unstyled mb-0">
                            @foreach($product['specs'] as $key => $value)
                            <li class="mb-2 d-flex">
                                <span class="text-secondary w-25">{{ $key }}:</span>
                                <span class="fw-medium">{{ $value }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Styles -->
    <style>
        .bg-soft-primary {
            background-color: rgba(92, 98, 236, 0.1);
        }

        .product-detail-img-container img {
            transition: transform 0.5s ease;
        }

        .product-detail-img-container:hover img {
            transform: scale(1.1);
        }

        .active-thumbnail {
            border-color: var(--primary) !important;
            opacity: 1 !important;
        }

        .thumbnail:hover {
            opacity: 1 !important;
        }
    </style>

    <script>
        function changeQty(amount) {
            const input = document.getElementById('productQty');
            let val = parseInt(input.value) + amount;
            if (val < 1) val = 1;
            input.value = val;
        }
    </script>

@endsection
