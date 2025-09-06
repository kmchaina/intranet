@extends('layouts.dashboard')

@section('title', 'System Links')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100 mb-6">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-6">
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
                                <p class="text-gray-600 mt-1">Quick access to all organizational systems</p>
                            </div>
                        </div>
                        <a href="{{ route('system-links.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add New Link
                        </a>
                    </div>
                </div>

                <!-- Simple Search -->
                <div class="px-6 py-4">
                    <form method="GET" action="{{ route('system-links.index') }}" class="flex gap-4">
                        <div class="flex-1">
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
                                class="block px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                                <option value="">All Categories</option>
                                @foreach (\App\Models\SystemLink::getCategories() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ request('category') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors">
                            Search
                        </button>
                        @if (request()->hasAny(['search', 'category']))
                            <a href="{{ route('system-links.index') }}"
                                class="inline-flex items-center px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Quick Access Management for Admins -->
            @if (auth()->user()->isAdmin())
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100 mb-6">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3">
                                        </path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900">Dashboard Quick Access</h2>
                            </div>
                            <span class="text-sm text-gray-600">Toggle which links appear on user dashboards</span>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $dashboardLinks = $links->where('show_on_dashboard', true);
                        @endphp
                        @if ($dashboardLinks->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($dashboardLinks as $link)
                                    <div
                                        class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            @if ($link->icon)
                                                @if (str_starts_with($link->icon, 'fa'))
                                                    <i class="{{ $link->icon }} text-green-600"></i>
                                                @else
                                                    <span>{{ $link->icon }}</span>
                                                @endif
                                            @endif
                                            <span class="text-sm font-medium text-gray-900">{{ $link->title }}</span>
                                        </div>
                                        <form action="{{ route('system-links.update', $link) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="show_on_dashboard" value="0">
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No links are currently shown on the dashboard</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- All Links - Simple Grid -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">All System Links</h2>
                        <span class="text-sm text-gray-600">{{ $links->total() }} links total</span>
                    </div>
                </div>

                @if ($links->count() > 0)
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                            @foreach ($links as $link)
                                @php
                                    $colorClasses = $link->getColorClasses();
                                @endphp
                                <div class="group relative">
                                    <!-- Main Link -->
                                    <a href="{{ $link->url }}" target="{{ $link->opens_new_tab ? '_blank' : '_self' }}"
                                        onclick="trackLinkClick({{ $link->id }})"
                                        class="block p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-green-300 hover:shadow-md transition-all duration-200 hover:-translate-y-1">

                                        <div class="text-center">
                                            <!-- Icon -->
                                            <div
                                                class="w-12 h-12 {{ $colorClasses[0] }} rounded-xl flex items-center justify-center mx-auto mb-3">
                                                @if ($link->icon)
                                                    @if (str_starts_with($link->icon, 'fa'))
                                                        <i class="{{ $link->icon }} {{ $colorClasses[1] }} text-lg"></i>
                                                    @else
                                                        <span class="text-xl">{{ $link->icon }}</span>
                                                    @endif
                                                @else
                                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </div>

                                            <!-- Title -->
                                            <h3 class="text-sm font-medium text-gray-900 line-clamp-2 mb-1">
                                                {{ $link->title }}</h3>

                                            <!-- Category Badge -->
                                            <span
                                                class="inline-block px-2 py-1 {{ $colorClasses[0] }} {{ $colorClasses[1] }} text-xs rounded-full">
                                                {{ \App\Models\SystemLink::getCategories()[$link->category] ?? $link->category }}
                                            </span>

                                            @if ($link->show_on_dashboard)
                                                <div class="mt-2">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        On Dashboard
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </a>

                                    <!-- Admin Actions -->
                                    @if (auth()->user()->isAdmin())
                                        <div
                                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <div class="flex space-x-1">
                                                @if (!$link->show_on_dashboard)
                                                    <form action="{{ route('system-links.update', $link) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="show_on_dashboard" value="1">
                                                        <button type="submit"
                                                            class="p-1 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors"
                                                            title="Add to Dashboard">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('system-links.edit', $link) }}"
                                                    class="p-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors"
                                                    title="Edit">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('system-links.destroy', $link) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Delete this link?')"
                                                        class="p-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors"
                                                        title="Delete">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
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
                        <p class="text-gray-500 mb-6">No links match your search criteria.</p>
                        @if (request()->hasAny(['search', 'category']))
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
            function trackLinkClick(linkId) {
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
@endsection
