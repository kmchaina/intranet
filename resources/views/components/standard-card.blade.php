{{-- NIMR Standard Card Component --}}
@props([
    'title' => '',
    'subtitle' => '',
    'actions' => '',
    'padding' => 'p-6',
    'spacing' => 'space-y-4',
])

<div class="nimr-card">
    @if ($title || $subtitle || $actions)
        <div class="nimr-card-header">
            <div class="flex items-center justify-between">
                <div>
                    @if ($title)
                        <h3 class="nimr-heading-3">{{ $title }}</h3>
                    @endif
                    @if ($subtitle)
                        <p class="nimr-text-muted mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
                @if ($actions)
                    <div class="flex items-center space-x-3">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="nimr-card-body {{ $spacing }}">
        {{ $slot }}
    </div>
</div> 
