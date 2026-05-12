@props([
    'eyebrow' => null,
    'title',
    'actionText' => null,
    'actionHref' => null,
])

<div class="luxe-section-header mb-5 d-flex align-items-end justify-content-between gap-4">
    <div class="luxe-section-header__content">
        @if($eyebrow)
            <span class="luxe-section-header__eyebrow text-uppercase fw-bold text-accent mb-2 d-block">{{ $eyebrow }}</span>
        @endif
        <h2 class="luxe-section-header__title fw-800 fs-1 mb-0">{{ $title }}</h2>
    </div>

    @if($actionText && $actionHref)
        <a href="{{ $actionHref }}" class="luxe-section-header__link fw-bold text-primary d-flex align-items-center gap-2 transition-all">
            {{ $actionText }}
            <i class="fas fa-arrow-right fs-7"></i>
        </a>
    @endif
</div>
