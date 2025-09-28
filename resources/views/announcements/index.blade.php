@extends('layouts.dashboard')
@section('title', 'Announcements')
@section('content')
<div class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-100/40 min-h-screen">
  <div class="max-w-7xl mx-auto p-6">
    <x-breadcrumbs :items="[
        ['label' => 'Dashboard', 'href' => route('dashboard')],
        ['label' => 'Announcements'],
    ]" />
    <x-page.header title="Announcements">
      <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
      </x-slot:icon>
      <x-slot:actions>
        @can('create', App\Models\Announcement::class)
        <a href="{{ route('announcements.create') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/></svg>
          New Announcement
        </a>
        @endcan
      </x-slot:actions>
    </x-page.header>

    <!-- Filter/Search Bar -->
  <form method="GET" action="{{ route('announcements.index') }}" data-auto-submit class="mb-6 p-4 bg-white rounded-xl shadow-sm border border-gray-200 grid grid-cols-1 md:grid-cols-12 gap-3">
      <div class="md:col-span-4">
        <label for="q" class="sr-only">Search</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
          </div>
          <input id="q" name="q" value="{{ request('q') }}" placeholder="Search title or content" class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
        </div>
      </div>
      <div class="md:col-span-3">
        <label for="category" class="sr-only">Category</label>
        <select id="category" name="category" class="w-full bg-white border border-gray-200 rounded-lg text-sm px-3 py-2">
          <option value="">All categories</option>
          @foreach (['general' => 'General', 'urgent' => 'Urgent', 'info' => 'Info', 'event' => 'Event'] as $val => $label)
            <option value="{{ $val }}" @selected(request('category')===$val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="md:col-span-3">
        <label for="priority" class="sr-only">Priority</label>
        <select id="priority" name="priority" class="w-full bg-white border border-gray-200 rounded-lg text-sm px-3 py-2">
          <option value="">All priorities</option>
          @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'] as $val => $label)
            <option value="{{ $val }}" @selected(request('priority')===$val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="md:col-span-2 flex items-center gap-3">
        <label class="inline-flex items-center text-xs text-gray-600">
          <input type="checkbox" name="only_unread" value="1" @checked(request()->boolean('only_unread')) class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
          Unread only
        </label>
        <select name="sort" class="bg-white border border-gray-200 rounded-lg text-sm px-2 py-2">
          <option value="newest" @selected(request('sort','newest')==='newest')>Newest first</option>
          <option value="oldest" @selected(request('sort')==='oldest')>Oldest first</option>
        </select>
      </div>
      <div class="md:col-span-12 flex justify-end gap-2">
        <a href="{{ route('announcements.index') }}" class="px-3 py-2 text-sm rounded-lg border border-gray-200 text-gray-700 bg-white">Reset</a>
        <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">Apply</button>
      </div>
    </form>

    <!-- Announcements List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
      <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold text-gray-900">Latest Announcements</h2>
          <span class="text-sm text-gray-500">{{ $announcements->total() }} announcement(s) found</span>
        </div>
      </div>

      <div id="announcementsList" class="divide-y divide-gray-100">
        @forelse($announcements as $announcement)
        <div class="announcement-item p-6 hover:bg-gray-50 transition-colors duration-200 border-l-4 @if ($announcement->priority === 'urgent') border-l-red-500 bg-red-50/30 @elseif ($announcement->priority === 'high') border-l-orange-500 bg-orange-50/30 @elseif($announcement->priority === 'medium') border-l-blue-500 bg-blue-50/30 @else border-l-gray-300 bg-gray-50/30 @endif">
          <div class="flex items-start gap-4">
            <!-- Priority & Unread -->
            <div class="flex-shrink-0 flex items-center gap-3">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm @if ($announcement->priority === 'urgent') bg-red-500 text-white @elseif($announcement->priority === 'high') bg-orange-500 text-white @elseif($announcement->priority === 'medium') bg-blue-500 text-white @else bg-gray-500 text-white @endif">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  @if ($announcement->priority === 'urgent')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                  @elseif($announcement->priority === 'high')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  @endif
                </svg>
              </div>
              @if (!$announcement->isReadBy(auth()->user()))
                <span class="px-2 py-0.5 text-[10px] rounded-full bg-blue-100 text-blue-700 font-semibold">Unread</span>
              @endif
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between mb-2">
                <div class="flex-1 min-w-0 pr-4">
                  <div class="flex items-center gap-3 mb-1">
                    <h3 class="text-lg font-semibold text-gray-900 hover:text-blue-600">
                      <a href="{{ route('announcements.show', $announcement) }}" class="block">
                        {{ $announcement->title }}
                      </a>
                    </h3>
                    <span class="inline-flex px-2 py-1 text-xs font-bold uppercase tracking-wide rounded-full @if ($announcement->priority === 'urgent') bg-red-500 text-white @elseif($announcement->priority === 'high') bg-orange-500 text-white @elseif($announcement->priority === 'medium') bg-blue-500 text-white @else bg-gray-500 text-white @endif">
                      {{ $announcement->priority }}
                    </span>
                  </div>
                  <div class="flex flex-wrap items-center gap-2 text-xs text-gray-600">
                    <span class="font-medium">{{ $announcement->creator->name }}</span>
                    <span>•</span>
                    <span>{{ optional($announcement->published_at)->format('M j, Y') }}</span>
                    <span>•</span>
                    <span class="capitalize">{{ $announcement->target_scope }}</span>
                    @if ($announcement->attachments_count > 0)
                      <span>•</span>
                      <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                        {{ $announcement->attachments_count }} file(s)
                      </span>
                    @endif
                  </div>
                </div>
                <div class="flex-shrink-0 ml-4">
                  <span class="inline-flex px-3 py-1 text-xs font-medium rounded-lg @if ($announcement->category === 'urgent') bg-red-100 text-red-800 @elseif($announcement->category === 'event') bg-blue-100 text-blue-800 @elseif($announcement->category === 'policy') bg-purple-100 text-purple-800 @elseif($announcement->category === 'training') bg-indigo-100 text-indigo-800 @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($announcement->category) }}
                  </span>
                </div>
              </div>

              <p class="text-gray-700 text-sm leading-relaxed mb-3">{{ Str::limit(strip_tags($announcement->content), 180) }}</p>

              @if ($announcement->attachments_count > 0)
                <div class="flex items-center flex-wrap gap-2 mb-3">
                  @foreach ($announcement->attachments->take(3) as $attachment)
                    <a href="{{ route('announcements.download-attachment', $attachment) }}" class="flex items-center space-x-2 bg-gray-50 hover:bg-gray-100 rounded-lg px-3 py-1 text-xs border border-gray-200">
                      <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                      </svg>
                      <span class="text-gray-700 font-medium">{{ Str::limit($attachment->original_name, 18) }}</span>
                    </a>
                  @endforeach
                  @if ($announcement->attachments_count > 3)
                    <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-lg">+{{ $announcement->attachments_count - 3 }} more</span>
                  @endif
                </div>
              @endif

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <a href="{{ route('announcements.show', $announcement) }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">Read More
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                  </a>
                  @if (auth()->user()->canManageAnnouncement($announcement))
                    <a href="{{ route('announcements.edit', $announcement) }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Edit</a>
                  @endif
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                  <span>{{ optional($announcement->published_at)->diffForHumans() }}</span>
                  @if (!$announcement->isReadBy(auth()->user()))
                    <form x-data @submit.prevent="fetch('{{ route('announcements.mark-read', $announcement) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(()=>location.reload())">
                      <button type="submit" class="text-blue-600 hover:text-blue-700 font-medium">Mark as read</button>
                    </form>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="p-12 text-center">
          <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-xl flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">No announcements found</h3>
          <p class="text-gray-500 mb-6">There are no announcements matching your current filters.</p>
          @if (auth()->user()->canCreateAnnouncements())
            <a href="{{ route('announcements.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Create First Announcement
            </a>
          @endif
        </div>
        @endforelse
      </div>

      <!-- Pagination -->
      @if ($announcements->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $announcements->links() }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
