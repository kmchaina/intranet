@extends('layouts.dashboard')

@section('title', 'Station Reports')

@section('content')
    <div class="space-y-6">
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Admin', 'href' => '#'],
            ['label' => 'Station Reports'],
        ]" />

        <!-- Premium Header Card -->
        <div class="card-premium overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-8 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">Station Analytics</h1>
                            <p class="text-white/90 mt-1">Performance snapshot for {{ $station->name ?? 'your station' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Station Selector (if applicable) -->
        @if ($availableStations->isNotEmpty())
            <div class="card-premium p-6">
                <form class="max-w-md">
                    <label class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Select Station</label>
                    <select name="station_id" class="input" onchange="this.form.submit()">
                        @foreach ($availableStations as $stationOption)
                            <option value="{{ $stationOption->id }}" @selected($stationOption->id === $selectedStationId)>
                                {{ $stationOption->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif

        <!-- Key Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Staff -->
            <div class="card-premium p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-nimr-neutral-600 mb-1">Total Staff</p>
                <p class="text-3xl font-bold text-nimr-neutral-900">{{ $metrics['staff_total'] }}</p>
            </div>

            <!-- Active Staff (30d) -->
            <div class="card-premium p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-nimr-neutral-600 mb-1">Active (30 days)</p>
                <p class="text-3xl font-bold text-nimr-neutral-900">{{ $metrics['recent_logins'] }}</p>
                <p class="text-xs text-nimr-neutral-500 mt-2">
                    {{ $metrics['staff_total'] > 0 ? number_format(($metrics['recent_logins'] / $metrics['staff_total']) * 100, 1) : 0 }}%
                    engagement
                </p>
            </div>

            <!-- Announcements Created -->
            <div class="card-premium p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-nimr-neutral-600 mb-1">Announcements</p>
                <p class="text-3xl font-bold text-nimr-neutral-900">{{ $metrics['announcements_created'] }}</p>
                <p class="text-xs text-nimr-neutral-500 mt-2">Last 30 days</p>
            </div>

            <!-- Documents Uploaded -->
            <div class="card-premium p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-nimr-neutral-600 mb-1">Documents</p>
                <p class="text-3xl font-bold text-nimr-neutral-900">{{ $metrics['documents_uploaded'] }}</p>
                <p class="text-xs text-nimr-neutral-500 mt-2">Last 30 days</p>
            </div>
        </div>

        <!-- Content Activity & Contributors Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Content Activity -->
            <div class="card-premium p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-nimr-neutral-900">Content Activity</h3>
                        <p class="text-sm text-nimr-neutral-600">Last 30 days</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-nimr-neutral-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                            <span class="text-sm text-nimr-neutral-700">Announcements</span>
                        </div>
                        <span class="text-lg font-bold text-nimr-neutral-900">{{ $metrics['announcements_created'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-nimr-neutral-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm text-nimr-neutral-700">Documents</span>
                        </div>
                        <span class="text-lg font-bold text-nimr-neutral-900">{{ $metrics['documents_uploaded'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-nimr-neutral-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-nimr-neutral-700">Events</span>
                        </div>
                        <span class="text-lg font-bold text-nimr-neutral-900">{{ $metrics['events_scheduled'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Top Contributors -->
            <div class="card-premium p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-nimr-neutral-900">Top Contributors</h3>
                        <p class="text-sm text-nimr-neutral-600">Most active members</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse ($topContributors as $index => $user)
                        <div
                            class="flex items-center gap-3 p-3 bg-gradient-to-r from-nimr-neutral-50 to-yellow-50 rounded-lg">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-nimr-neutral-900">{{ $user->name }}</p>
                                <p class="text-xs text-nimr-neutral-600">{{ $user->content_count }} items</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-nimr-neutral-500 bg-nimr-neutral-50 rounded-lg">
                            <svg class="w-12 h-12 mx-auto text-nimr-neutral-300 mb-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm font-semibold">No activity yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="card-premium p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-nimr-neutral-900">Upcoming Events</h3>
                        <p class="text-sm text-nimr-neutral-600">Next 3 events</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse ($upcomingEvents as $event)
                        <div
                            class="p-4 bg-gradient-to-r from-nimr-neutral-50 to-blue-50 rounded-lg border border-nimr-neutral-200">
                            <div class="flex gap-3">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-lg flex flex-col items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-semibold text-blue-600">
                                        {{ $event->start_datetime->format('M') }}
                                    </span>
                                    <span class="text-lg font-bold text-blue-700">
                                        {{ $event->start_datetime->format('d') }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-nimr-neutral-900 text-sm">{{ $event->title }}</p>
                                    <p class="text-xs text-nimr-neutral-600 mt-1">
                                        {{ $event->start_datetime->format('g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-nimr-neutral-500 bg-nimr-neutral-50 rounded-lg">
                            <svg class="w-12 h-12 mx-auto text-nimr-neutral-300 mb-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm font-semibold">No events scheduled</p>
                        </div>
                    @endforelse
                </div>
                <a href="{{ route('events.create') }}"
                    class="block mt-4 text-center text-sm font-semibold text-nimr-primary-600 hover:text-nimr-primary-700">
                    + Schedule New Event
                </a>
            </div>
        </div>
    </div>
@endsection
