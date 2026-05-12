@props(['product', 'ctaIcon' => 'fa-cart-shopping'])

<div class="luxe-product-card" data-product-id="{{ $product->id }}">
    <div class="luxe-product-card__image">
        <a href="{{ route('client.products.show', $product->slug) }}">
            @if ($product->image)
                <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     loading="lazy">
            @else
                <div class="luxe-product-card__placeholder">
                    <i class="fas fa-image fa-2x"></i>
                </div>
            @endif
        </a>
        
        @if($product->is_featured)
            <span class="luxe-product-card__badge">Featured</span>
        @endif

        <button type="button" class="luxe-product-card__wishlist {{ auth()->check() && auth()->user()->wishlist->contains($product->id) ? 'active' : '' }}" 
                onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }}, this)"
                title="Add to Wishlist">
            <i class="fa{{ auth()->check() && auth()->user()->wishlist->contains($product->id) ? 's' : 'r' }} fa-heart"></i>
        </button>

        <button type="button" class="luxe-product-card__quickview" 
                onclick="event.preventDefault(); event.stopPropagation(); openQuickView({{ $product->id }})"
                title="Quick View">
            <i class="fas fa-eye"></i>
        </button>
    </div>

    <div class="luxe-product-card__content">
        <div class="luxe-product-card__meta">
            <span class="luxe-product-card__category">{{ $product->category->name ?? 'Uncategorized' }}</span>
            <div class="luxe-product-card__rating">
                <i class="fas fa-star"></i>
                <span>{{ number_format($product->reviews_avg_rating ?? 5.0, 1) }}</span>
            </div>
        </div>

        <h3 class="luxe-product-card__title">
            <a href="{{ route('client.products.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>

        <div class="luxe-product-card__footer">
            <div class="luxe-product-card__price">
                <span class="luxe-product-card__amount">{{ number_format($product->price) }}đ</span>
            </div>
            
            <button type="button" class="luxe-product-card__add-btn" 
                    onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $product->id }}, 1)"
                    title="Add to Cart">
                <i class="fas {{ $ctaIcon }}"></i>
            </button>
        </div>
    </div>
</div>
