@props([
    'title' => null,
    'description' => null,
    'footer' => null,
    'padding' => true,
    'hover' => true,
])

<div {{ $attributes->merge(['class' => 'premium-card' . ($hover ? ' is-hoverable' : '')]) }}>
    @if ($title || $description || $slot->isNotEmpty())
        <div class="card-body {{ $padding ? '' : 'p-0' }}">
            @if ($title)
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $title }}</h5>
                        @if ($description)
                            <p class="text-muted small mb-0">{{ $description }}</p>
                        @endif
                    </div>
                    @if (isset($headerAction))
                        {{ $headerAction }}
                    @endif
                </div>
            @endif

            {{ $slot }}
        </div>
    @endif

    @if ($footer)
        <div class="card-footer bg-transparent border-top border-light py-3">
            {{ $footer }}
        </div>
    @endif
</div>
