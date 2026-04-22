@extends('client.layout')

@section('title', 'Tất cả sản phẩm - K-Q Store')

@section('content')
    <section class="catalog-hero">
        <div class="container">
            <div class="catalog-hero__inner">
                <div>
                    <span class="tech-badge">Product Catalog</span>
                    <h1>Danh mục sản phẩm được sắp xếp rõ ràng, dễ lọc và dễ so sánh.</h1>
                    <p>Tìm nhanh theo tên, danh mục, thương hiệu và khoảng giá để ra quyết định nhanh hơn.</p>
                </div>
                <div class="catalog-hero__meta">
                    <strong>{{ $products->total() }}</strong>
                    <span>Sản phẩm đang hiển thị trong hệ thống</span>
                </div>
            </div>
        </div>
    </section>

    <div class="container catalog-page">
        <form action="{{ route('client.products.index') }}" method="GET" id="filterForm" class="row g-4 align-items-start">
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <div class="col-xl-3">
                <aside class="catalog-filter">
                    <div class="catalog-filter__section">
                        <div class="catalog-filter__title">
                            <i class="fas fa-sliders"></i>
                            <span>Bộ lọc</span>
                        </div>

                        <label class="catalog-filter__label">Danh mục</label>
                        <div class="catalog-filter__options">
                            <label class="filter-chip">
                                <input class="filter-input" type="radio" name="category" value=""
                                    {{ !request('category') ? 'checked' : '' }}>
                                <span>Tất cả</span>
                            </label>
                            @foreach ($categories as $cat)
                                <label class="filter-chip">
                                    <input class="filter-input" type="radio" name="category" value="{{ $cat->slug }}"
                                        {{ request('category') == $cat->slug ? 'checked' : '' }}>
                                    <span>{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="catalog-filter__section">
                        <label class="catalog-filter__label" for="priceRange">Khoảng giá</label>
                        <select name="price_range" id="priceRange" class="form-select filter-input">
                            <option value="" {{ !request('price_range') ? 'selected' : '' }}>Mọi mức giá</option>
                            <option value="1" {{ request('price_range') == '1' ? 'selected' : '' }}>Dưới 5 triệu</option>
                            <option value="2" {{ request('price_range') == '2' ? 'selected' : '' }}>5 - 15 triệu</option>
                            <option value="3" {{ request('price_range') == '3' ? 'selected' : '' }}>15 - 30 triệu</option>
                            <option value="4" {{ request('price_range') == '4' ? 'selected' : '' }}>Trên 30 triệu</option>
                        </select>
                    </div>

                    <div class="catalog-filter__section">
                        <label class="catalog-filter__label">Thương hiệu</label>
                        <input type="hidden" name="brand" id="brandInput" value="{{ request('brand') }}">
                        <div class="catalog-filter__brands">
                            @foreach ($brands as $brand)
                                <button type="button"
                                    class="brand-pill {{ request('brand') == $brand ? 'is-active' : '' }}"
                                    data-brand="{{ $brand }}">
                                    {{ $brand }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="catalog-filter__actions">
                        <button type="submit" class="btn btn-tech-primary w-100">Áp dụng bộ lọc</button>
                        <a href="{{ route('client.products.index') }}" class="btn btn-tech-secondary w-100">Đặt lại</a>
                    </div>
                </aside>
            </div>

            <div class="col-xl-9">
                <div class="catalog-toolbar">
                    <div>
                        <h2>Cửa hàng công nghệ</h2>
                        <p>Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} trong {{ $products->total() }} sản phẩm</p>
                    </div>

                    <div class="catalog-toolbar__sort">
                        <span>Sắp xếp</span>
                        <select name="sort" class="form-select filter-input">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                        </select>
                    </div>
                </div>

                <div class="row g-4">
                    @forelse ($products as $product)
                        <div class="col-sm-6 col-lg-4">
                            <x-ui.product-card
                                :product="$product"
                                :footer-text="$product->brand ?? 'Khoa Quyen Store'"
                                cta-icon="fa-cart-plus" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="catalog-empty">
                                <i class="fas fa-box-open"></i>
                                <h3>Không tìm thấy sản phẩm phù hợp</h3>
                                <p>Thử bỏ bớt bộ lọc hoặc chuyển sang từ khóa khác để mở rộng kết quả.</p>
                                <a href="{{ route('client.products.index') }}" class="btn btn-tech-primary">Xem tất cả sản phẩm</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="catalog-pagination">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const brandBadges = document.querySelectorAll('.brand-pill');
                const brandInput = document.getElementById('brandInput');
                const filterForm = document.getElementById('filterForm');

                brandBadges.forEach(badge => {
                    badge.addEventListener('click', function() {
                        const brand = this.getAttribute('data-brand');
                        brandInput.value = brandInput.value === brand ? '' : brand;
                        filterForm.submit();
                    });
                });

                document.querySelectorAll('.filter-input').forEach(input => {
                    input.addEventListener('change', function() {
                        filterForm.submit();
                    });
                });
            });
        </script>
    @endpush
@endsection
