@props([
    'variant' => 'primary', // primary, success, danger, warning, info, secondary, dark
    'pill' => true,
    'size' => 'md', // sm, md, lg
    'soft' => true, // Use a soft background (light tint)
])

@php
    $baseClasses = 'badge d-inline-flex align-items-center justify-content-center fw-bold transition-all';
    
    // Soft variant logic
    $variantClass = $soft ? "badge-soft-{$variant}" : "bg-{$variant}";
    
    $sizeClasses = [
        'sm' => 'px-2 py-1',
        'md' => 'px-2.5 py-1.5',
        'lg' => 'px-3 py-2',
    ];

    $rounding = $pill ? 'rounded-pill' : 'rounded-3';
@endphp

<span {{ $attributes->merge(['class' => "$baseClasses $variantClass {$sizeClasses[$size]} $rounding"]) }}>
    {{ $slot }}
</span>
