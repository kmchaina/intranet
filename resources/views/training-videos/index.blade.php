@extends('layouts.dashboard')

@section('title', 'Training Videos')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto p-6">
            <x-breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Training Videos']]" />

            <!-- Header Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                <div class="bg-purple-600 p-6 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center border border-white/20">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">🎥 Training Videos</h1>
                                <p class="text-purple-100 mt-1">Enhance your skills with our comprehensive training library
                                </p>
                            </div>
                        </div>
                        @can('manage-training')
                            <a href="{{ route('training-videos.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-white hover:bg-purple-50 text-purple-700 text-sm font-medium rounded-lg transition-colors duration-200 border border-white shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Video
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            @php
                $totalVideos = $videos->total();
                $featuredVideos = App\Models\TrainingVideo::where('is_featured', true)
                    ->where('is_active', true)
                    ->count();
                $totalViews = App\Models\TrainingVideo::where('is_active', true)->sum('view_count');
                $categoriesCount = App\Models\TrainingVideo::where('is_active', true)->distinct('category')->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Total Videos</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $totalVideos }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Featured</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $featuredVideos }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Total Views</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($totalViews) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Categories</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $categoriesCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 p-6">
                <form method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 min-w-48">
                        <input type="text" name="search" value="{{ $search }}" placeholder="🔍 Search videos..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    </div>
                    <div>
                        <select name="category"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                            <option value="">All Categories</option>
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}" {{ $category === $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="target_audience"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                            <option value="">All Audiences</option>
                            @foreach ($targetAudiences as $key => $label)
                                <option value="{{ $key }}" {{ $target_audience === $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors">
                        Search
                    </button>
                    @if ($search || $category || $target_audience)
                        <a href="{{ route('training-videos.index') }}"
                            class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-medium transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            @if ($videos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
                    @foreach ($videos as $video)
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                            <!-- Video Thumbnail -->
                            <div class="relative aspect-video bg-gray-100 cursor-pointer"
                                onclick="openVideoModal('{{ $video->video_url }}', '{{ addslashes($video->title) }}', '{{ addslashes($video->description ?? '') }}')">
                                @if (strpos($video->video_url, 'youtube.com') !== false || strpos($video->video_url, 'youtu.be') !== false)
                                    @php
                                        preg_match(
                                            '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/',
                                            $video->video_url,
                                            $matches,
                                        );
                                        $videoId = $matches[1] ?? '';
                                        $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
                                    @endphp
                                    <img src="{{ $thumbnailUrl }}" alt="{{ $video->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-purple-600">
                                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Play button overlay -->
                                <div
                                    class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div
                                        class="w-16 h-16 bg-white/90 rounded-full flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-8 h-8 text-purple-600 ml-1" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Featured badge -->
                                @if ($video->is_featured)
                                    <div class="absolute top-3 left-3">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 backdrop-blur-sm">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                            Featured
                                        </span>
                                    </div>
                                @endif

                                <!-- Duration -->
                                @if (isset($video->duration_minutes) && $video->duration_minutes)
                                    <div class="absolute bottom-3 right-3">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-black/70 text-white backdrop-blur-sm">
                                            {{ $video->duration_minutes }}m
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Video Details -->
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 flex-1 pr-2">
                                        {{ $video->title }}
                                    </h3>
                                    @can('manage-training')
                                        <div class="flex items-center space-x-1">
                                            <a href="{{ route('training-videos.edit', $video) }}"
                                                class="p-1 text-gray-400 hover:text-purple-600 transition-colors"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('training-videos.destroy', $video) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this video?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                                                    title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>

                                @if ($video->description)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                        {{ Str::limit($video->description, 80) }}</p>
                                @endif

                                <!-- Metadata -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $categories[$video->category] ?? $video->category }}
                                    </span>
                                    @if ($video->target_audience)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $targetAudiences[$video->target_audience] ?? $video->target_audience }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Stats -->
                                <div
                                    class="flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 pt-3">
                                    <div class="flex items-center space-x-4">
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            {{ number_format($video->view_count) }}
                                        </span>
                                    </div>
                                    <span>{{ $video->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $videos->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        @if ($search || $category || $target_audience)
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No videos found</h3>
                            <p class="text-gray-600 mb-6">No training videos match your current search criteria. Try
                                adjusting your filters.</p>
                            <a href="{{ route('training-videos.index') }}"
                                class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                                Clear Filters
                            </a>
                        @else
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No training videos found</h3>
                            <p class="text-gray-600 mb-6">Get started by creating your first training video to build your
                                learning library.</p>
                            @can('manage-training')
                                <a href="{{ route('training-videos.create') }}"
                                    class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Your First Video
                                </a>
                            @endcan
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Video Modal -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 id="modalTitle" class="text-lg font-semibold"></h3>
                    <button onclick="closeVideoModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <div id="modalVideoContainer" class="aspect-video mb-4"></div>
                    <p id="modalDescription" class="text-gray-600"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openVideoModal(videoUrl, title, description) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalDescription').textContent = description;

            const container = document.getElementById('modalVideoContainer');

            if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be')) {
                const videoId = extractYouTubeId(videoUrl);
                container.innerHTML = `
            <iframe class="w-full h-full rounded-lg" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>
        `;
            } else {
                container.innerHTML = `
            <video class="w-full h-full rounded-lg" controls>
                <source src="${videoUrl}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        `;
            }

            document.getElementById('videoModal').classList.remove('hidden');
        }

        function closeVideoModal() {
            document.getElementById('videoModal').classList.add('hidden');
            document.getElementById('modalVideoContainer').innerHTML = '';
        }

        function extractYouTubeId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length === 11) ? match[2] : null;
        }

        // Close modal when clicking outside
        document.getElementById('videoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeVideoModal();
            }
        });
    </script>
@endsection
