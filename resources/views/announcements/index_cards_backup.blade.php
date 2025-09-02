@extends('layouts.dashboard')

@section('title', 'Announcements')
@section('page-title', 'Announcements')
@section('page-subtitle', 'Stay updated with the latest news from NIMR')

@section('content')
    <div class="p-6">
        <!-- Header Section -->
        <div class="bg-white/90 backdrop-blur-md rounded-xl border border-gray-200 shadow-lg p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Announcements</h1>
                    <p class="text-gray-600">Stay updated with the latest news from NIMR</p>
                </div>

                @if (auth()->user()->canCreateAnnouncements())
                    <div class="mt-4 lg:mt-0">
                        <a href="{{ route('announcements.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Announcement
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white/90 backdrop-blur-md rounded-xl border border-gray-200 shadow-lg p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="searchInput" placeholder="Search announcements..."
                            class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 placeholder-gray-500 shadow-sm">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="lg:w-48">
                    <select id="categoryFilter"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 shadow-sm">
                        <option value="">All Categories</option>
                        <option value="general">General</option>
                        <option value="urgent">Urgent</option>
                        <option value="event">Event</option>
                        <option value="policy">Policy</option>
                        <option value="training">Training</option>
                    </select>
                </div>

                <!-- Priority Filter -->
                <div class="lg:w-48">
                    <select id="priorityFilter"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 shadow-sm">
                        <option value="">All Priorities</option>
                        <option value="high">High Priority</option>
                        <option value="medium">Medium Priority</option>
                        <option value="low">Low Priority</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Announcements List -->
        <div class="bg-white/95 backdrop-blur-md rounded-xl border border-gray-200 shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Latest Announcements</h2>
                <p class="text-gray-600 text-sm mt-1">{{ $announcements->total() }} announcement(s) found</p>
            </div>
            <div id="announcementsGrid" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($announcements as $announcement)
                    <div class="announcement-card" data-title="{{ strtolower($announcement->title) }}"
                        data-category="{{ $announcement->category }}" data-priority="{{ $announcement->priority }}">
                        <div
                            class="bg-white/95 backdrop-blur-sm rounded-xl border border-gray-200 shadow-lg p-6 h-full flex flex-col hover:bg-white transition-all duration-200 hover:shadow-xl">
                            <!-- Header with Priority Badge -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <!-- Priority Icon -->
                                    <div
                                        class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg
                                    @if ($announcement->priority === 'high') icon-gradient-red
                                    @elseif($announcement->priority === 'medium') icon-gradient-yellow  
                                    @else icon-gradient-green @endif">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            @if ($announcement->priority === 'high')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            @elseif($announcement->priority === 'medium')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            @endif
                                        </svg>
                                    </div>

                                    <!-- Read/Unread Indicator -->
                                    @if (!$announcement->isReadBy(auth()->user()))
                                        <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                                    @endif
                                </div>

                                <!-- Category Badge -->
                                <span
                                    class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                @if ($announcement->category === 'urgent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                @elseif($announcement->category === 'event') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                @elseif($announcement->category === 'policy') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                @elseif($announcement->category === 'training') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                                    {{ ucfirst($announcement->category) }}
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">
                                    {{ $announcement->title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4">
                                    {{ Str::limit(strip_tags($announcement->content), 150) }}
                                </p>
                            </div>

                            <!-- Footer -->
                            <div
                                class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-medium">{{ $announcement->creator->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $announcement->published_at->format('M j, Y') }}</span>
                                </div>
                                <a href="{{ route('announcements.show', $announcement) }}"
                                    class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium text-sm transition-colors">
                                    Read More
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div
                            class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No announcements found</h3>
                        <p class="text-gray-600 dark:text-gray-400">There are no announcements available at the moment.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($announcements->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const categoryFilter = document.getElementById('categoryFilter');
                const priorityFilter = document.getElementById('priorityFilter');
                const announcementsGrid = document.getElementById('announcementsGrid');
                const announcements = document.querySelectorAll('.announcement-card');

                function filterAnnouncements() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const selectedCategory = categoryFilter.value;
                    const selectedPriority = priorityFilter.value;

                    announcements.forEach(announcement => {
                        const title = announcement.dataset.title;
                        const category = announcement.dataset.category;
                        const priority = announcement.dataset.priority;

                        const matchesSearch = title.includes(searchTerm);
                        const matchesCategory = !selectedCategory || category === selectedCategory;
                        const matchesPriority = !selectedPriority || priority === selectedPriority;

                        if (matchesSearch && matchesCategory && matchesPriority) {
                            announcement.style.display = 'block';
                        } else {
                            announcement.style.display = 'none';
                        }
                    });
                }

                searchInput.addEventListener('input', filterAnnouncements);
                categoryFilter.addEventListener('change', filterAnnouncements);
                priorityFilter.addEventListener('change', filterAnnouncements);
            });
        </script>
    @endpush
@endsection
