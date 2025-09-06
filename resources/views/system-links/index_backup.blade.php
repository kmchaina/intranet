@extends('layouts.dashboard')

@section('title', 'System Links')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Modern Header -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100 mb-8">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-8 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">System Links</h1>
                                <p class="text-gray-600 mt-1">Quick access to all organizational systems and external tools
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- View Toggle -->
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                <button type="button" onclick="setView('grid')" id="grid-view-btn"
                                    class="px-3 py-2 text-sm font-medium rounded-md transition-colors focus:outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                </button>
                                <button type="button" onclick="setView('list')" id="list-view-btn"
                                    class="px-3 py-2 text-sm font-medium rounded-md transition-colors focus:outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </button>
                            </div>

                            <a href="{{ route('system-links.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add New Link
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="px-6 py-6">
                    <form method="GET" action="{{ route('system-links.index') }}"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search links..."
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                        <div>
                            <select name="category"
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                                <option value="">All Categories</option>
                                @foreach (\App\Models\SystemLink::getCategories() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ request('category') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Search
                            </button>
                            @if (request()->hasAny(['search', 'category', 'access_level']))
                                <a href="{{ route('system-links.index') }}"
                                    class="inline-flex items-center px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Featured Links -->
            @if ($featured->count() > 0)
                <div class="mb-8">
                    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900">Featured Links</h2>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $featured->count() }} links
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                @foreach ($featured as $link)
                                    @include('system-links.partials.link-card', [
                                        'link' => $link,
                                        'featured' => true,
                                    ])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- All Links -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">All System Links</h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $links->total() }} {{ Str::plural('link', $links->total()) }} found
                            </span>
                        </div>
                    </div>
                </div>

                @if ($links->count() > 0)
                    <div class="p-6">
                        <!-- Grid View (hidden by default) -->
                        <div id="grid-view" class="hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                @foreach ($links as $link)
                                    @include('system-links.partials.link-card', [
                                        'link' => $link,
                                        'featured' => false,
                                    ])
                                @endforeach
                            </div>
                        </div>

                        <!-- List View (visible by default) -->
                        <div id="list-view" class="">
                            <div class="overflow-hidden">
                                <div class="space-y-3">
                                    @foreach ($links as $link)
                                        @include('system-links.partials.link-row', ['link' => $link])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if ($links->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $links->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No links found</h3>
                        <p class="text-gray-500 mb-6">No links match your current search criteria.</p>
                        @if (request()->hasAny(['search', 'category', 'access_level']))
                            <a href="{{ route('system-links.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 transition-colors">
                                Clear filters
                            </a>
                        @else
                            <a href="{{ route('system-links.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors">
                                Add first link
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // View switching functionality
            function setView(viewType) {
                const gridView = document.getElementById('grid-view');
                const listView = document.getElementById('list-view');
                const gridBtn = document.getElementById('grid-view-btn');
                const listBtn = document.getElementById('list-view-btn');

                if (viewType === 'grid') {
                    gridView.classList.remove('hidden');
                    listView.classList.add('hidden');
                    gridBtn.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
                    gridBtn.classList.remove('text-gray-500');
                    listBtn.classList.add('text-gray-500');
                    listBtn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
                    localStorage.setItem('linksView', 'grid');
                } else {
                    gridView.classList.add('hidden');
                    listView.classList.remove('hidden');
                    listBtn.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
                    listBtn.classList.remove('text-gray-500');
                    gridBtn.classList.add('text-gray-500');
                    gridBtn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
                    localStorage.setItem('linksView', 'list');
                }
            }

            // Load saved view preference on page load (defaults to list view)
            document.addEventListener('DOMContentLoaded', function() {
                const savedView = localStorage.getItem('linksView') || 'list';
                setView(savedView);
            });

            // Dropdown and link click functions
            function toggleDropdown(id) {
                event.stopPropagation();
                const dropdown = document.getElementById(id);
                const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');

                // Close all other dropdowns
                allDropdowns.forEach(d => {
                    if (d.id !== id) {
                        d.classList.add('hidden');
                    }
                });

                // Toggle current dropdown
                dropdown.classList.toggle('hidden');
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
                allDropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            });

            // Track link clicks
            function trackLinkClick(linkId) {
                fetch(`/system-links/${linkId}/click`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                });
            }
        </script>
    @endpush
@endsection
