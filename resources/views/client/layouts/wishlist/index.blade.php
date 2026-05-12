@extends('client.layout')

@section('title', 'Sản phẩm yêu thích - K-Q Store')

@section('content')
<div class="wishlist-page py-5 bg-light-subtle min-vh-100">
    <div class="container">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-5 animate__animated animate__fadeInDown">
            <div>
                <h2 class="fw-800 mb-1">Sản phẩm yêu thích</h2>
                <p class="text-secondary mb-0">Bạn đang có <span class="text-primary fw-bold wishlist-count">{{ count($wishlistItems) }}</span> sản phẩm trong danh sách</p>
            </div>
            <a href="{{ route('client.products.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-600">
                <i class="fas fa-shopping-cart me-2"></i>Tiếp tục mua sắm
            </a>
        </div>

        @if(count($wishlistItems) > 0)
            <div class="row g-4 wishlist-grid">
                @foreach($wishlistItems as $item)
                    <div class="col-6 col-md-4 col-lg-3 wishlist-item-container animate__animated animate__fadeInUp" data-product-id="{{ $item->product->id }}">
                        <x-ui.product-card :product="$item->product" />
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-wishlist text-center py-5 animate__animated animate__fadeIn">
                <div class="empty-icon-wrapper mb-4">
                    <div class="heart-pulse-container">
                        <i class="fas fa-heart fa-5x text-primary-subtle opacity-50"></i>
                    </div>
                </div>
                <h3 class="fw-bold">Danh sách đang trống</h3>
                <p class="text-secondary mb-4">Hãy thêm những sản phẩm bạn yêu thích để theo dõi chúng dễ dàng hơn.</p>
                <a href="{{ route('client.products.index') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg">
                    KHÁM PHÁ SẢN PHẨM NGAY
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
    @vite(['resources/scss/client/pages/wishlist.scss'])
@endpush

@push('scripts')
    @vite(['resources/js/client/pages/wishlist.js'])
@endpush
