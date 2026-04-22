@props([
    'status',
])

@php
    $classes = [
        'pending' => 'status-pending',
        'processing' => 'status-processing',
        'shipping' => 'status-shipping',
        'completed' => 'status-completed',
        'cancelled' => 'status-cancelled',
    ];

    $labels = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang chuẩn bị',
        'shipping' => 'Đang giao',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'order-status ' . ($classes[$status] ?? 'status-pending')]) }}>
    {{ $labels[$status] ?? ucfirst($status) }}
</span>
