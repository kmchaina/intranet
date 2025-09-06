{{-- Standard Page Layout Component --}}
<div class="max-w-6xl mx-auto px-4 py-6">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
                @if (isset($subtitle))
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @if (isset($actions))
                <div class="flex items-center space-x-3">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>

    {{-- Content Area --}}
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>
