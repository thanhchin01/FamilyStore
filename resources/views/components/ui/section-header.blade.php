@props([
    'eyebrow',
    'title',
    'actionText' => null,
    'actionHref' => null,
    'class' => '',
])

<div {{ $attributes->merge(['class' => trim("tech-section__header {$class}")]) }}>
    <div>
        <span class="tech-section__eyebrow">{{ $eyebrow }}</span>
        <h2>{{ $title }}</h2>
    </div>

    @if ($actionText && $actionHref)
        <a href="{{ $actionHref }}">{{ $actionText }}</a>
    @endif
</div>
