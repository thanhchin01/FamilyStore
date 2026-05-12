@props([
    'variant' => 'primary', // primary, secondary, outline, danger, ghost
    'size' => 'md',       // sm, md, lg
    'type' => 'button',
    'href' => null,
    'icon' => null,
    'isLoading' => false,
])

@php
    $baseClasses = 'btn-luxe d-inline-flex align-items-center justify-content-center gap-2 font-weight-bold transition-all';
    
    $variantClasses = [
        'primary' => 'btn-luxe-primary',
        'secondary' => 'btn-luxe-secondary',
        'outline' => 'btn-luxe-outline',
        'danger' => 'btn-luxe-danger',
        'ghost' => 'btn-luxe-ghost',
    ][$variant] ?? 'btn-luxe-primary';

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 fs-7',
        'md' => 'px-4 py-2.5 fs-6',
        'lg' => 'px-5 py-3 fs-5',
    ][$size] ?? 'px-4 py-2.5 fs-6';

    $classes = "{$baseClasses} {$variantClasses} {$sizeClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) <i class="{{ $icon }}"></i> @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @disabled($isLoading)>
        @if($isLoading)
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        @elseif($icon)
            <i class="{{ $icon }}"></i>
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
