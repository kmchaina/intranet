{{-- Standard Card Component --}}
@props([
    'title' => '',
    'subtitle' => '',
    'actions' => '',
    'padding' => 'p-6',
    'spacing' => 'space-y-4',
])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
    @if ($title || $subtitle || $actions)
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    @if ($title)
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $title }}</h3>
                    @endif
                    @if ($subtitle)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
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

    <div class="{{ $padding }} {{ $spacing }}">
        {{ $slot }}
    </div>
</div>
