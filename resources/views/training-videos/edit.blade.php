@extends('layouts.dashboard')

@section('title', 'Edit Training Video')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('training-videos.show', $video) }}"
                        class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">Edit Training Video</h1>
                        <p class="text-lg text-gray-600 mt-1">Update video information</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('training-videos.update', $video) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Video URL and Type Detection -->
            <div class="mb-6">
                <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">
                    Video URL <span class="text-red-500">*</span>
                </label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $video->video_url) }}"
                    required placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/... or file path"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('video_url') border-red-500 @enderror">
                @error('video_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Current type: <span class="font-medium">{{ ucfirst($video->video_type) }}</span>
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $video->title) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                            placeholder="Describe what viewers will learn from this video..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $video->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                            <option value="">Select a category</option>
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('category', $video->category) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target Audience -->
                    <div>
                        <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-2">
                            Target Audience
                        </label>
                        <select id="target_audience" name="target_audience"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('target_audience') border-red-500 @enderror">
                            <option value="">Select target audience</option>
                            @foreach ($targetAudiences as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('target_audience', $video->target_audience) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('target_audience')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Duration -->
                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                            Duration (minutes)
                        </label>
                        <input type="number" id="duration_minutes" name="duration_minutes"
                            value="{{ old('duration_minutes', $video->duration_minutes) }}" min="1"
                            placeholder="e.g., 15"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('duration_minutes') border-red-500 @enderror">
                        @error('duration_minutes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Optional: Helps users know the time commitment
                        </p>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            Tags
                        </label>
                        <input type="text" id="tags" name="tags" value="{{ old('tags', $video->tags) }}"
                            placeholder="excel, pivot tables, data analysis"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('tags') border-red-500 @enderror">
                        @error('tags')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Separate multiple tags with commas
                        </p>
                    </div>

                    <!-- Options -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                {{ old('is_featured', $video->is_featured) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                                Feature this video
                            </label>
                        </div>
                        <p class="text-sm text-gray-500 ml-6">
                            Featured videos appear prominently and are recommended to new users
                        </p>
                    </div>

                    <!-- Current Video Preview -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Video</label>
                        <div class="border border-gray-300 rounded-lg overflow-hidden">
                            <div class="aspect-video bg-gray-100">
                                @if ($video->video_type === 'youtube')
                                    @php
                                        preg_match(
                                            '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/',
                                            $video->video_url,
                                            $matches,
                                        );
                                        $videoId = $matches[1] ?? '';
                                        $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg";
                                    @endphp
                                    <img src="{{ $thumbnailUrl }}" alt="{{ $video->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">
                                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            <p><span class="font-medium">Views:</span> {{ $video->view_count }}</p>
                            <p><span class="font-medium">Added:</span> {{ $video->created_at->format('M j, Y') }}</p>
                            @if ($video->updated_at != $video->created_at)
                                <p><span class="font-medium">Last updated:</span>
                                    {{ $video->updated_at->format('M j, Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('training-videos.show', $video) }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Video
                    </button>
                </div>

                <form action="{{ route('training-videos.destroy', $video) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Are you sure you want to delete this video? This action cannot be undone.')"
                        class="bg-red-100 hover:bg-red-200 text-red-700 px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Video
                    </button>
                </form>
            </div>
        </form>
    </div>
    </div>

    <script>
        document.getElementById('video_url').addEventListener('blur', function() {
            const url = this.value.trim();

            if (!url) return;

            // Show a simple notification about video type detection
            let videoType = 'unknown';
            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                videoType = 'YouTube';
            } else if (url.includes('vimeo.com')) {
                videoType = 'Vimeo';
            } else if (url.match(/\.(mp4|webm|ogg)$/i)) {
                videoType = 'Video File';
            }

            if (videoType !== 'unknown') {
                const typeIndicator = document.createElement('div');
                typeIndicator.className = 'mt-1 text-sm text-green-600';
                typeIndicator.textContent = `✓ Detected as ${videoType} video`;

                // Remove any existing type indicator
                const existing = this.parentNode.querySelector('.text-green-600');
                if (existing) {
                    existing.remove();
                }

                this.parentNode.appendChild(typeIndicator);

                // Remove after 3 seconds
                setTimeout(() => {
                    if (typeIndicator.parentNode) {
                        typeIndicator.remove();
                    }
                }, 3000);
            }
        });
    </script>
@endsection
