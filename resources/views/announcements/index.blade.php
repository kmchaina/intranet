@extends('layouts.dashboard')
@section('title', 'Announcements')

@section('page-title')
    <div class="flex items-center justify-between animate-fade-in">
        <div>
            <h1 class="text-4xl font-black">
                <span class="bg-gradient-to-r from-blue-600 via-blue-700 to-purple-600 bg-clip-text text-transparent">
                    📢 Announcements
                </span>
            </h1>
            <p class="text-gray-600 mt-2 text-lg">Stay ahead with critical updates and important news</p>
        </div>
        <div class="flex items-center gap-4">
            @can('create', App\Models\Announcement::class)
                <a href="{{ route('announcements.create') }}"
                    class="btn btn-primary shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transform hover:scale-105 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                    </svg>
                    New Announcement
                </a>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">

        {{-- Quick Stats Bar --}}
        @php
            $totalCount = $announcements->total();
            $unreadCount = App\Models\Announcement::visibleTo(auth()->user())
                ->whereDoesntHave('readBy', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->count();
            $urgentCount = App\Models\Announcement::visibleTo(auth()->user())
                ->where('priority', 'high')
                ->count();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Total Announcements --}}
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Total Announcements</p>
                        <p class="text-4xl font-black mt-2">{{ $totalCount }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Unread --}}
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium uppercase tracking-wide">Unread</p>
                        <p class="text-4xl font-black mt-2">{{ $unreadCount }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- High Priority --}}
            <div
                class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-xl transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium uppercase tracking-wide">High Priority</p>
                        <p class="text-4xl font-black mt-2">{{ $urgentCount }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="card-premium p-6 shadow-lg">
            <form method="GET" action="{{ route('announcements.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Search --}}
                    <div class="md:col-span-2">
                        <label for="q" class="block text-sm font-bold text-gray-900 mb-2">🔍 Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input id="q" name="q" value="{{ request('q') }}"
                                placeholder="Search by title or content..." class="input pl-10" />
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <label for="sort" class="block text-sm font-bold text-gray-900 mb-2">📊 Sort By</label>
                        <select name="sort" id="sort" class="select">
                            <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest First</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Oldest First</option>
                        </select>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <label class="inline-flex items-center text-sm font-bold text-gray-900 cursor-pointer">
                        <input type="checkbox" name="only_unread" value="1" @checked(request()->boolean('only_unread'))
                            class="checkbox mr-2">
                        Show Unread Only
                    </label>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Announcements Grid --}}
        @if ($announcements->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach ($announcements as $index => $announcement)
                    @php
                        $isUnread = !$announcement->isReadBy(auth()->user());
                        $priorityClasses = match ($announcement->priority) {
                            'high' => 'from-red-500 to-orange-500',
                            'medium' => 'from-blue-500 to-indigo-500',
                            default => 'from-gray-400 to-gray-500',
                        };
                        $iconBg = match ($announcement->priority) {
                            'high' => 'bg-gradient-to-br from-red-500 to-red-600',
                            'medium' => 'bg-gradient-to-br from-blue-500 to-blue-600',
                            default => 'bg-gradient-to-br from-gray-400 to-gray-500',
                        };
                    @endphp

                    <article
                        class="group relative rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2 {{ $isUnread ? 'bg-white ring-4 ring-blue-400 ring-opacity-50' : 'bg-gradient-to-br from-gray-50 to-gray-100 opacity-75 hover:opacity-90' }}">

                        {{-- Priority Accent --}}
                        <div
                            class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r {{ $priorityClasses }} {{ !$isUnread ? 'opacity-50' : '' }}">
                        </div>

                        {{-- Read/Unread Indicator --}}
                        <div class="absolute top-4 right-4 z-10">
                            @if ($isUnread)
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                                    <span class="w-2 h-2 bg-white rounded-full"></span>
                                    NEW
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-400 text-white text-xs font-bold rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    READ
                                </span>
                            @endif
                        </div>

                        <div class="p-6 {{ !$isUnread ? 'opacity-90' : '' }}">
                            {{-- Header --}}
                            <div class="flex items-start gap-4 mb-4">
                                {{-- Icon --}}
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-xl {{ $iconBg }} transform group-hover:scale-110 group-hover:rotate-3 transition-all">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            @if ($announcement->priority === 'high')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            @elseif($announcement->priority === 'medium')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                            @endif
                                        </svg>
                                    </div>
                                </div>

                                {{-- Title & Metadata --}}
                                <div class="flex-1 min-w-0">
                                    <h2
                                        class="text-2xl font-black {{ $isUnread ? 'text-gray-900' : 'text-gray-600' }} group-hover:text-blue-600 transition-colors mb-2 line-clamp-2">
                                        <a
                                            href="{{ route('announcements.show', $announcement) }}">{{ $announcement->title }}</a>
                                    </h2>

                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        {{-- Priority Badge --}}
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $announcement->priority === 'high' ? 'bg-red-100 text-red-800' : ($announcement->priority === 'medium' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            @if ($announcement->priority === 'high')
                                                🔥
                                            @elseif($announcement->priority === 'medium')
                                                ⚡
                                            @else
                                                📌
                                            @endif
                                            {{ $announcement->priority }}
                                        </span>

                                        {{-- Category Badge --}}
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 capitalize">
                                            {{ $announcement->category }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Content Preview --}}
                            <p
                                class="{{ $isUnread ? 'text-gray-700' : 'text-gray-500' }} leading-relaxed mb-4 line-clamp-3">
                                {{ Str::limit(strip_tags($announcement->content), 180) }}
                            </p>

                            {{-- Meta Info --}}
                            <div
                                class="flex flex-wrap items-center gap-4 text-sm {{ $isUnread ? 'text-gray-500' : 'text-gray-400' }} mb-4">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $announcement->published_at ? $announcement->published_at->diffForHumans() : $announcement->created_at->diffForHumans() }}
                                </span>

                                @if ($announcement->attachments_count > 0)
                                    <span class="flex items-center gap-1.5 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        {{ $announcement->attachments_count }}
                                        {{ Str::plural('file', $announcement->attachments_count) }}
                                    </span>
                                @endif

                                @if ($announcement->expires_at)
                                    <span class="flex items-center gap-1.5 text-orange-600 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Expires {{ $announcement->expires_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div
                                class="flex items-center justify-between pt-4 border-t-2 border-gray-100 group-hover:border-blue-100 transition-colors">
                                <a href="{{ route('announcements.show', $announcement) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transform hover:scale-105 transition-all">
                                    Read Full Announcement
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>

                                @if ($isUnread)
                                    <div class="flex items-center gap-2">
                                        <form x-data
                                            @submit.prevent="fetch('{{ route('announcements.mark-read', $announcement) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(()=>location.reload())">
                                            <button type="submit"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="Mark as Read">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16 px-4">
                <div
                    class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No Announcements Found</h3>
                <p class="text-gray-600 mb-8 text-lg">There are no announcements matching your current filters.</p>
                @can('create', App\Models\Announcement::class)
                    <a href="{{ route('announcements.create') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                        </svg>
                        Create Your First Announcement
                    </a>
                @endcan
            </div>
        @endif

        {{-- Pagination --}}
        @if ($announcements->hasPages())
            <div class="card-premium p-6 shadow-lg">
                {{ $announcements->links() }}
            </div>
        @endif

    </div>
@endsection
