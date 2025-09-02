@extends('layouts.dashbo            <!-- Form -->
            <form action="{{ route('announcements.update', $announcement) }}" method="POST" class="space-y-8">
                @csrf
                @method('PATCH')
                <div class="bg-white/95 backdrop-blur-md rounded-xl border border-gray-200 shadow-lg p-8">
                    <!-- Title -->
                    <div class="mb-6">
@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')
@section('page-subtitle', 'Update your announcement details')

@section('content')
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <a href="{{ route('announcements.show', $announcement) }}" 
                       class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors mr-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Announcement
                    </a>
                    </div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Announcement</h1>
                <p class="text-gray-600 mt-2">Update your announcement details</p>
            </div>

            <!-- Form -->
            <form action="{{ route('announcements.update', $announcement) }}" method="POST" class="space-y-8">
                @csrf
                @method('PATCH')
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 shadow-lg p-8">
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-3">
                        Announcement Title
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $announcement->title) }}"
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 placeholder-gray-500 shadow-sm"
                           placeholder="Enter a clear, descriptive title..."
                           required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-semibold text-gray-800 mb-3">
                        Content
                    </label>
                    <textarea id="content" 
                              name="content" 
                              rows="8"
                              class="w-full px-4 py-3 bg-white/50 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 placeholder-gray-500 resize-vertical"
                              placeholder="Write your announcement content here..."
                              required>{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category and Priority Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-800 mb-3">
                            Category
                        </label>
                        <select id="category" 
                                name="category"
                                class="w-full px-4 py-3 bg-white/50 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                            <option value="general" {{ old('category', $announcement->category) === 'general' ? 'selected' : '' }}>General</option>
                            <option value="urgent" {{ old('category', $announcement->category) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="event" {{ old('category', $announcement->category) === 'event' ? 'selected' : '' }}>Event</option>
                            <option value="policy" {{ old('category', $announcement->category) === 'policy' ? 'selected' : '' }}>Policy</option>
                            <option value="training" {{ old('category', $announcement->category) === 'training' ? 'selected' : '' }}>Training</option>
                        </select>
                        @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            Priority
                        </label>
                        <select id="priority" 
                                name="priority"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white">
                            <option value="low" {{ old('priority', $announcement->priority) === 'low' ? 'selected' : '' }}>Low Priority</option>
                            <option value="medium" {{ old('priority', $announcement->priority) === 'medium' ? 'selected' : '' }}>Medium Priority</option>
                            <option value="high" {{ old('priority', $announcement->priority) === 'high' ? 'selected' : '' }}>High Priority</option>
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
                            <input type="radio" 
                                   id="target_all" 
                                   name="target_scope" 
                                   value="all"
                                   {{ old('target_scope', $announcement->target_scope) === 'all' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_all" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                All NIMR Staff
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" 
                                   id="target_headquarters" 
                                   name="target_scope" 
                                   value="headquarters"
                                   {{ old('target_scope', $announcement->target_scope) === 'headquarters' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_headquarters" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                Headquarters Only
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" 
                                   id="target_my_centre" 
                                   name="target_scope" 
                                   value="my_centre"
                                   {{ old('target_scope', $announcement->target_scope) === 'my_centre' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_my_centre" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                My Centre Only
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" 
                                   id="target_my_centre_stations" 
                                   name="target_scope" 
                                   value="my_centre_stations"
                                   {{ old('target_scope', $announcement->target_scope) === 'my_centre_stations' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_my_centre_stations" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                My Centre and Its Stations
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" 
                                   id="target_my_station" 
                                   name="target_scope" 
                                   value="my_station"
                                   {{ old('target_scope', $announcement->target_scope) === 'my_station' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_my_station" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                My Station Only
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" 
                                   id="target_all_centres" 
                                   name="target_scope" 
                                   value="all_centres"
                                   {{ old('target_scope', $announcement->target_scope) === 'all_centres' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_all_centres" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                All Centres (No Stations)
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" 
                                   id="target_all_stations" 
                                   name="target_scope" 
                                   value="all_stations"
                                   {{ old('target_scope', $announcement->target_scope) === 'all_stations' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="target_all_stations" class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                All Stations (No Centres)
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" 
                                   id="target_specific" 
                                   name="target_scope" 
                                   value="specific"
                                   {{ old('target_scope', $announcement->target_scope) === 'specific' ? 'checked' : '' }}
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

                <!-- Specific Target Selection -->
                <div id="specificTargetSection" class="{{ $announcement->target_scope === 'specific' ? '' : 'hidden' }} mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Target Centres -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                Select Centres
                            </label>
                            <div class="space-y-2 max-h-40 overflow-y-auto bg-gray-50 dark:bg-gray-800 rounded-xl p-3">
                                @foreach ($centres as $centre)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="centre_{{ $centre->id }}" 
                                               name="target_centres[]" 
                                               value="{{ $centre->id }}"
                                               {{ in_array($centre->id, old('target_centres', $announcement->target_centres ?? [])) ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="centre_{{ $centre->id }}" class="ml-2 text-sm text-gray-900 dark:text-white">
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
                                        <input type="checkbox" 
                                               id="station_{{ $station->id }}" 
                                               name="target_stations[]" 
                                               value="{{ $station->id }}"
                                               {{ in_array($station->id, old('target_stations', $announcement->target_stations ?? [])) ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="station_{{ $station->id }}" class="ml-2 text-sm text-gray-900 dark:text-white">
                                            {{ $station->name }}
                                            @if ($station->centre)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $station->centre->name }})</span>
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
                            <label for="published_at" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                Publish Date
                            </label>
                            <input type="datetime-local" 
                                   id="published_at" 
                                   name="published_at" 
                                   value="{{ old('published_at', $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white">
                            @error('published_at')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expiry Date -->
                        <div>
                            <label for="expires_at" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                Expiry Date (Optional)
                            </label>
                            <input type="datetime-local" 
                                   id="expires_at" 
                                   name="expires_at" 
                                   value="{{ old('expires_at', $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '') }}"
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
                        <input type="checkbox" 
                               id="email_notification" 
                               name="email_notification" 
                               value="1"
                               {{ old('email_notification', $announcement->email_notification) ? 'checked' : '' }}
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Update Announcement
                    </button>

                    <a href="{{ route('announcements.show', $announcement) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-white/30 hover:bg-white/40 text-gray-800 font-semibold rounded-xl transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
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
</script>
@endpush
@endsection

