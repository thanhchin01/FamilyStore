@props([
    'icon' => 'fa-folder-open',
    'title' => 'Chưa có dữ liệu',
    'description' => 'Hiện tại chưa có thông tin nào để hiển thị trong mục này.',
])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <div class="empty-state__icon">
        <i class="fa-solid {{ $icon }}"></i>
    </div>
    <h3 class="empty-state__title">{{ $title }}</h3>
    <p class="empty-state__text">{{ $description }}</p>
    
    @if ($slot->isNotEmpty())
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
