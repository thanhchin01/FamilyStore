@extends('client.layout')

@section('title', $product->name . ' - K-Q Store')

@section('content')
    <div class="container product-detail-page mb-5 py-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('client.products.index') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="product-detail-card product-detail-gallery">
                    @if ($product->image)
                        <img src="{{ \Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                            class="img-fluid rounded-3" alt="{{ $product->name }}" id="productMainImg">
                    @else
                        <div class="product-detail-placeholder">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>

                @if ($product->productImages->count() > 0)
                    <div class="mt-4 row g-2 product-detail-thumbs">
                        <div class="col-3">
                            <img src="{{ \Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                                class="img-fluid rounded-3 thumbnail active-thumbnail"
                                alt="thumbnail"
                                onclick="updateMainImage(this.src, this)">
                        </div>
                        @foreach ($product->productImages as $img)
                            <div class="col-3">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    class="img-fluid rounded-3 thumbnail opacity-50"
                                    alt="thumbnail"
                                    onclick="updateMainImage(this.src, this)">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-md-6">
                <div class="product-detail-card product-info-card">
                    <span class="badge bg-soft-primary text-primary mb-3">{{ $product->category->name ?? 'Sản phẩm cao cấp' }}</span>
                    <h1 class="fw-bold mb-3 h2">{{ $product->name }}</h1>

                    <div class="d-flex align-items-center flex-wrap gap-2 mb-4">
                        <div class="text-warning me-2 small">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-secondary extra-small">(Đánh giá 5/5)</span>
                        <span class="mx-2 text-secondary opacity-50 d-none d-sm-inline">|</span>
                        @if ($product->stock > 0)
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
                        <div class="product-qty">
                            <button class="btn btn-outline-secondary btn-sm px-3 rounded-pill" onclick="changeQty(-1)">
                                <i class="fas fa-minus small"></i>
                            </button>
                            <input type="number" class="form-control text-center rounded-pill border-secondary fw-bold" id="productQty" value="1" min="1">
                            <button class="btn btn-outline-secondary btn-sm px-3 rounded-pill" onclick="changeQty(1)">
                                <i class="fas fa-plus small"></i>
                            </button>
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
