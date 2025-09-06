{{-- Standard Form Field Component --}}
@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'options' => [],
    'rows' => 3,
    'help' => '',
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    @if ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-base focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error($name) border-red-500 @enderror">{{ old($name, $value) }}</textarea>
    @elseif($type === 'select')
        <select id="{{ $name }}" name="{{ $name }}" {{ $required ? 'required' : '' }}
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-base focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error($name) border-red-500 @enderror">
            @if ($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    @elseif($type === 'file')
        <input type="file" id="{{ $name }}" name="{{ $name }}" {{ $required ? 'required' : '' }}
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-base focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error($name) border-red-500 @enderror"
            {{ $attributes }}>
    @elseif($type === 'checkbox')
        <div class="flex items-center">
            <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1"
                {{ old($name, $value) ? 'checked' : '' }}
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error($name) border-red-500 @enderror">
            @if ($label)
                <label for="{{ $name }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    {{ $label }}
                </label>
            @endif
        </div>
    @else
        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
            value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-base focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error($name) border-red-500 @enderror">
    @endif

    @if ($help)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
