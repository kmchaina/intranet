@props([
    // items: array of ['label' => 'Text', 'href' => route('...') | null]
    'items' => [],
    // optional: compact mode for tighter spacing
    'compact' => false,
])

@php
    $container = 'bg-white rounded-xl shadow-sm border border-gray-200 ' . ($compact ? 'p-3' : 'p-4');
    $listClass = 'flex items-center text-sm ' . ($compact ? 'space-x-2' : 'space-x-3');
@endphp

<nav aria-label="Breadcrumb" class="mb-4">
    <div class="{{ $container }}">
        <ol class="{{ $listClass }}">
            @php $count = count($items); @endphp
            @foreach ($items as $index => $item)
                @php $isLast = $index === $count - 1; @endphp
                <li class="flex items-center">
                    @if ($index === 0)
                        <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                        </svg>
                    @endif

                    @if (!empty($item['href']) && !$isLast)
                        <a href="{{ $item['href'] }}" class="text-blue-600 hover:text-blue-800 transition-colors font-medium">{{ $item['label'] }}</a>
                    @else
                        <span class="text-gray-700 font-semibold">{{ $item['label'] }}</span>
                    @endif

                    @unless($isLast)
                        <svg class="w-4 h-4 text-gray-300 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    @endunless
                </li>
            @endforeach
        </ol>
    </div>
    {{ $slot }}
    {{-- Optional extra content can be slotted under breadcrumbs --}}
</nav>
