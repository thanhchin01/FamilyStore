@extends('client.layout')

@section('title', $product->name . ' - K-Q Store')

@section('content')

    <div class="container mb-5 py-4">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('client.products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Product Image -->
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="product-detail-img-container bg-white p-3 rounded-4 shadow-sm h-100 d-flex align-items-center justify-content-center overflow-hidden">
                    @if($product->image)
                        <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" class="img-fluid rounded-3" alt="{{ $product->name }}" id="productMainImg">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center w-100" style="min-height: 400px;">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>
                
                {{-- Thumbnails --}}
                @if($product->productImages->count() > 0)
                <div class="mt-4 row g-2">
                    <div class="col-3">
                        <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                             class="img-fluid rounded-3 thumbnail active-thumbnail" 
                             alt="thumbnail" 
                             style="cursor: pointer; border: 2px solid transparent;"
                             onclick="updateMainImage(this.src, this)">
                    </div>
                    @foreach($product->productImages as $img)
                    <div class="col-3">
                        <img src="{{ asset('storage/' . $img->image_path) }}" 
                             class="img-fluid rounded-3 thumbnail opacity-50" 
                             alt="thumbnail" 
                             style="cursor: pointer; border: 2px solid transparent;"
                             onclick="updateMainImage(this.src, this)">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="product-info-card p-4 h-100 bg-white rounded-4 shadow-sm">
                    <span class="badge bg-soft-primary text-primary mb-3">{{ $product->category->name ?? 'Sản phẩm cao cấp' }}</span>
                    <h1 class="fw-bold mb-3 h2">{{ $product->name }}</h1>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="text-warning me-2 small">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-secondary extra-small">(Đánh giá 5/5)</span>
                        <span class="mx-3 text-secondary opacity-50">|</span>
                        @if($product->stock > 0)
                            <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i> Còn hàng</span>
                        @else
                            <span class="text-danger small fw-bold"><i class="fas fa-times-circle me-1"></i> Hết hàng</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h2 class="display-6 fw-bold text-teal mb-0">{{ number_format($product->price) }}đ</h2>
                    </div>

                    <div class="product-desc-short text-secondary mb-4">
                        {{ $product->description }}
                    </div>

                    <div class="mb-5">
                        <label class="fw-bold mb-2 small text-uppercase tracking-wider">Số lượng mua:</label>
                        <div class="d-flex align-items-center gap-2" style="max-width: 160px;">
                            <button class="btn btn-outline-secondary btn-sm px-3 rounded-pill" onclick="changeQty(-1)"><i class="fas fa-minus small"></i></button>
                            <input type="number" class="form-control text-center rounded-pill border-secondary fw-bold" id="productQty" value="1" min="1">
                            <button class="btn btn-outline-secondary btn-sm px-3 rounded-pill" onclick="changeQty(1)"><i class="fas fa-plus small"></i></button>
                        </div>
                    </div>

                    <div class="d-grid gap-3 d-md-flex mb-4">
                        <button type="button" 
                                class="btn btn-primary-custom btn-lg rounded-pill flex-grow-1 fw-bold py-3 shadow-sm add-to-cart-btn"
                                data-id="{{ $product->id }}"
                                onclick="this.setAttribute('data-qty', document.getElementById('productQty').value)">
                            <i class="fas fa-cart-plus me-2"></i> THÊM VÀO GIỎ HÀNG
                        </button>
                        <button class="btn btn-outline-danger btn-lg rounded-pill px-4 shadow-sm">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>

                    <hr class="my-5 opacity-50">

                    <div class="product-specs">
                        <h6 class="fw-800 text-uppercase small tracking-wider mb-4">Thông số kỹ thuật:</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex border-bottom pb-2">
                                <span class="text-secondary w-40 small">Thương hiệu:</span>
                                <span class="fw-600 small">{{ $product->brand ?? '---' }}</span>
                            </li>
                            <li class="mb-3 d-flex border-bottom pb-2">
                                <span class="text-secondary w-40 small">Model:</span>
                                <span class="fw-600 small">{{ $product->model ?? '---' }}</span>
                            </li>
                            <li class="mb-3 d-flex border-bottom pb-2">
                                <span class="text-secondary w-40 small">Bảo hành:</span>
                                <span class="fw-600 small">{{ $product->warranty_months ? $product->warranty_months . ' tháng' : 'Không có' }}</span>
                            </li>
                            <li class="mb-3 d-flex border-bottom pb-2">
                                <span class="text-secondary w-40 small">Phân loại:</span>
                                <span class="fw-600 small">{{ $product->category->name ?? '---' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-soft-primary { background-color: rgba(0, 150, 136, 0.1); }
        .text-teal { color: #009688; }
        .fw-800 { font-weight: 800; }
        .fw-600 { font-weight: 600; }
        .extra-small { font-size: 0.75rem; }
        .tracking-wider { letter-spacing: 0.1em; }
        .w-40 { width: 40%; }

        .product-detail-img-container img {
            transition: transform 0.5s ease;
            max-height: 500px;
            object-fit: contain;
        }

        .active-thumbnail {
            border-color: #009688 !important;
            opacity: 1 !important;
        }

        .thumbnail:hover {
            opacity: 1 !important;
        }
    </style>

    @push('scripts')
    <script>
        function changeQty(amount) {
            const input = document.getElementById('productQty');
            let val = parseInt(input.value) + amount;
            if (val < 1) val = 1;
            input.value = val;
        }

        function updateMainImage(src, thumb) {
            document.getElementById('productMainImg').src = src;
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active-thumbnail', 'opacity-50'));
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.add('opacity-50'));
            thumb.classList.remove('opacity-50');
            thumb.classList.add('active-thumbnail');
        }
    </script>
    @endpush

@endsection
