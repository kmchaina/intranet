@extends('layouts.dashboard')
@section('title', 'Announcements')

@section('page-title')
    <div class="flex items-center justify-between animate-fade-in">
        <div>
            <h1 class="text-3xl font-bold">
                <span class="bg-gradient-to-r from-nimr-primary-500 to-purple-600 bg-clip-text text-transparent">
                    Announcements
                </span>
            </h1>
            <p class="text-nimr-neutral-600 mt-1">Stay informed with the latest updates</p>
        </div>
        <div class="flex items-center gap-4">
            @can('create', App\Models\Announcement::class)
                <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                    </svg>
                    Create Announcement
                </a>
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">

        {{-- Filter Bar --}}
        <div class="card-premium p-6">
            <form method="GET" action="{{ route('announcements.index') }}"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="lg:col-span-2">
                    <label for="q" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-nimr-neutral-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input id="q" name="q" value="{{ request('q') }}"
                            placeholder="Search announcements..." class="input pl-10" />
                    </div>
                </div>

                {{-- Category --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Category</label>
                    <select id="category" name="category" class="select">
                        <option value="">All Categories</option>
                        <option value="general" @selected(request('category') === 'general')>General</option>
                        <option value="urgent" @selected(request('category') === 'urgent')>Urgent</option>
                        <option value="info" @selected(request('category') === 'info')>Info</option>
                        <option value="event" @selected(request('category') === 'event')>Event</option>
                    </select>
                </div>

                {{-- Priority --}}
                <div>
                    <label for="priority" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Priority</label>
                    <select id="priority" name="priority" class="select">
                        <option value="">All Priorities</option>
                        <option value="low" @selected(request('priority') === 'low')>Low</option>
                        <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
                        <option value="high" @selected(request('priority') === 'high')>High</option>
                        <option value="urgent" @selected(request('priority') === 'urgent')>Urgent</option>
                    </select>
                </div>

                {{-- Actions --}}
                <div class="lg:col-span-4 flex items-center justify-between gap-3">
                    <label class="inline-flex items-center text-sm font-medium text-nimr-neutral-700">
                        <input type="checkbox" name="only_unread" value="1" @checked(request()->boolean('only_unread'))
                            class="checkbox mr-2">
                        Unread Only
                    </label>

                    <div class="flex items-center gap-3">
                        <select name="sort" class="select text-sm">
                            <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest First</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Oldest First</option>
                        </select>
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline btn-sm">Reset</a>
                        <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Announcements List --}}
        <div class="space-y-4">
            @forelse($announcements as $announcement)
                <div class="card-premium overflow-hidden group hover:-translate-y-1 transition-all duration-300">
                    {{-- Priority Border --}}
                    <div
                        class="absolute left-0 top-0 bottom-0 w-1 @if ($announcement->priority === 'urgent') bg-gradient-to-b from-red-500 to-red-600 @elseif($announcement->priority === 'high') bg-gradient-to-b from-orange-500 to-orange-600 @elseif($announcement->priority === 'medium') bg-gradient-to-b from-nimr-primary-500 to-nimr-primary-600 @else bg-gradient-to-b from-nimr-neutral-300 to-nimr-neutral-400 @endif">
                    </div>

                    <div class="p-6 pl-8">
                        <div class="flex items-start gap-4">
                            {{-- Icon --}}
                            <div class="flex-shrink-0">
                                <div
                                    class="w-14 h-14 rounded-xl flex items-center justify-center shadow-md @if ($announcement->priority === 'urgent') bg-gradient-to-br from-red-500 to-red-600 @elseif($announcement->priority === 'high') bg-gradient-to-br from-orange-500 to-orange-600 @elseif($announcement->priority === 'medium') bg-gradient-to-br from-nimr-primary-500 to-nimr-primary-600 @else bg-gradient-to-br from-nimr-neutral-400 to-nimr-neutral-500 @endif">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        @if ($announcement->priority === 'urgent')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        @elseif($announcement->priority === 'high')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        @endif
                                    </svg>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3
                                                class="text-xl font-bold text-nimr-neutral-900 group-hover:text-nimr-primary-600 transition-colors">
                                                <a
                                                    href="{{ route('announcements.show', $announcement) }}">{{ $announcement->title }}</a>
                                            </h3>
                                            @if (!$announcement->isReadBy(auth()->user()))
                                                <span class="badge badge-primary text-xs font-bold">NEW</span>
                                            @endif
                                        </div>

                                        <div
                                            class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-nimr-neutral-600 mb-3">
                                            <span class="flex items-center gap-1 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $announcement->creator->name }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $announcement->published_at ? $announcement->published_at->diffForHumans() : $announcement->created_at->diffForHumans() }}
                                            </span>
                                            @if ($announcement->attachments_count > 0)
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                    </svg>
                                                    {{ $announcement->attachments_count }}
                                                    {{ Str::plural('file', $announcement->attachments_count) }}
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-nimr-neutral-700 leading-relaxed line-clamp-2">
                                            {{ Str::limit(strip_tags($announcement->content), 200) }}
                                        </p>
                                    </div>

                                    {{-- Badges --}}
                                    <div class="flex flex-col gap-2">
                                        <span
                                            class="badge @if ($announcement->priority === 'urgent') badge-urgent @elseif($announcement->priority === 'high') badge-high @elseif($announcement->priority === 'medium') badge-medium @else badge-low @endif text-xs font-bold uppercase">
                                            {{ $announcement->priority }}
                                        </span>
                                        <span class="badge badge-gray text-xs capitalize">
                                            {{ $announcement->category }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center justify-between pt-3 border-t border-nimr-neutral-100">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('announcements.show', $announcement) }}"
                                            class="nimr-link text-sm font-semibold">
                                            Read More →
                                        </a>
                                        @if (auth()->user()->canManageAnnouncement($announcement))
                                            <a href="{{ route('announcements.edit', $announcement) }}"
                                                class="text-sm font-medium text-nimr-neutral-600 hover:text-nimr-primary-600 transition-colors">
                                                Edit
                                            </a>
                                        @endif
                                    </div>

                                    @if (!$announcement->isReadBy(auth()->user()))
                                        <form x-data
                                            @submit.prevent="fetch('{{ route('announcements.mark-read', $announcement) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(()=>location.reload())">
                                            <button type="submit"
                                                class="text-xs font-medium text-nimr-primary-600 hover:text-nimr-primary-700 hover:underline">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <h3 class="empty-state-title">No Announcements Found</h3>
                    <p class="empty-state-description">There are no announcements matching your current filters.</p>
                    @can('create', App\Models\Announcement::class)
                        <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                            </svg>
                            Create First Announcement
                        </a>
                    @endcan
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($announcements->hasPages())
            <div class="card-premium p-4">
                {{ $announcements->links() }}
            </div>
        @endif

    </div>
@endsection
