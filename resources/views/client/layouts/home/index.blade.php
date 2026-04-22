@extends('client.layout')

@section('title', 'K-Q Store - Công nghệ cho không gian sống hiện đại')

@section('content')
    <section class="tech-hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <div class="tech-hero__content">
                        <span class="tech-badge">Không gian sống công nghệ</span>
                        <h1>Thiết bị gia dụng hiện đại, giao diện mua sắm cao cấp và rõ ràng hơn.</h1>
                        <p>
                            Khoa Quyen Store chọn lọc những sản phẩm công nghệ gia dụng đáng tin cậy, dễ so sánh,
                            dễ mua và phù hợp cho nhu cầu quản lý lẫn trải nghiệm khách hàng hiện đại.
                        </p>

                        <div class="tech-hero__actions">
                            <a href="{{ route('client.products.index') }}" class="btn btn-tech-primary">Khám phá sản phẩm</a>
                            <a href="#featured-products" class="btn btn-tech-secondary">Xem nổi bật</a>
                        </div>

                        <div class="tech-hero__metrics">
                            <div>
                                <strong>500+</strong>
                                <span>Sản phẩm đang bán</span>
                            </div>
                            <div>
                                <strong>24/7</strong>
                                <span>Hỗ trợ nhanh</span>
                            </div>
                            <div>
                                <strong>4.9/5</strong>
                                <span>Mức hài lòng trung bình</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="tech-hero__visual">
                        <div class="tech-hero__glow"></div>
                        <img src="{{ asset('images/client/banner.png') }}" alt="Thiết bị nổi bật">

                        <div class="tech-float-card">
                            <span>Smart Search</span>
                            <strong>Tìm đúng sản phẩm nhanh hơn</strong>
                        </div>

                        <div class="tech-float-card is-bottom">
                            <span>Premium Selection</span>
                            <strong>Danh mục rõ ràng, mua sắm trực quan</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tech-section">
        <div class="container">
            <x-ui.section-header
                eyebrow="Danh mục nổi bật"
                title="Đi thẳng vào nhóm sản phẩm bạn cần"
                action-text="Xem toàn bộ danh mục"
                :action-href="route('client.products.index')" />

            <div class="tech-category-grid">
                @foreach ($categories as $category)
                    <a href="{{ route('client.products.index', ['category' => $category->slug]) }}" class="tech-category-card">
                        <span class="tech-category-card__icon">
                            <i class="fas fa-microchip"></i>
                        </span>
                        <strong>{{ $category->name }}</strong>
                        <small>{{ $category->products_count }} sản phẩm</small>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="tech-section tech-section--dense" id="featured-products">
        <div class="container">
            <x-ui.section-header
                eyebrow="Sản phẩm nổi bật"
                title="Lựa chọn công nghệ dành cho ngôi nhà hiện đại"
                action-text="Mở toàn bộ catalog"
                :action-href="route('client.products.index')" />

            <div class="row g-4">
                @foreach ($featuredProducts as $product)
                    <div class="col-sm-6 col-xl-3">
                        <x-ui.product-card :product="$product" cta-icon="fa-plus" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="tech-section">
        <div class="container">
            <x-ui.section-header
                eyebrow="Mới cập nhật"
                title="Sản phẩm mới lên kệ"
                action-text="Xem hàng mới"
                :action-href="route('client.products.index', ['sort' => 'newest'])" />

            <div class="tech-mini-grid">
                @foreach ($latestProducts as $product)
                    <a href="{{ route('client.products.show', $product->slug) }}" class="tech-mini-card">
                        <div class="tech-mini-card__thumb">
                            @if ($product->image)
                                <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}">
                            @else
                                <span><i class="fas fa-image"></i></span>
                            @endif
                        </div>
                        <div class="tech-mini-card__body">
                            <span>{{ $product->category->name ?? 'Thiết bị' }}</span>
                            <strong>{{ $product->name }}</strong>
                            <small>{{ number_format($product->price) }}đ</small>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
