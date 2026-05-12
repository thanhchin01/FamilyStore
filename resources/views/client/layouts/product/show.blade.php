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
                            class="btn btn-outline-primary btn-lg rounded-pill flex-grow-1 fw-bold py-3 shadow-sm add-to-cart-btn"
                            data-id="{{ $product->id }}"
                            data-qty="1"
                            onmouseover="this.setAttribute('data-qty', document.getElementById('productQty').value)">
                            <i class="fas fa-cart-plus me-2"></i> THÊM VÀO GIỎ HÀNG
                        </button>
                        <button type="button"
                            class="btn btn-primary-gradient btn-lg rounded-pill flex-grow-1 fw-bold py-3 shadow-sm buy-now-btn"
                            data-id="{{ $product->id }}"
                            data-qty="1"
                            data-require-auth
                            onmouseover="this.setAttribute('data-qty', document.getElementById('productQty').value)">
                            MUA NGAY
                        </button>
                    </div>

                    <div class="product-meta-extra mt-4 pt-4 border-top">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center text-secondary small">
                                    <i class="fas fa-shipping-fast me-2 text-primary"></i>
                                    <span>Giao hàng nhanh toàn quốc</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center text-secondary small">
                                    <i class="fas fa-shield-alt me-2 text-primary"></i>
                                    <span>Bảo hành chính hãng</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description & Specs Section -->
        <div class="row mt-5 pt-4">
            <div class="col-lg-10 mx-auto">
                <div class="product-detail-card p-0 overflow-hidden">
                    <ul class="nav nav-tabs nav-justified bg-light border-0" id="productTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active border-0 py-3 fw-bold text-uppercase small" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-content" type="button" role="tab">
                                <i class="fas fa-file-alt me-2"></i>Mô tả sản phẩm
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link border-0 py-3 fw-bold text-uppercase small" id="spec-tab" data-bs-toggle="tab" data-bs-target="#spec-content" type="button" role="tab">
                                <i class="fas fa-microchip me-2"></i>Thông số kỹ thuật
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content p-4 p-md-5" id="productTabContent">
                        <div class="tab-pane fade show active" id="desc-content" role="tabpanel">
                            <div class="product-description text-secondary lh-lg">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="spec-content" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr class="border-bottom">
                                            <th class="py-3 text-secondary small w-25" style="min-width: 150px;">Thương hiệu</th>
                                            <td class="py-3 fw-600">{{ $product->brand ?? '---' }}</td>
                                        </tr>
                                        <tr class="border-bottom">
                                            <th class="py-3 text-secondary small">Model</th>
                                            <td class="py-3 fw-600">{{ $product->model ?? '---' }}</td>
                                        </tr>
                                        <tr class="border-bottom">
                                            <th class="py-3 text-secondary small">Mã sản phẩm (SKU)</th>
                                            <td class="py-3 fw-600">{{ $product->sku ?? '---' }}</td>
                                        </tr>
                                        <tr class="border-bottom">
                                            <th class="py-3 text-secondary small">Thời gian bảo hành</th>
                                            <td class="py-3 fw-600 text-primary">{{ $product->warranty_months ? $product->warranty_months . ' tháng' : 'Không có' }}</td>
                                        </tr>
                                        <tr class="border-bottom">
                                            <th class="py-3 text-secondary small">Danh mục</th>
                                            <td class="py-3 fw-600">{{ $product->category->name ?? '---' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-3 text-secondary small">Trạng thái</th>
                                            <td class="py-3 fw-600">
                                                @if($product->stock > 0)
                                                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>Sẵn hàng ({{ $product->stock }})</span>
                                                @else
                                                    <span class="text-danger"><i class="fas fa-times-circle me-1"></i>Hết hàng</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->count() > 0)
        <div class="row mt-5 pt-5">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="fw-800 mb-0">Sản phẩm liên quan</h3>
                    <a href="{{ route('client.products.index', ['category' => $product->category->slug ?? '']) }}" class="text-primary text-decoration-none fw-bold small hvr-forward">
                        Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="row g-4">
                    @foreach($relatedProducts as $related)
                        <div class="col-6 col-md-4 col-lg-3">
                            <x-ui.product-card :product="$related" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('styles')
        @vite(['resources/scss/client/pages/product-detail.scss'])
    @endpush

    @push('scripts')
        @vite(['resources/js/client/pages/product-detail.js'])
    @endpush
@endsection
