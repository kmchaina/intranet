@extends('layouts.dashboard')

@section('title', 'News Feed')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">News Feed</h1>
                <p class="text-lg text-gray-600">Stay updated with the latest news and activities from all NIMR locations</p>
            </div>

            @can('create', App\Models\News::class)
                <a href="{{ route('news.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create News
                </a>
            @endcan
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search News</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Search by title or content..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>
                </div>

                <!-- Location Filter -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <select name="location" id="location"
                        class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                        <option value="">All Locations</option>
                        <option value="headquarters" {{ request('location') == 'headquarters' ? 'selected' : '' }}>
                            Headquarters</option>
                        <option value="mwanza" {{ request('location') == 'mwanza' ? 'selected' : '' }}>Mwanza</option>
                        <option value="mbeya" {{ request('location') == 'mbeya' ? 'selected' : '' }}>Mbeya</option>
                        <option value="tanga" {{ request('location') == 'tanga' ? 'selected' : '' }}>Tanga</option>
                        <option value="tabora" {{ request('location') == 'tabora' ? 'selected' : '' }}>Tabora</option>
                        <option value="dodoma" {{ request('location') == 'dodoma' ? 'selected' : '' }}>Dodoma</option>
                        <option value="amani" {{ request('location') == 'amani' ? 'selected' : '' }}>Amani</option>
                        <option value="mpwapwa" {{ request('location') == 'mpwapwa' ? 'selected' : '' }}>Mpwapwa</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit"
                        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        Filter
                    </button>
                    <a href="{{ route('news.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors duration-200">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Featured News (if any) -->
        @if ($featuredNews->isNotEmpty())
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    Featured News
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach ($featuredNews as $news)
                        <article
                            class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                            @if ($news && is_object($news) && $news->featured_image)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ asset('storage/' . $news->featured_image) }}"
                                        alt="{{ $news && is_object($news) ? $news->title : 'News Article' }}"
                                        class="w-full h-48 object-cover">
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                        {{ $news && is_object($news) ? $news->location_display : 'Unknown Location' }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $news && is_object($news) ? $news->priority_color : 'bg-gray-100 text-gray-800' }}">
                                        {{ $news && is_object($news) && $news->priority ? ucfirst($news->priority) : 'Normal' }}
                                        Priority
                                    </span>
                                </div>

                                <h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <a href="{{ route('news.show', $news) }}"
                                        class="hover:text-teal-600 transition-colors">
                                        {{ $news && is_object($news) ? $news->title : 'News Article' }}
                                    </a>
                                </h3>

                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ $news && is_object($news) && $news->excerpt ? $news->excerpt : 'No excerpt available' }}
                                </p>

                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        <span>{{ $news && is_object($news) && $news->author ? $news->author->name : 'Unknown Author' }}</span>
                                        <span>{{ $news && is_object($news) && $news->published_at ? $news->published_at->diffForHumans() : 'Unknown Date' }}</span>
                                        <span>{{ $news && is_object($news) ? $news->reading_time : '' }}</span>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            {{ $news && is_object($news) ? $news->views_count : 0 }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                </path>
                                            </svg>
                                            {{ $news && is_object($news) ? $news->likes_count : 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Regular News Grid -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Latest News</h2>

            @if ($news->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($news as $item)
                        <article
                            class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                            @if ($item && is_object($item) && $item->featured_image)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ asset('storage/' . $item->featured_image) }}"
                                        alt="{{ $item && is_object($item) ? $item->title : 'News Article' }}"
                                        class="w-full h-48 object-cover">
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $item && is_object($item) ? $item->location_display : 'Unknown Location' }}
                                    </span>
                                    @if ($item && is_object($item) && $item->priority && $item->priority !== 'normal')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item && is_object($item) ? $item->priority_color : 'bg-gray-100 text-gray-800' }}">
                                            {{ $item && is_object($item) && $item->priority ? ucfirst($item->priority) : 'Normal' }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <a href="{{ route('news.show', $item) }}"
                                        class="hover:text-teal-600 transition-colors">
                                        {{ $item && is_object($item) ? $item->title : 'News Article' }}
                                    </a>
                                </h3>

                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ $item && is_object($item) && $item->excerpt ? $item->excerpt : 'No excerpt available' }}
                                </p>

                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <span>{{ $item && is_object($item) && $item->author ? $item->author->name : 'Unknown Author' }}</span>
                                        <span>•</span>
                                        <span>{{ $item && is_object($item) && $item->published_at ? $item->published_at->diffForHumans() : 'Unknown Date' }}</span>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            {{ $item && is_object($item) ? $item->views_count : 0 }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                </path>
                                            </svg>
                                            {{ $item && is_object($item) ? $item->likes_count : 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($news && is_object($news))
                    <div class="mt-8">
                        @if (method_exists($news, 'withQueryString'))
                            {{ $news->withQueryString()->links() }}
                        @elseif(method_exists($news, 'links'))
                            {{ $news->links() }}
                        @endif
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No news found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if (request()->hasAny(['search', 'location']))
                            Try adjusting your search criteria or clearing the filters.
                        @else
                            Get started by creating the first news post.
                        @endif
                    </p>
                    @can('create', App\Models\News::class)
                        <div class="mt-6">
                            <a href="{{ route('news.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                                Create News Post
                            </a>
                        </div>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .aspect-w-16 {
                position: relative;
                padding-bottom: 56.25%;
            }

            .aspect-w-16>* {
                position: absolute;
                height: 100%;
                width: 100%;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
            }
        </style>
    @endpush
@endsection
