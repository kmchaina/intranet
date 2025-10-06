@extends('layouts.dashboard')
@php use Illuminate\Support\Facades\Schema; @endphp
@section('title', 'Dashboard')
@section('page-title')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-medium text-nimr-primary-900 tracking-tight">
                Welcome back,
                <span class="font-bold text-nimr-accent-600">
                    @php
                        $nameParts = explode(' ', auth()->user()->name);
                        $titles = ['Dr.', 'Dr', 'Prof.', 'Prof', 'Mr.', 'Mr', 'Mrs.', 'Mrs', 'Ms.', 'Ms', 'Miss'];
                        $firstName = $nameParts[0];
                        foreach ($titles as $title) {
                            if (stripos($firstName, $title) === 0) {
                                $firstName = isset($nameParts[1]) ? $nameParts[1] : $firstName;
                                break;
                            }
                        }
                    @endphp
                    {{ $firstName }}
                </span>!
            </h1>
        </div>
    </div>
@endsection
@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Recent Announcements (Back on top) -->
            <div class="nimr-card">
                <div class="nimr-card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="nimr-icon-primary">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Latest Announcements</h3>
                        </div>
                        <a href="{{ route('announcements.index') }}" class="nimr-btn-outline-sm">
                            <span>View all</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="nimr-card-body">
                    @if (isset($recentAnnouncements) && $recentAnnouncements->count())
                        <div class="space-y-4">
                            @foreach ($recentAnnouncements->take(3) as $index => $announcement)
                                <div
                                    class="group flex items-start space-x-4 p-4 rounded-xl transition-all duration-200 cursor-pointer border border-gray-100 hover:shadow-md hover:-translate-y-0.5">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-4 h-4 bg-nimr-primary-500 rounded-full mt-1.5 group-hover:scale-125 transition-transform shadow-sm">
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('announcements.show', $announcement) }}" class="block">
                                            <p
                                                class="text-base font-semibold text-gray-900 group-hover:text-nimr-primary-700 transition-colors line-clamp-2 mb-2">
                                                {{ $announcement->title }}
                                            </p>
                                            <div class="flex items-center space-x-3">
                                                <p class="text-sm text-gray-600">
                                                    By <span class="font-medium">{{ $announcement->creator->name }}</span>
                                                </p>
                                                <span class="text-gray-400">•</span>
                                                <p class="text-sm text-gray-500">
                                                    {{ $announcement->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            @if ($announcement->priority === 'high')
                                                <div class="mt-3">
                                                    <span class="nimr-badge-error">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        High Priority
                                                    </span>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No announcements yet</h3>
                            <p class="text-gray-500 max-w-sm mx-auto">When announcements are created, they'll appear here
                                for everyone to see.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Documents (Redesigned) -->
            <div class="nimr-card">
                <div class="nimr-card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="nimr-icon-secondary">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Document Library</h3>
                        </div>
                        <a href="{{ route('documents.index') }}" class="nimr-btn-outline-sm">
                            <span>Browse all</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="nimr-card-body">
                    @if (isset($recentDocuments) && $recentDocuments->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($recentDocuments->take(6) as $index => $document)
                                @php
                                    $fileExtension = strtolower(
                                        pathinfo($document->filename ?? '', PATHINFO_EXTENSION),
                                    );
                                @endphp
                                <div
                                    class="group relative bg-white border border-gray-200 rounded-xl p-4 hover:border-nimr-secondary-300 hover:shadow-lg transition-all duration-200 hover:-translate-y-1 cursor-pointer">
                                    <a href="{{ route('documents.show', $document) }}" class="block">
                                        <!-- File Type Badge -->
                                        <div class="absolute top-3 right-3">
                                            <span class="nimr-badge">{{ $fileExtension ?: 'file' }}</span>
                                        </div>
                                        <!-- Document Icon -->
                                        <div
                                            class="flex items-center justify-center w-16 h-16 nimr-icon-secondary rounded-xl mb-4 group-hover:scale-110 transition-transform">
                                            @if (in_array($fileExtension, ['pdf']))
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            @elseif(in_array($fileExtension, ['doc', 'docx']))
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            @elseif(in_array($fileExtension, ['xls', 'xlsx']))
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 10h18M3 14h18m-9-4v8m-7 0V7a2 2 0 012-2h14a2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                                                    </path>
                                                </svg>
                                            @elseif(in_array($fileExtension, ['ppt', 'pptx']))
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0v11a2 2 0 01-2 2H9a2 2 0 01-2-2V4m0 0h10M9 9h6m-6 4h6">
                                                    </path>
                                                </svg>
                                            @else
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            @endif
                                        </div>
                                        <!-- Document Details -->
                                        <div class="space-y-2">
                                            <h4
                                                class="font-semibold text-gray-900 text-sm leading-tight line-clamp-2 group-hover:text-nimr-secondary-700 transition-colors">
                                                {{ $document->title }}
                                            </h4>
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span class="font-medium">{{ $document->getFileSize() }}</span>
                                                <span>{{ $document->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if ($document->uploader)
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-4 h-4 bg-gray-300 rounded-full flex-shrink-0"></div>
                                                    <span
                                                        class="text-xs text-gray-600 truncate">{{ $document->uploader->name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <!-- Download Indicator -->
                                        <div
                                            class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <div
                                                class="w-6 h-6 bg-nimr-secondary-500 rounded-full flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-20 h-20 bg-nimr-secondary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-nimr-secondary-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No documents available</h3>
                            <p class="text-gray-500 max-w-sm mx-auto mb-4">Upload documents to share files and resources
                                with your team.</p>
                            <a href="{{ route('documents.create') }}" class="nimr-btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Upload Document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Access to Systems -->
            <div class="nimr-card">
                <div class="nimr-card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="nimr-icon-secondary">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Quick Access</h3>
                        </div>
                        <a href="{{ url('/system-links') }}" class="nimr-btn-outline-sm">
                            <span>See all</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="nimr-card-body">
                    @if (isset($quickAccessLinks) && $quickAccessLinks->count() > 0)
                        <div class="space-y-3">
                            @foreach ($quickAccessLinks as $link)
                                <a href="{{ $link->url }}" target="{{ $link->opens_new_tab ? '_blank' : '_self' }}"
                                    class="group flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 bg-nimr-secondary-100 rounded-lg flex items-center justify-center">
                                            <span class="text-sm">{{ $link->icon ?? '🔗' }}</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $link->title }}</span>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 group-hover:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div
                                class="w-12 h-12 mx-auto mb-4 bg-nimr-secondary-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-nimr-secondary-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">No quick access links available</p>
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('system-links.create') }}?category=quick_access"
                                    class="nimr-btn-secondary-sm">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Quick Access Link
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent News -->
            @if (isset($recentNews) && $recentNews->count())
                <div class="nimr-card">
                    <div class="nimr-card-header">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="nimr-icon-accent">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Latest News</h3>
                            </div>
                            <a href="{{ route('news.index') }}" class="nimr-btn-outline-sm">
                                <span>View all</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="nimr-card-body">
                        <div class="space-y-4">
                            @foreach ($recentNews->take(3) as $newsItem)
                                @if ($newsItem instanceof \App\Models\News)
                                    <div class="group relative">
                                        <a href="{{ route('news.show', $newsItem) }}"
                                            class="block p-3 rounded-xl hover:bg-nimr-accent-50 transition-colors border border-transparent hover:border-nimr-accent-200">
                                            <!-- Priority Badge -->
                                            @if ($newsItem->priority === 'high')
                                                <div class="absolute top-2 right-2">
                                                    <span class="nimr-badge-error">
                                                        <div
                                                            class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1 animate-pulse">
                                                        </div>
                                                        High Priority
                                                    </span>
                                                </div>
                                            @endif

                                            <!-- Featured Image -->
                                            @if ($newsItem->featured_image_url)
                                                <div class="w-full h-24 bg-gray-200 rounded-lg mb-3 overflow-hidden">
                                                    <img src="{{ $newsItem && is_object($newsItem) ? $newsItem->featured_image_url : asset('images/default-news.jpg') }}"
                                                        alt="{{ $newsItem && is_object($newsItem) ? $newsItem->title : 'News Article' }}"
                                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                                </div>
                                            @endif

                                            <!-- News Content -->
                                            <div class="space-y-2">
                                                <h4
                                                    class="font-semibold text-gray-900 text-sm leading-tight line-clamp-2 group-hover:text-nimr-accent-700 transition-colors {{ $newsItem && is_object($newsItem) && $newsItem->priority && $newsItem->priority === 'high' ? 'pr-20' : '' }}">
                                                    {{ $newsItem && is_object($newsItem) ? $newsItem->title : 'News Article' }}
                                                </h4>

                                                <!-- Meta Information -->
                                                <div class="flex items-center justify-between text-xs text-gray-500">
                                                    <div class="flex items-center space-x-2">
                                                        <span
                                                            class="font-medium">{{ $newsItem && is_object($newsItem) ? $newsItem->location_display : 'Unknown Location' }}</span>
                                                        @if ($newsItem && is_object($newsItem) && $newsItem->is_recent)
                                                            <span class="nimr-badge-success">
                                                                <div class="w-1 h-1 bg-green-500 rounded-full mr-1"></div>
                                                                New
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span>{{ $newsItem && is_object($newsItem) && $newsItem->published_at ? $newsItem->published_at->diffForHumans() : 'Unknown Date' }}</span>
                                                </div>

                                                <!-- Engagement Stats -->
                                                <div class="flex items-center space-x-4 text-xs text-gray-400">
                                                    <div class="flex items-center space-x-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                        <span>{{ $newsItem && is_object($newsItem) ? $newsItem->views_count : 0 }}</span>
                                                    </div>
                                                    <div class="flex items-center space-x-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                            </path>
                                                        </svg>
                                                        <span>{{ $newsItem->likes_count }}</span>
                                                    </div>
                                                    <span>{{ $newsItem && is_object($newsItem) ? $newsItem->reading_time : '' }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="nimr-card">
                <div class="nimr-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="nimr-icon-secondary">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                </div>
                <div class="nimr-card-body">
                    <div class="space-y-4">
                        @if ($canManageContent)
                            <a href="{{ route('announcements.create') }}" class="nimr-action-card nimr-action-primary">
                                <div class="flex items-center space-x-3">
                                    <div class="nimr-icon-primary group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Create Announcement</p>
                                        <p class="text-sm text-gray-600">Share important updates</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-nimr-primary-600 group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                            <a href="{{ route('news.create') }}" class="nimr-action-card nimr-action-secondary">
                                <div class="flex items-center space-x-3">
                                    <div class="nimr-icon-secondary group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Create News</p>
                                        <p class="text-sm text-gray-600">Share news and activities</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-nimr-secondary-600 group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                            <a href="{{ route('polls.create') }}" class="nimr-action-card nimr-action-accent">
                                <div class="flex items-center space-x-3">
                                    <div class="nimr-icon-accent group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Create Poll</p>
                                        <p class="text-sm text-gray-600">Gather opinions and feedback</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-nimr-accent-600 group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('documents.create') }}" class="nimr-action-card nimr-action-secondary">
                            <div class="flex items-center space-x-3">
                                <div class="nimr-icon-secondary group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Upload Document</p>
                                    <p class="text-sm text-gray-600">Share files and resources</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-nimr-secondary-600 group-hover:translate-x-1 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="nimr-action-card nimr-action-neutral">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Edit Profile</p>
                                    <p class="text-sm text-gray-600">Update your information</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-600 group-hover:translate-x-1 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Active Polls -->
            @if ($activePolls->count())
                <div class="nimr-card">
                    <div class="nimr-card-header">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="nimr-icon-secondary">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Active Polls</h3>
                            </div>
                            <a href="{{ route('polls.index') }}" class="nimr-btn-outline-sm">
                                <span>View all</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="nimr-card-body">
                        <div class="space-y-4">
                            @foreach ($activePolls as $poll)
                                <div
                                    class="group p-4 rounded-xl hover:bg-nimr-secondary-50 transition-colors cursor-pointer border border-transparent hover:border-nimr-secondary-200">
                                    <a href="{{ route('polls.show', $poll) }}" class="block">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-base font-semibold text-gray-900 group-hover:text-nimr-secondary-700 transition-colors mb-2">
                                                    {{ $poll->title }}
                                                </p>
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex items-center space-x-2">
                                                        <div class="w-3 h-3 bg-nimr-secondary-400 rounded-full"></div>
                                                        <span
                                                            class="text-sm font-medium text-nimr-secondary-700">{{ ucfirst($poll->type) }}</span>
                                                    </div>
                                                    <span class="text-gray-400">•</span>
                                                    <span class="text-sm text-gray-600">{{ $poll->responses()->count() }}
                                                        responses</span>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ml-4">
                                                <div
                                                    class="w-8 h-8 bg-nimr-secondary-100 rounded-lg flex items-center justify-center group-hover:bg-nimr-secondary-200 transition-colors">
                                                    <svg class="w-4 h-4 text-nimr-secondary-600 group-hover:translate-x-0.5 transition-transform"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Birthday Modal
        function showBirthdays() {
            // Create modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.id = 'birthday-modal';
            modal.innerHTML = `
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center"> 🎂 Today's Birthdays </h3>
                        <button onclick="closeBirthdayModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="text-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-pink-600 mx-auto"></div>
                        <p class="text-gray-600 mt-2">Loading birthdays...</p>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(modal);

            // Fetch birthdays (simulate for now)
            setTimeout(() => {
                const birthdayList = document.querySelector('#birthday-modal .text-center');
                birthdayList.innerHTML = `
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-pink-50 rounded-lg">
                        <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center text-white font-bold"> J </div>
                        <div class="ml-3">
                            <p class="font-medium">John Mwamba</p>
                            <p class="text-sm text-gray-600">Research Officer</p>
                        </div>
                        <div class="ml-auto text-pink-500">🎉</div>
                    </div>
                    <p class="text-gray-500 text-sm">No other birthdays today</p>
                </div>
            `;
            }, 1000);
        }

        function closeBirthdayModal() {
            const modal = document.getElementById('birthday-modal');
            if (modal) modal.remove();
        }

        // To-Do List
        function openTodoList() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.id = 'todo-modal';
            modal.innerHTML = `
            <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center"> 📋 My To-Do List </h3>
                    <button onclick="closeTodoModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <div class="flex gap-2">
                        <input type="text" id="new-task" placeholder="Add a new task..." class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <button onclick="addTask()" class="px-4 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600">Add</button>
                    </div>
                </div>
                <div id="task-list" class="space-y-2">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" class="mr-3">
                        <span class="flex-1">Review quarterly reports</span>
                        <button class="text-red-500 hover:text-red-700" onclick="removeTask(this)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" class="mr-3" checked>
                        <span class="flex-1 line-through text-gray-500">Update staff directory</span>
                        <button class="text-red-500 hover:text-red-700" onclick="removeTask(this)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(modal);
            updateTaskCounts();
        }

        function closeTodoModal() {
            const modal = document.getElementById('todo-modal');
            if (modal) modal.remove();
        }

        function addTask() {
            const input = document.getElementById('new-task');
            const taskText = input.value.trim();
            if (!taskText) return;

            const taskList = document.getElementById('task-list');
            const taskDiv = document.createElement('div');
            taskDiv.className = 'flex items-center p-3 bg-gray-50 rounded-lg';
            taskDiv.innerHTML = `
            <input type="checkbox" class="mr-3" onchange="updateTaskCounts()">
            <span class="flex-1">${taskText}</span>
            <button class="text-red-500 hover:text-red-700" onclick="removeTask(this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        `;
            taskList.appendChild(taskDiv);
            input.value = '';
            updateTaskCounts();
        }

        function removeTask(button) {
            button.parentElement.remove();
            updateTaskCounts();
        }

        function updateTaskCounts() {
            const tasks = document.querySelectorAll('#task-list .flex');
            const completedTasks = document.querySelectorAll('#task-list input[type="checkbox"]:checked');
            const totalElement = document.getElementById('total-tasks');
            const pendingElement = document.getElementById('pending-tasks');

            if (totalElement) totalElement.textContent = tasks.length;
            if (pendingElement) pendingElement.textContent = tasks.length - completedTasks.length;
        }

        // Password Vault
        function openPasswordVault() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.id = 'password-modal';
            modal.innerHTML = `
            <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center"> 🔐 Password Vault </h3>
                    <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800">Security Notice</h4>
                            <p class="text-sm text-yellow-700">Your passwords are encrypted and stored securely. Only you can access them.</p>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <button onclick="showAddPasswordForm()" class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600">Add New Password</button>
                </div>
                <div id="password-list" class="space-y-3">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium">Email Account</h4>
                            <p class="text-sm text-gray-600">john.doe@nimr.or.tz</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="copyPassword('email123')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Copy</button>
                            <button onclick="showPassword(this)" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Show</button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium">HRIS System</h4>
                            <p class="text-sm text-gray-600">Internal HR Portal</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="copyPassword('hris456')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Copy</button>
                            <button onclick="showPassword(this)" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Show</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(modal);
            updatePasswordCount();
        }

        function closePasswordModal() {
            const modal = document.getElementById('password-modal');
            if (modal) modal.remove();
        }

        function copyPassword(password) {
            navigator.clipboard.writeText(password).then(() => {
                showToast('Password copied to clipboard!', 'success');
            });
        }

        function updatePasswordCount() {
            const passwords = document.querySelectorAll('#password-list > div');
            const countElement = document.getElementById('saved-passwords');
            if (countElement) countElement.textContent = passwords.length;
        }

        // Staff Directory
        function openStaffDirectory() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.id = 'staff-modal';
            modal.innerHTML = `
            <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center"> 📞 Staff Directory </h3>
                    <button onclick="closeStaffModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <input type="text" placeholder="Search for colleagues..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-cyan-500 rounded-full flex items-center justify-center text-white font-bold"> A </div>
                            <div class="ml-3">
                                <h4 class="font-medium">Dr. Alice Mwamba</h4>
                                <p class="text-sm text-gray-600">Research Director</p>
                            </div>
                        </div>
                        <div class="space-y-1 text-sm">
                            <p><span class="font-medium">Email:</span> alice.mwamba@nimr.or.tz</p>
                            <p><span class="font-medium">Phone:</span> +255 123 456 789</p>
                            <p><span class="font-medium">Office:</span> Mwanza Centre</p>
                        </div>
                    </div>
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-cyan-500 rounded-full flex items-center justify-center text-white font-bold"> B </div>
                            <div class="ml-3">
                                <h4 class="font-medium">Bob Kilimo</h4>
                                <p class="text-sm text-gray-600">IT Officer</p>
                            </div>
                        </div>
                        <div class="space-y-1 text-sm">
                            <p><span class="font-medium">Email:</span> bob.kilimo@nimr.or.tz</p>
                            <p><span class="font-medium">Phone:</span> +255 987 654 321</p>
                            <p><span class="font-medium">Office:</span> Headquarters</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(modal);
        }

        function closeStaffModal() {
            const modal = document.getElementById('staff-modal');
            if (modal) modal.remove();
        }

        // Help Center
        function openHelpCenter() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.id = 'help-modal';
            modal.innerHTML = `
            <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center"> 🆘 Help & Support </h3>
                    <button onclick="closeHelpModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900">Quick Help</h4>
                        <div class="space-y-2">
                            <div class="p-3 bg-blue-50 rounded-lg cursor-pointer hover:bg-blue-100">
                                <h5 class="font-medium text-blue-900">How to upload documents?</h5>
                                <p class="text-sm text-blue-700">Learn how to share files with your colleagues</p>
                            </div>
                            <div class="p-3 bg-green-50 rounded-lg cursor-pointer hover:bg-green-100">
                                <h5 class="font-medium text-green-900">Create announcements</h5>
                                <p class="text-sm text-green-700">Share important news with your team</p>
                            </div>
                            <div class="p-3 bg-purple-50 rounded-lg cursor-pointer hover:bg-purple-100">
                                <h5 class="font-medium text-purple-900">Password management</h5>
                                <p class="text-sm text-purple-700">Keep your accounts secure</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900">Contact Support</h4>
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <h5 class="font-medium text-gray-900 mb-2">IT Support</h5>
                            <p class="text-sm text-gray-600 mb-2">For technical issues and system problems</p>
                            <div class="space-y-1 text-sm">
                                <p><span class="font-medium">Email:</span> it-support@nimr.or.tz</p>
                                <p><span class="font-medium">Phone:</span> +255 123 000 111</p>
                                <p><span class="font-medium">Hours:</span> Mon-Fri 8AM-5PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(modal);
        }

        function closeHelpModal() {
            const modal = document.getElementById('help-modal');
            if (modal) modal.remove();
        }

        // Toast notifications
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className =
                `fixed top-4 right-4 px-4 py-2 rounded-md shadow-lg z-50 ${ type === 'success' ? 'bg-green-500 text-white' : type === 'error' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white' }`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Initialize task counts on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateTaskCounts();
            updatePasswordCount();
        });

        // Track Quick Access link clicks
        function trackQuickAccessClick(linkId) {
            fetch(`/system-links/${linkId}/click`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            }).catch(error => console.error('Error tracking click:', error));
        }
    </script>
@endpush
