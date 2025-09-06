{{-- Standard Form Component --}}
@props([
    'action' => '',
    'method' => 'POST',
    'title' => '',
    'subtitle' => '',
    'submitText' => 'Submit',
    'cancelRoute' => null,
])

<div class="max-w-2xl mx-auto px-4 py-6">
    {{-- Form Header --}}
    @if ($title)
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    {{-- Form Card --}}
    <x-standard-card>
        <form action="{{ $action }}" method="{{ $method }}" enctype="multipart/form-data">
            @csrf
            @if ($method !== 'POST' && $method !== 'GET')
                @method($method)
            @endif

            <div class="space-y-4">
                {{ $slot }}
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                @if ($cancelRoute)
                    <a href="{{ $cancelRoute }}"
                        class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Cancel
                    </a>
                @endif
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    {{ $submitText }}
                </button>
            </div>
        </form>
    </x-standard-card>
</div>
