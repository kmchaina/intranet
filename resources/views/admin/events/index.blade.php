@extends('layouts.dashboard')
@section('title', 'Manage Events')

@section('content')
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Manage Events
                    </span>
                </h1>
                <p class="text-gray-600 mt-1">View and manage all events</p>
            </div>
            <a href="{{ route('events.create') }}"
                class="btn btn-primary shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                </svg>
                Create New Event
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium uppercase">Total Events</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium uppercase">Upcoming</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['upcoming'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium uppercase">Ongoing</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['ongoing'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-100 text-sm font-medium uppercase">Past</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['past'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Filters & Search --}}
        <div class="card-premium p-6">
            <form method="GET" action="{{ route('admin.events.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-semibold text-gray-900 mb-2">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search by title, description, or location..." class="input">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                        <select id="status" name="status" class="select">
                            <option value="">All Events</option>
                            <option value="upcoming" @selected(request('status') === 'upcoming')>Upcoming</option>
                            <option value="ongoing" @selected(request('status') === 'ongoing')>Ongoing</option>
                            <option value="past" @selected(request('status') === 'past')>Past</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        <label for="sort_by" class="text-sm font-medium text-gray-700">Sort by:</label>
                        <select name="sort_by" id="sort_by" class="select text-sm">
                            <option value="start_date" @selected(request('sort_by', 'start_date') === 'start_date')>Start Date</option>
                            <option value="created_at" @selected(request('sort_by') === 'created_at')>Created Date</option>
                            <option value="title" @selected(request('sort_by') === 'title')>Title</option>
                        </select>

                        <select name="sort_order" class="select text-sm">
                            <option value="desc" @selected(request('sort_order', 'desc') === 'desc')>Descending</option>
                            <option value="asc" @selected(request('sort_order') === 'asc')>Ascending</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline btn-sm">Reset</a>
                        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Events Table --}}
        @if ($events->isNotEmpty())
            <div class="card-premium overflow-hidden">
                <form id="bulkActionForm" method="POST" action="{{ route('admin.events.bulk-delete') }}">
                    @csrf
                    @method('DELETE')

                    {{-- Bulk Actions Bar --}}
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="selectAll" class="checkbox"
                                onclick="document.querySelectorAll('.event-checkbox').forEach(cb => cb.checked = this.checked)">
                            <label for="selectAll" class="text-sm font-medium text-gray-700">Select All</label>
                        </div>

                        <button type="button"
                            onclick="showConfirmModal({
                                type: 'danger',
                                title: 'Delete Events',
                                message: 'Are you sure you want to delete the selected events? This action cannot be undone.',
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
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Event</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Date & Time
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Location</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Organizer
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">RSVPs</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($events as $event)
                                    @php
                                        $isUpcoming = $event->start_date->isFuture();
                                        $isOngoing = $event->start_date->isPast() && $event->end_date->isFuture();
                                        $isPast = $event->end_date->isPast();
                                    @endphp

                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <input type="checkbox" name="event_ids[]" value="{{ $event->id }}"
                                                class="event-checkbox checkbox">
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-900 truncate">{{ $event->title }}</p>
                                                @if ($event->description)
                                                    <p class="text-sm text-gray-500 line-clamp-1">
                                                        {{ Str::limit(strip_tags($event->description), 80) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            @if ($isUpcoming)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                                    Upcoming
                                                </span>
                                            @elseif($isOngoing)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                    <span
                                                        class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                                    Ongoing
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                                    Past
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $event->start_date->format('M j, Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $event->start_date->format('g:i A') }} -
                                                {{ $event->end_date->format('g:i A') }}
                                            </p>
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $event->location }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $event->organizer->name }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                {{ $event->rsvps_count }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('events.show', $event) }}"
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

                                                <a href="{{ route('events.edit', $event) }}"
                                                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                                    id="deleteForm{{ $event->id }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        onclick="showConfirmModal({
                                                            type: 'danger',
                                                            title: 'Delete Event',
                                                            message: 'Are you sure you want to delete this event? All RSVPs will be lost.',
                                                            confirmText: 'Delete',
                                                            onConfirm: () => document.getElementById('deleteForm{{ $event->id }}').submit()
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
            @if ($events->hasPages())
                <div class="card-premium p-4">
                    {{ $events->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-600 text-lg">No events found</p>
            </div>
        @endif

    </div>

    {{-- Confirmation Modal --}}
    <x-confirm-modal />
@endsection
