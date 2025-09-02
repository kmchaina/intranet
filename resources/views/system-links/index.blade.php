@extends('layouts.dashboard')

@section('title', 'Links')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🔗 Links</h1>
                <p class="text-gray-600 mt-1">Quick access to all organizational systems and external tools</p>
            </div>
            <div class="flex space-x-3">
                <!-- View Toggle -->
                <div class="flex border border-gray-300 rounded-md">
                    <button type="button" onclick="setView('grid')" id="grid-view-btn"
                        class="px-3 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-l-md border-r border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    <button type="button" onclick="setView('list')" id="list-view-btn"
                        class="px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <a href="{{ route('system-links.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Link
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-4">
                <form method="GET" action="{{ route('system-links.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[250px]">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search links..." 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="min-w-[150px]">
                        <select name="category" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\SystemLink::getCategories() as $key => $label)
                                <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[150px]">
                        <select name="access_level" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Access Levels</option>
                            @foreach(\App\Models\SystemLink::getAccessLevels() as $key => $label)
                                <option value="{{ $key }}" {{ request('access_level') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>
                        @if(request()->hasAny(['search', 'category', 'access_level']))
                            <a href="{{ route('system-links.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Featured Links -->
        @if($featured->count() > 0)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">⭐ Featured Links</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($featured as $link)
                        @include('system-links.partials.link-card', ['link' => $link, 'featured' => true])
                    @endforeach
                </div>
            </div>
        @endif

        <!-- All Links -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">All Links</h2>
                <div class="text-sm text-gray-500">
                    {{ $links->total() }} {{ Str::plural('link', $links->total()) }} found
                </div>
            </div>

            @if($links->count() > 0)
                <!-- Grid View (hidden by default) -->
                <div id="grid-view" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($links as $link)
                            @include('system-links.partials.link-card', ['link' => $link, 'featured' => false])
                        @endforeach
                    </div>
                </div>

                <!-- List View (visible by default) -->
                <div id="list-view" class="">
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Link
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Access
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Clicks
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Added
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($links as $link)
                                    @include('system-links.partials.link-row', ['link' => $link])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $links->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No links found</h3>
                    <p class="text-gray-500 mb-4">No links match your current search criteria.</p>
                    @if(request()->hasAny(['search', 'category', 'access_level']))
                        <a href="{{ route('system-links.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Clear filters
                        </a>
                    @else
                        <a href="{{ route('system-links.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
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
        gridBtn.classList.add('bg-blue-600', 'text-white');
        gridBtn.classList.remove('bg-white', 'text-gray-700');
        listBtn.classList.add('bg-white', 'text-gray-700');
        listBtn.classList.remove('bg-blue-600', 'text-white');
        localStorage.setItem('linksView', 'grid');
    } else {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        listBtn.classList.add('bg-blue-600', 'text-white');
        listBtn.classList.remove('bg-white', 'text-gray-700');
        gridBtn.classList.add('bg-white', 'text-gray-700');
        gridBtn.classList.remove('bg-blue-600', 'text-white');
        localStorage.setItem('linksView', 'list');
    }
}

// Load saved view preference on page load (defaults to list view)
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('linksView') || 'list';
    setView(savedView);
});

// Reuse dropdown and link click functions
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

function handleLinkClick(event, url, linkId, opensNewTab) {
    // Don't trigger if clicking on dropdown or form elements
    if (event.target.closest('[onclick*="toggleDropdown"]') || 
        event.target.closest('form') || 
        event.target.closest('button')) {
        return;
    }
    
    event.preventDefault();
    
    // Increment click count
    fetch(`/system-links/${linkId}/increment-click`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).catch(error => console.error('Error incrementing click count:', error));
    
    // Open the link
    if (opensNewTab) {
        window.open(url, '_blank');
    } else {
        window.location.href = url;
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function() {
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    allDropdowns.forEach(d => d.classList.add('hidden'));
});
</script>
@endpush

@endsection
