@extends('layouts.dashboard')

@section('title', 'Document Library')
@section('page-title', 'Document Library')
@section('page-subtitle', 'Access and manage organizational documents')

@section('content')
    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Document Library</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        Browse and access organizational documents
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                    <!-- View Toggle -->
                    <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                        <button onclick="setView('grid')" id="grid-view-btn"
                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors view-toggle">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Grid
                        </button>
                        <button onclick="setView('list')" id="list-view-btn"
                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors view-toggle active">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            List
                        </button>
                    </div>

                    <!-- Upload Button -->
                    <a href="{{ route('documents.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Upload Document
                    </a>
                </div>
            </div>

            <!-- Filters Section -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-8">
                <form method="GET" action="{{ route('documents.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Search Documents
                            </label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search by title, description..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category
                            </label>
                            <select id="category" name="category"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All Categories</option>
                                <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>General
                                </option>
                                <option value="policy" {{ request('category') === 'policy' ? 'selected' : '' }}>Policy
                                </option>
                                <option value="research" {{ request('category') === 'research' ? 'selected' : '' }}>Research
                                </option>
                                <option value="administrative"
                                    {{ request('category') === 'administrative' ? 'selected' : '' }}>Administrative</option>
                                <option value="training" {{ request('category') === 'training' ? 'selected' : '' }}>Training
                                </option>
                            </select>
                        </div>

                        <!-- Access Level Filter -->
                        <div>
                            <label for="access_level"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Access Level
                            </label>
                            <select id="access_level" name="access_level"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All Access Levels</option>
                                <option value="public" {{ request('access_level') === 'public' ? 'selected' : '' }}>Public
                                </option>
                                <option value="restricted" {{ request('access_level') === 'restricted' ? 'selected' : '' }}>
                                    Restricted</option>
                                <option value="confidential"
                                    {{ request('access_level') === 'confidential' ? 'selected' : '' }}>Confidential
                                </option>
                            </select>
                        </div>

                        <!-- Tag Filter -->
                        <div>
                            <label for="tag" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tags
                            </label>
                            <input type="text" id="tag" name="tag" value="{{ request('tag') }}"
                                placeholder="Search by tag..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z" />
                            </svg>
                            Apply Filters
                        </button>
                        <a href="{{ route('documents.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Documents Display -->
            @if (isset($documents) && $documents->count() > 0)
                <!-- Grid View -->
                <div id="grid-view" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                        @foreach ($documents as $document)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                                <!-- Document Icon -->
                                <div class="p-6 text-center border-b border-gray-100 dark:border-gray-700">
                                    <div class="flex justify-center mb-3">
                                        <div
                                            class="w-16 h-16 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 truncate"
                                        title="{{ $document->title }}">
                                        {{ $document->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                        {{ $document->description ?: 'No description available' }}
                                    </p>
                                </div>

                                <!-- Document Details -->
                                <div class="p-4 space-y-3">
                                    <!-- Category and Access Level -->
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            {{ ucfirst($document->category ?? 'general') }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            {{ ucfirst($document->access_level ?? 'public') }}
                                        </span>
                                    </div>

                                    <!-- File Info -->
                                    <div
                                        class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <span>{{ $document->file_size ?? 'Unknown size' }}</span>
                                        <span>{{ $document->download_count ?? 0 }} downloads</span>
                                    </div>

                                    <!-- Uploader and Date -->
                                    <div
                                        class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <span>{{ $document->uploader->name ?? 'Unknown' }}</span>
                                        <span>{{ $document->created_at->format('M j, Y') }}</span>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex space-x-2 pt-3">
                                        <a href="{{ route('documents.show', $document) }}"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg text-sm transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('documents.download', $document) }}"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-lg text-sm transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- List View -->
                <div id="list-view" class="space-y-4 mb-8">
                    @foreach ($documents as $document)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center space-x-4">
                                    <!-- Document Icon -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-12 h-12 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Document Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0 pr-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $document->title }}
                                                </h3>
                                                @if ($document->description)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                                        {{ $document->description }}
                                                    </p>
                                                @endif

                                                <!-- Metadata -->
                                                <div
                                                    class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        {{ $document->uploader->name ?? 'Unknown' }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-8 0h8M5 7v13a2 2 0 002 2h10a2 2 0 002-2V7H5z" />
                                                        </svg>
                                                        {{ $document->file_size ?? 'Unknown size' }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        {{ $document->download_count ?? 0 }} downloads
                                                    </span>
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-8 0h8M5 7v13a2 2 0 002 2h10a2 2 0 002-2V7H5z" />
                                                        </svg>
                                                        {{ $document->created_at->format('M j, Y') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Badges and Actions -->
                                            <div class="flex flex-col items-end space-y-3">
                                                <!-- Category and Access Level -->
                                                <div class="flex flex-col items-end space-y-2">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                        {{ ucfirst($document->category ?? 'general') }}
                                                    </span>
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                        {{ ucfirst($document->access_level ?? 'public') }}
                                                    </span>
                                                </div>

                                                <!-- Actions -->
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('documents.show', $document) }}"
                                                        class="inline-flex items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg text-sm transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View
                                                    </a>
                                                    <a href="{{ route('documents.download', $document) }}"
                                                        class="inline-flex items-center px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-lg text-sm transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $documents->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div
                        class="w-24 h-24 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No documents found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        {{ request()->hasAny(['search', 'category', 'access_level', 'tag'])
                            ? 'Try adjusting your filters or search terms.'
                            : 'Get started by uploading your first document.' }}
                    </p>
                    @unless (request()->hasAny(['search', 'category', 'access_level', 'tag']))
                        <a href="{{ route('documents.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Upload Your First Document
                        </a>
                    @endunless
                </div>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            /* View Toggle Styles */
            .view-toggle {
                color: #6b7280;
                background-color: transparent;
            }

            .view-toggle.active {
                color: #1f2937;
                background-color: #ffffff;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }

            .dark .view-toggle {
                color: #9ca3af;
            }

            .dark .view-toggle.active {
                color: #f9fafb;
                background-color: #374151;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // View toggle functionality
            let currentView = localStorage.getItem('documentsView') || 'list';

            // Set initial view
            document.addEventListener('DOMContentLoaded', function() {
                setView(currentView);
            });

            function setView(viewType) {
                const gridView = document.getElementById('grid-view');
                const listView = document.getElementById('list-view');
                const gridBtn = document.getElementById('grid-view-btn');
                const listBtn = document.getElementById('list-view-btn');

                if (!gridView || !listView || !gridBtn || !listBtn) return;

                // Update button states
                gridBtn.classList.remove('active');
                listBtn.classList.remove('active');

                if (viewType === 'list') {
                    // Show list view
                    gridView.classList.add('hidden');
                    listView.classList.remove('hidden');
                    listBtn.classList.add('active');
                    currentView = 'list';
                } else {
                    // Show grid view
                    listView.classList.add('hidden');
                    gridView.classList.remove('hidden');
                    gridBtn.classList.add('active');
                    currentView = 'grid';
                }

                // Save preference
                localStorage.setItem('documentsView', currentView);
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Alt + G for grid view
                if (e.altKey && e.key === 'g') {
                    e.preventDefault();
                    setView('grid');
                }
                // Alt + L for list view
                if (e.altKey && e.key === 'l') {
                    e.preventDefault();
                    setView('list');
                }
            });
        </script>
    @endpush
@endsection
