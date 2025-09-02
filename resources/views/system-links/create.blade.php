@extends('layouts.dashboard')

@section('title', 'Add New Link')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">🔗 Add New Link</h1>
                    <p class="text-gray-600 mt-1">Add a new system link or external tool for easy access</p>
                </div>
                <div>
                    <a href="{{ route('system-links.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Links
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow-sm rounded-lg">
                <form action="{{ route('system-links.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                maxlength="255"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                                placeholder="Enter link title...">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                placeholder="Brief description of what this link is for...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="url" class="block text-sm font-medium text-gray-700 mb-1">
                                URL <span class="text-red-500">*</span>
                            </label>
                            <input type="url" name="url" id="url" value="{{ old('url') }}" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('url') border-red-500 @enderror"
                                placeholder="https://example.com">
                            @error('url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Categories and Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category" id="category" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                                <option value="">Select a category...</option>
                                @foreach (\App\Models\SystemLink::getCategories() as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="access_level" class="block text-sm font-medium text-gray-700 mb-1">
                                Access Level <span class="text-red-500">*</span>
                            </label>
                            <select name="access_level" id="access_level" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('access_level') border-red-500 @enderror">
                                <option value="">Select access level...</option>
                                @foreach (\App\Models\SystemLink::getAccessLevels() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('access_level') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('access_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="color_scheme" class="block text-sm font-medium text-gray-700 mb-1">
                                Color Scheme
                            </label>
                            <select name="color_scheme" id="color_scheme"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('color_scheme') border-red-500 @enderror">
                                @foreach (\App\Models\SystemLink::getColorSchemes() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('color_scheme', 'blue') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('color_scheme')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">
                                Icon (Emoji)
                            </label>
                            <input type="text" name="icon" id="icon" value="{{ old('icon') }}" maxlength="10"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('icon') border-red-500 @enderror"
                                placeholder="🌐">
                            <p class="mt-1 text-xs text-gray-500">Use an emoji or leave blank for default</p>
                            @error('icon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Link Options</h3>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" name="opens_new_tab" id="opens_new_tab" value="1"
                                    {{ old('opens_new_tab') ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="opens_new_tab" class="ml-2 block text-sm text-gray-900">
                                    Open in new tab
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="requires_vpn" id="requires_vpn" value="1"
                                    {{ old('requires_vpn') ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="requires_vpn" class="ml-2 block text-sm text-gray-900">
                                    Requires VPN connection
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                    {{ old('is_featured') ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    Feature this link (appears in featured section)
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active (visible to users)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('system-links.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Add Link
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview Section -->
            <div class="mt-6 bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Preview</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div id="link-preview" class="max-w-sm">
                        <!-- Preview will be populated by JavaScript -->
                        <div class="bg-white rounded-lg border border-gray-200 bg-blue-100 border-blue-300 p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <span class="text-2xl" id="preview-icon">🌐</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-blue-800 truncate" id="preview-title">
                                            Link Title
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2" id="preview-description">
                                Link description will appear here...
                            </p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700"
                                    id="preview-category">
                                    Category
                                </span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"
                                    id="preview-access">
                                    Access Level
                                </span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700 hidden"
                                    id="preview-vpn">
                                    🔒 VPN Required
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                const previewTitle = document.getElementById('preview-title');
                const previewDescription = document.getElementById('preview-description');
                const previewIcon = document.getElementById('preview-icon');
                const previewCategory = document.getElementById('preview-category');
                const previewAccess = document.getElementById('preview-access');
                const previewVpn = document.getElementById('preview-vpn');
                const previewCard = document.querySelector('#link-preview > div');

                const colorMap = {
                    'blue': ['bg-blue-100', 'text-blue-800', 'border-blue-300'],
                    'green': ['bg-green-100', 'text-green-800', 'border-green-300'],
                    'red': ['bg-red-100', 'text-red-800', 'border-red-300'],
                    'purple': ['bg-purple-100', 'text-purple-800', 'border-purple-300'],
                    'yellow': ['bg-yellow-100', 'text-yellow-800', 'border-yellow-300'],
                    'gray': ['bg-gray-100', 'text-gray-800', 'border-gray-300']
                };

                function updatePreview() {
                    const title = document.getElementById('title').value || 'Link Title';
                    const description = document.getElementById('description').value ||
                        'Link description will appear here...';
                    const icon = document.getElementById('icon').value || '🌐';
                    const category = document.getElementById('category').value;
                    const accessLevel = document.getElementById('access_level').value;
                    const colorScheme = document.getElementById('color_scheme').value || 'blue';
                    const requiresVpn = document.getElementById('requires_vpn').checked;

                    // Update preview content
                    previewTitle.textContent = title;
                    previewDescription.textContent = description;
                    previewIcon.textContent = icon;

                    // Update category
                    const categoryOptions = @json(\App\Models\SystemLink::getCategories());
                    previewCategory.textContent = categoryOptions[category] || 'Category';

                    // Update access level
                    const accessOptions = @json(\App\Models\SystemLink::getAccessLevels());
                    previewAccess.textContent = accessOptions[accessLevel] || 'Access Level';

                    // Update VPN badge
                    if (requiresVpn) {
                        previewVpn.classList.remove('hidden');
                    } else {
                        previewVpn.classList.add('hidden');
                    }

                    // Update colors
                    const colors = colorMap[colorScheme] || colorMap['blue'];
                    previewCard.className = `bg-white rounded-lg border border-gray-200 ${colors[0]} ${colors[2]} p-4`;
                    previewTitle.className = `text-lg font-semibold ${colors[1]} truncate`;
                }

                // Add event listeners
                ['title', 'description', 'icon', 'category', 'access_level', 'color_scheme'].forEach(id => {
                    document.getElementById(id).addEventListener('input', updatePreview);
                });

                document.getElementById('requires_vpn').addEventListener('change', updatePreview);

                // Initial preview update
                updatePreview();
            });
        </script>
    @endpush
@endsection
