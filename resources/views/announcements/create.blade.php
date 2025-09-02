@extends('layouts.dashboard')

@section('title', 'Create Announcement')
@section('page-title', 'Create New Announcement')
@section('page-subtitle', 'Share important information with your colleagues')

@section('content')
    <div class="p-6">
        <!-- Header Section -->
        <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 shadow-lg p-6 mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('announcements.index') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors mr-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Announcements
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Create New Announcement</h1>
            <p class="text-gray-600 mt-2">Share important information with your colleagues</p>
        </div>

        <!-- Form -->
        <div class="bg-white/95 backdrop-blur-md rounded-xl border border-gray-200 shadow-lg p-8">
            <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-3">
                        Announcement Title
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-500"
                        placeholder="Enter a clear, descriptive title..." required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        Content
                    </label>
                    <textarea id="content" name="content" rows="8"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-500 resize-vertical"
                        placeholder="Write your announcement content here..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Attachments -->
                <div class="mb-6">
                    <label for="attachments" class="block text-sm font-semibold text-gray-800 mb-3">
                        Attachments (Optional)
                    </label>
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-gray-400 transition-colors">
                        <input type="file" id="attachments" name="attachments[]" multiple
                            accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar" class="hidden"
                            onchange="updateFileList(this)">
                        <label for="attachments" class="cursor-pointer">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-gray-600 mb-2">
                                <span class="font-medium text-blue-600">Click to upload files</span>
                                or drag and drop
                            </p>
                            <p class="text-sm text-gray-500">
                                PDF, DOC, images, ZIP files up to 10MB each
                            </p>
                        </label>
                    </div>
                    <div id="fileList" class="mt-4 space-y-2"></div>
                    @error('attachments.*')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category and Priority Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            Category
                        </label>
                        <select id="category" name="category"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white">
                            <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General</option>
                            <option value="urgent" {{ old('category') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="event" {{ old('category') === 'event' ? 'selected' : '' }}>Event</option>
                            <option value="policy" {{ old('category') === 'policy' ? 'selected' : '' }}>Policy</option>
                            <option value="training" {{ old('category') === 'training' ? 'selected' : '' }}>Training
                            </option>
                        </select>
                        @error('category')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            Priority
                        </label>
                        <select id="priority" name="priority"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white">
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low Priority</option>
                            <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium Priority
                            </option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High Priority
                            </option>
                        </select>
                        @error('priority')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Target Audience Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        Target Audience
                    </label>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="radio" id="target_all" name="target_scope" value="all"
                                {{ old('target_scope', 'all') === 'all' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_all" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                All NIMR Staff
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="target_headquarters" name="target_scope" value="headquarters"
                                {{ old('target_scope') === 'headquarters' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_headquarters"
                                class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                Headquarters Only
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="target_my_centre" name="target_scope" value="my_centre"
                                {{ old('target_scope') === 'my_centre' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_my_centre" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                My Centre Only
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="target_my_centre_stations" name="target_scope"
                                value="my_centre_stations"
                                {{ old('target_scope') === 'my_centre_stations' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_my_centre_stations"
                                class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                My Centre and Its Stations
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="target_my_station" name="target_scope" value="my_station"
                                {{ old('target_scope') === 'my_station' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_my_station" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                My Station Only
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="target_all_centres" name="target_scope" value="all_centres"
                                {{ old('target_scope') === 'all_centres' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_all_centres"
                                class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                All Centres (No Stations)
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="target_all_stations" name="target_scope" value="all_stations"
                                {{ old('target_scope') === 'all_stations' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_all_stations"
                                class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                All Stations (No Centres)
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="target_specific" name="target_scope" value="specific"
                                {{ old('target_scope') === 'specific' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_specific" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                Specific Centres/Stations
                            </label>
                        </div>
                    </div>
                    @error('target_scope')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Specific Target Selection (Hidden by default) -->
                <div id="specificTargetSection" class="hidden mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Target Centres -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                Select Centres
                            </label>
                            <div class="space-y-2 max-h-40 overflow-y-auto bg-gray-50 dark:bg-gray-800 rounded-xl p-3">
                                @foreach ($centres as $centre)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="centre_{{ $centre->id }}" name="target_centres[]"
                                            value="{{ $centre->id }}"
                                            {{ in_array($centre->id, old('target_centres', [])) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="centre_{{ $centre->id }}"
                                            class="ml-2 text-sm text-gray-900 dark:text-white">
                                            {{ $centre->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Target Stations -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                Select Stations
                            </label>
                            <div class="space-y-2 max-h-40 overflow-y-auto bg-gray-50 dark:bg-gray-800 rounded-xl p-3">
                                @foreach ($stations as $station)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="station_{{ $station->id }}" name="target_stations[]"
                                            value="{{ $station->id }}"
                                            {{ in_array($station->id, old('target_stations', [])) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="station_{{ $station->id }}"
                                            class="ml-2 text-sm text-gray-900 dark:text-white">
                                            {{ $station->name }}
                                            @if ($station->centre)
                                                <span
                                                    class="text-xs text-gray-500 dark:text-gray-400">({{ $station->centre->name }})</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Publishing Options -->
                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Publish Date -->
                        <div>
                            <label for="published_at" class="block text-sm font-semibold text-gray-800 mb-3">
                                Publish Date (Optional)
                            </label>
                            <input type="datetime-local" id="published_at" name="published_at"
                                value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 shadow-sm">
                            <p class="mt-2 text-xs text-gray-600">Leave empty to publish immediately, or set a future date
                                to schedule</p>
                            @error('published_at')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expiry Date -->
                        <div>
                            <label for="expires_at"
                                class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                Expiry Date (Optional)
                            </label>
                            <input type="datetime-local" id="expires_at" name="expires_at"
                                value="{{ old('expires_at') }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white">
                            <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Leave empty for no expiry</p>
                            @error('expires_at')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Email Notification -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="email_notification" name="email_notification" value="1"
                            {{ old('email_notification') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="email_notification" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                            Send email notification to target audience
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                        Recipients will receive an email notification about this announcement
                    </p>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Publish Announcement
                    </button>

                    <a href="{{ route('announcements.index') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-white/30 hover:bg-white/40 text-gray-800 font-semibold rounded-xl transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const targetRadios = document.querySelectorAll('input[name="target_scope"]');
                const specificSection = document.getElementById('specificTargetSection');

                function toggleSpecificSection() {
                    const selectedValue = document.querySelector('input[name="target_scope"]:checked').value;
                    if (selectedValue === 'specific') {
                        specificSection.classList.remove('hidden');
                    } else {
                        specificSection.classList.add('hidden');
                    }
                }

                targetRadios.forEach(radio => {
                    radio.addEventListener('change', toggleSpecificSection);
                });

                // Initialize on page load
                toggleSpecificSection();
            });

            // File upload handling
            function updateFileList(input) {
                const fileList = document.getElementById('fileList');
                fileList.innerHTML = '';

                if (input.files.length > 0) {
                    Array.from(input.files).forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center justify-between bg-gray-100 rounded-lg p-3';
                        fileItem.innerHTML = `
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">${file.name}</p>
                                    <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                                </div>
                            </div>
                            <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        `;
                        fileList.appendChild(fileItem);
                    });
                }
            }

            function removeFile(index) {
                const input = document.getElementById('attachments');
                const dt = new DataTransfer();
                const files = Array.from(input.files);

                files.forEach((file, i) => {
                    if (i !== index) {
                        dt.items.add(file);
                    }
                });

                input.files = dt.files;
                updateFileList(input);
            }
        </script>
    @endpush
@endsection
