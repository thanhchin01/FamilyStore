@props([
    'product',
    'footerText' => null,
    'ctaIcon' => 'fa-plus',
])

@php
    $rating = number_format($product->reviews_avg_rating ?? 5, 1);
    $footerText = $footerText ?? ($product->stock > 0 ? 'Còn hàng' : 'Tạm hết hàng');
    $image = $product->image
        ? (\Illuminate\Support\Str::startsWith($product->image, 'http')
            ? $product->image
            : asset('storage/' . $product->image))
        : null;
@endphp

<article {{ $attributes->merge(['class' => 'product-card product-card--tech h-100']) }}>
    <a href="{{ route('client.products.show', $product->slug) }}" class="product-card__image">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $product->name }}">
        @else
            <span class="product-card__placeholder"><i class="fas fa-image"></i></span>
        @endif
    </a>

    <div class="product-card__body">
        <div class="product-card__meta">
            <span>{{ $product->category->name ?? 'Thiết bị công nghệ' }}</span>
            <div class="product-card__rating">
                <i class="fas fa-star"></i>
                <strong>{{ $rating }}</strong>
                <small>({{ $product->reviews_count ?? 0 }})</small>
            </div>
        </div>

        <h3>
            <a href="{{ route('client.products.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>

        <div class="product-card__footer">
            <div>
                <strong>{{ number_format($product->price) }}đ</strong>
                <span>{{ $footerText }}</span>
            </div>

            <button type="button" class="product-card__cta add-to-cart-btn" data-id="{{ $product->id }}">
                <i class="fas {{ $ctaIcon }}"></i>
            </button>
        </div>
    </div>
</article>
