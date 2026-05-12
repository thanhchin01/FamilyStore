@props([
    'id',
    'title' => null,
    'size' => 'md', // sm, md, lg, xl
    'centered' => true,
    'footer' => null,
])

<div class="modal fade luxe-modal" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }} {{ $centered ? 'modal-dialog-centered' : '' }}">
        <div class="modal-content border-0 shadow-xl rounded-4">
            @if($title || $slot->isEmpty())
                <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="modal-title font-weight-bold fs-4" id="{{ $id }}Label">{{ $title }}</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif

            <div class="modal-body p-4">
                {{ $slot }}
            </div>

            @if($footer)
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
