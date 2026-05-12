@props([
    'status',
])

@php
    $variants = [
        'pending' => 'primary',
        'processing' => 'info',
        'shipping' => 'warning',
        'completed' => 'success',
        'cancelled' => 'danger',
    ];

    $labels = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang chuẩn bị',
        'shipping' => 'Đang giao',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
    ];
@endphp

<x-ui.badge :variant="$variants[$status] ?? 'secondary'" {{ $attributes }}>
    {{ $labels[$status] ?? ucfirst($status) }}
</x-ui.badge>

