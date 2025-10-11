@extends('layouts.dashboard')
@section('title', 'Manage Announcements')

@section('content')
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Manage Announcements
                    </span>
                </h1>
                <p class="text-gray-600 mt-1">View, edit, and manage all system announcements</p>
            </div>
            <a href="{{ route('announcements.create') }}"
                class="btn btn-primary shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                </svg>
                Create New Announcement
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium uppercase">Total</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium uppercase">Published</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['published'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium uppercase">Expired</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['expired'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium uppercase">High Priority</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['high_priority'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Filters & Search --}}
        <div class="card-premium p-6">
            <form method="GET" action="{{ route('admin.announcements.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-semibold text-gray-900 mb-2">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search by title or content..." class="input">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                        <select id="status" name="status" class="select">
                            <option value="">All Status</option>
                            <option value="published" @selected(request('status') === 'published')>Published</option>
                            <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                            <option value="scheduled" @selected(request('status') === 'scheduled')>Scheduled</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        <label for="sort_by" class="text-sm font-medium text-gray-700">Sort by:</label>
                        <select name="sort_by" id="sort_by" class="select text-sm">
                            <option value="created_at" @selected(request('sort_by', 'created_at') === 'created_at')>Created Date</option>
                            <option value="published_at" @selected(request('sort_by') === 'published_at')>Published Date</option>
                            <option value="title" @selected(request('sort_by') === 'title')>Title</option>
                            <option value="views_count" @selected(request('sort_by') === 'views_count')>Views</option>
                        </select>

                        <select name="sort_order" class="select text-sm">
                            <option value="desc" @selected(request('sort_order', 'desc') === 'desc')>Descending</option>
                            <option value="asc" @selected(request('sort_order') === 'asc')>Ascending</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline btn-sm">Reset</a>
                        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Announcements Table --}}
        @if ($announcements->isNotEmpty())
            <div class="card-premium overflow-hidden">
                <form id="bulkActionForm" method="POST" action="{{ route('admin.announcements.bulk-delete') }}">
                    @csrf
                    @method('DELETE')

                    {{-- Bulk Actions Bar --}}
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="selectAll" class="checkbox"
                                onclick="document.querySelectorAll('.announcement-checkbox').forEach(cb => cb.checked = this.checked)">
                            <label for="selectAll" class="text-sm font-medium text-gray-700">Select All</label>
                        </div>

                        <button type="button"
                            onclick="showConfirmModal({
                                type: 'danger',
                                title: 'Delete Announcements',
                                message: 'Are you sure you want to delete the selected announcements? This action cannot be undone.',
                                confirmText: 'Delete',
                                onConfirm: () => document.getElementById('bulkActionForm').submit()
                            })"
                            class="btn btn-sm bg-red-600 hover:bg-red-700 text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Selected
                        </button>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase w-12"></th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Priority</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Category</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Target</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Created By
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Reads</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Created</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($announcements as $announcement)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <input type="checkbox" name="announcement_ids[]"
                                                value="{{ $announcement->id }}" class="announcement-checkbox checkbox">
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex items-start gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-gray-900 truncate">
                                                        {{ $announcement->title }}</p>
                                                    <p class="text-sm text-gray-500 line-clamp-1">
                                                        {{ Str::limit($announcement->content, 80) }}
                                                    </p>
                                                </div>
                                                @if ($announcement->attachments_count > 0)
                                                    <span
                                                        class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                        </svg>
                                                        {{ $announcement->attachments_count }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            @if ($announcement->is_published && (!$announcement->expires_at || $announcement->expires_at->isFuture()))
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                    Published
                                                </span>
                                            @elseif($announcement->expires_at && $announcement->expires_at->isPast())
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                                    Expired
                                                </span>
                                            @elseif($announcement->published_at->isFuture())
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                                    Scheduled
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold uppercase
                                                {{ $announcement->priority === 'high'
                                                    ? 'bg-red-100 text-red-800'
                                                    : ($announcement->priority === 'medium'
                                                        ? 'bg-blue-100 text-blue-800'
                                                        : 'bg-gray-100 text-gray-800') }}">
                                                {{ $announcement->priority }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4">
                                            <span
                                                class="text-sm text-gray-600 capitalize">{{ $announcement->category }}</span>
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="text-sm text-gray-600 capitalize">
                                                {{ match ($announcement->target_scope) {
                                                    'all' => 'All Staff',
                                                    'my_centre' => 'Centre',
                                                    'my_station' => 'Station',
                                                    'my_centre_stations' => 'Centre + Stations',
                                                    'headquarters' => 'HQ',
                                                    'all_centres' => 'All Centres',
                                                    'all_stations' => 'All Stations',
                                                    'specific' => 'Specific',
                                                    default => $announcement->target_scope,
                                                } }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="text-sm text-gray-600">{{ $announcement->creator->name }}</span>
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                {{ $announcement->readers_count }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $announcement->created_at->format('M j, Y') }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('announcements.show', $announcement) }}"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="View">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <form
                                                    action="{{ route('admin.announcements.toggle-publish', $announcement) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="p-2 {{ $announcement->is_published ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg transition-colors"
                                                        title="{{ $announcement->is_published ? 'Unpublish' : 'Publish' }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            @if ($announcement->is_published)
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                            @else
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            @endif
                                                        </svg>
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.announcements.destroy', $announcement) }}"
                                                    method="POST" id="deleteForm{{ $announcement->id }}"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        onclick="showConfirmModal({
                                                            type: 'danger',
                                                            title: 'Delete Announcement',
                                                            message: 'Are you sure you want to delete this announcement? This action cannot be undone.',
                                                            confirmText: 'Delete',
                                                            onConfirm: () => document.getElementById('deleteForm{{ $announcement->id }}').submit()
                                                        })"
                                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                        title="Delete">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            {{-- Pagination --}}
            @if ($announcements->hasPages())
                <div class="card-premium p-4">
                    {{ $announcements->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7" />
                </svg>
                <p class="text-gray-600 text-lg">No announcements found</p>
            </div>
        @endif

    </div>

    {{-- Confirmation Modal --}}
    <x-confirm-modal />
@endsection
