@extends('client.layout')

@section('title', 'Tất cả sản phẩm - K-Q Store')

@section('content')
    <div class="container mb-5 py-5">
        <form action="{{ route('client.products.index') }}" method="GET" id="filterForm">
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="row g-5">
                <!-- Premium Sidebar Filters -->
                <div class="col-lg-3 mb-4">
                    <div class="glass-panel p-4 sticky-top shadow-md border-0" style="top: 110px; z-index: 10;">
                        <div class="d-flex align-items-center mb-4">
                            <i class="fas fa-sliders-h text-primary-color me-2"></i>
                            <h5 class="fw-bold mb-0">Bộ lọc thông minh</h5>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-800 extra-small text-uppercase tracking-wider mb-3">Danh mục
                                hàng</label>
                            <div class="form-check custom-check mb-3">
                                <input class="form-check-input filter-input" type="radio" name="category" value=""
                                    id="catAll" {{ !request('category') ? 'checked' : '' }}>
                                <label class="form-check-label small fw-500" for="catAll">Tất cả danh mục</label>
                            </div>
                            @foreach ($categories as $cat)
                                <div class="form-check custom-check mb-3">
                                    <input class="form-check-input filter-input" type="radio" name="category"
                                        value="{{ $cat->slug }}" id="cat{{ $cat->id }}"
                                        {{ request('category') == $cat->slug ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-500"
                                        for="cat{{ $cat->id }}">{{ $cat->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-800 extra-small text-uppercase tracking-wider mb-3">Mức giá ưu
                                tiên</label>
                            <select name="price_range"
                                class="form-select border-light border-2 rounded-3 py-2 small shadow-sm filter-input">
                                <option value="" {{ !request('price_range') ? 'selected' : '' }}>Mọi mức giá</option>
                                <option value="1" {{ request('price_range') == '1' ? 'selected' : '' }}>Dưới 5.000.000đ
                                </option>
                                <option value="2" {{ request('price_range') == '2' ? 'selected' : '' }}>5 - 15 triệu
                                </option>
                                <option value="3" {{ request('price_range') == '3' ? 'selected' : '' }}>15 - 30 triệu
                                </option>
                                <option value="4" {{ request('price_range') == '4' ? 'selected' : '' }}>Trên 30 triệu
                                </option>
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-800 extra-small text-uppercase tracking-wider mb-3">Thương
                                hiệu</label>
                            <div class="brands-grid d-flex flex-wrap gap-2">
                                <input type="hidden" name="brand" id="brandInput" value="{{ request('brand') }}">
                                @foreach ($brands as $brand)
                                    <span
                                        class="badge {{ request('brand') == $brand ? 'bg-primary' : 'bg-light text-dark' }} py-2 px-3 border border-light-subtle rounded-3 brand-badge"
                                        style="cursor: pointer;" data-brand="{{ $brand }}">
                                        {{ $brand }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary-custom py-3">ÁP DỤNG LỌC</button>
                            <a href="{{ route('client.products.index') }}" class="btn btn-link small text-muted mt-2">Xóa
                                bộ lọc</a>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="col-lg-9">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
                        <div>
                            <h2 class="fw-800 display-6 mb-1">Cửa hàng</h2>
                            <p class="text-muted small mb-0">Hiển thị {{ $products->firstItem() ?? 0 }} -
                                {{ $products->lastItem() ?? 0 }} trong số {{ $products->total() }} sản phẩm</p>
                        </div>
                        <div class="d-flex gap-3 align-items-center bg-white p-2 rounded-4 shadow-sm border border-light">
                            <span class="small text-muted ps-2"><i class="fas fa-sort-amount-down me-2"></i>Sắp xếp:</span>
                            <select name="sort"
                                class="form-select border-0 bg-transparent shadow-none w-auto fw-600 extra-small filter-input"
                                style="cursor: pointer;">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>MỚI NHẤT
                                </option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>GIÁ THẤP
                                    ĐẾN CAO</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>GIÁ CAO
                                    ĐẾN THẤP</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-4">
                        @forelse($products as $product)
                            <div class="col-6 col-md-4">
                                <div class="product-card h-100"
                                    onclick="window.location.href='{{ route('client.products.show', $product->slug) }}'"
                                    style="cursor: pointer;">
                                    <div class="product-img-wrapper">
                                        @if ($product->image)
                                            <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                style="height: 250px;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="product-actions" onclick="event.stopPropagation();">
                                            <button type="button"
                                                class="btn btn-md btn-white rounded-circle shadow-md p-2"><i
                                                    class="far fa-heart text-danger"></i></button>
                                            <a href="{{ route('client.products.show', $product->slug) }}"
                                                class="btn btn-md btn-white rounded-circle shadow-md p-2"><i
                                                    class="fas fa-expand text-primary-color"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-details text-center">
                                        <span class="category-label">{{ $product->category->name ?? 'CAO CẤP' }}</span>
                                        <h5 class="fw-bold mb-2 h6" style="min-height: 40px;">
                                            <a href="{{ route('client.products.show', $product->slug) }}"
                                                class="text-dark text-decoration-none">{{ $product->name }}</a>
                                        </h5>
                                        <div class="product-price mt-3">
                                            {{ number_format($product->price) }}đ
                                        </div>
                                        <div class="d-flex gap-2 mt-3" onclick="event.stopPropagation();">
                                            <button type="button"
                                                class="btn btn-outline-primary-custom flex-grow-1 py-1 extra-small fw-600 rounded-pill add-to-cart-btn"
                                                data-id="{{ $product->id }}">
                                                THÊM GIỎ
                                            </button>
                                            <a href="{{ route('client.cart') }}"
                                                class="btn btn-primary-custom flex-grow-1 py-2 extra-small fw-600 rounded-pill">MUA
                                                NGAY</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 py-5 text-center bg-white rounded-5 shadow-sm">
                                <i class="fas fa-box-open fa-3x text-light mb-4"></i>
                                <h4 class="fw-bold">Rất tiếc!</h4>
                                <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp với bộ lọc của bạn.</p>
                                <a href="{{ route('client.products.index') }}" class="btn btn-primary-custom mt-3">XEM
                                    TẤT
                                    CẢ SẢN PHẨM</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Premium Pagination -->
                    <div class="mt-7 d-flex justify-content-center">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .fw-800 {
            font-weight: 800;
        }

        .fw-700 {
            font-weight: 700;
        }

        .fw-600 {
            font-weight: 600;
        }

        .fw-500 {
            font-weight: 500;
        }

        .text-primary-color {
            color: #009688;
        }

        .extra-small {
            font-size: 0.7rem;
        }

        .tracking-wider {
            letter-spacing: 0.05em;
        }

        .mt-7 {
            margin-top: 5rem;
        }

        .custom-check .form-check-input {
            width: 1.25em;
            height: 1.25em;
            border-width: 2px;
            cursor: pointer;
        }

        .custom-check .form-check-input:checked {
            background-color: #009688;
            border-color: #009688;
        }

        .pagination {
            gap: 10px;
        }

        .pagination .page-item .page-link {
            width: 45px;
            height: 45px;
            border-radius: 12px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            color: #1e293b;
            font-weight: 600;
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .pagination .page-item.active .page-link {
            background-color: #009688 !important;
            color: white !important;
            box-shadow: 0 4px 6px rgba(0, 150, 136, 0.2);
        }

        .btn-white {
            background-color: white;
            color: #333;
        }

        .btn-white:hover {
            background-color: #f8fafc;
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .brand-badge {
            transition: all 0.2s;
        }

        .brand-badge:hover {
            background-color: #009688 !important;
            color: white !important;
        }

        .brand-badge.bg-primary {
            background-color: #009688 !important;
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle brand filtering
                const brandBadges = document.querySelectorAll('.brand-badge');
                const brandInput = document.getElementById('brandInput');
                const filterForm = document.getElementById('filterForm');

                brandBadges.forEach(badge => {
                    badge.addEventListener('click', function() {
                        const brand = this.getAttribute('data-brand');
                        if (brandInput.value === brand) {
                            brandInput.value = ''; // Deselect
                        } else {
                            brandInput.value = brand;
                        }
                        filterForm.submit();
                    });
                });

                // Handle sorting and other filter inputs
                const filterInputs = document.querySelectorAll('.filter-input');
                filterInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        filterForm.submit();
                    });
                });

            });
        </script>
    @endpush
@endsection
