@extends('layouts.dashboard')
@section('title', 'Station Admin Dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Welcome Header --}}
        <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl p-8 text-white shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        Welcome, {{ explode(' ', auth()->user()->name)[0] }}! 🎯
                    </h1>
                    <p class="text-green-100 text-lg">Station Administrator</p>
                    <p class="text-green-200 text-sm mt-1">
                        {{ auth()->user()->station->name ?? 'Your Station' }} •
                        {{ auth()->user()->centre->name ?? 'Centre' }}
                    </p>
                    <p class="text-green-200 text-xs mt-1">{{ now()->format('l, F j, Y • g:i A') }}</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Station Stats --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Station Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium uppercase">Station Staff</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['stationUsers'] ?? 0 }}</p>
                            <p class="text-green-200 text-sm mt-1">{{ $adminStats['activeStationUsers'] ?? 0 }} active</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium uppercase">My Announcements</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['myAnnouncements'] ?? 0 }}</p>
                            <p class="text-emerald-200 text-sm mt-1">Content posted</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-teal-100 text-sm font-medium uppercase">My News</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['myNews'] ?? 0 }}</p>
                            <p class="text-teal-200 text-sm mt-1">Articles published</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-lime-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-lime-100 text-sm font-medium uppercase">My Content</p>
                            <p class="text-4xl font-bold mt-2">
                                {{ ($adminStats['myEvents'] ?? 0) + ($adminStats['myDocuments'] ?? 0) }}
                            </p>
                            <p class="text-lime-200 text-sm mt-1">Events & Documents</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Staff Management & Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Staff List --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Station Staff
                        </h3>
                        <a href="{{ route('admin.station.users.index') }}"
                            class="text-xs text-green-600 hover:text-green-700 font-medium">View All →</a>
                    </div>
                </div>
                <div class="p-6">
                    @if (isset($stationUsers) && $stationUsers->count() > 0)
                        <div class="space-y-3">
                            @foreach ($stationUsers->take(5) as $staffMember)
                                <div
                                    class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-all">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ substr($staffMember->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $staffMember->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">{{ $staffMember->email }}</p>
                                    </div>
                                    @if ($staffMember->email_verified_at)
                                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-sm">No staff members yet</p>
                            <a href="{{ route('admin.station.users.create') }}"
                                class="inline-block mt-3 text-xs text-green-600 hover:text-green-700 font-medium">
                                Add First Staff Member →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- My Content Summary --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        My Content Summary
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.announcements.index') }}"
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-green-50 transition-colors group">
                        <span class="text-sm text-gray-700 group-hover:text-green-700">Announcements</span>
                        <span class="text-lg font-bold text-blue-600">{{ $adminStats['myAnnouncements'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.news.index') }}"
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-green-50 transition-colors group">
                        <span class="text-sm text-gray-700 group-hover:text-green-700">News Articles</span>
                        <span class="text-lg font-bold text-purple-600">{{ $adminStats['myNews'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.events.index') }}"
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-green-50 transition-colors group">
                        <span class="text-sm text-gray-700 group-hover:text-green-700">Events</span>
                        <span class="text-lg font-bold text-green-600">{{ $adminStats['myEvents'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.documents.index') }}"
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-green-50 transition-colors group">
                        <span class="text-sm text-gray-700 group-hover:text-green-700">Documents</span>
                        <span class="text-lg font-bold text-indigo-600">{{ $adminStats['myDocuments'] ?? 0 }}</span>
                    </a>

                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Total Content</span>
                            <span class="text-2xl font-bold text-green-600">
                                {{ ($adminStats['myAnnouncements'] ?? 0) + ($adminStats['myNews'] ?? 0) + ($adminStats['myEvents'] ?? 0) + ($adminStats['myDocuments'] ?? 0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.station.users.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-green-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-green-100 group-hover:bg-green-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Manage Staff</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $adminStats['stationUsers'] ?? 0 }} staff members</p>
                </a>

                <a href="{{ route('admin.announcements.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">My Announcements</p>
                    <p class="text-xs text-gray-500 mt-1">Manage announcements</p>
                </a>

                <a href="{{ route('admin.news.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-purple-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-purple-100 group-hover:bg-purple-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">My News</p>
                    <p class="text-xs text-gray-500 mt-1">Manage news articles</p>
                </a>

                <a href="{{ route('admin.events.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-emerald-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">My Events</p>
                    <p class="text-xs text-gray-500 mt-1">Manage events</p>
                </a>

                <a href="{{ route('admin.station.reports.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-teal-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-teal-100 group-hover:bg-teal-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-teal-600 transition-colors">Station Reports</p>
                    <p class="text-xs text-gray-500 mt-1">View analytics</p>
                </a>

                <a href="{{ route('announcements.create') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-amber-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">Post Announcement
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Create new announcement</p>
                </a>
            </div>
        </div>

        {{-- Switch to Staff View --}}
        <div class="bg-gradient-to-r from-gray-100 to-gray-200 rounded-xl p-6 border border-gray-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">View as Staff Member?</p>
                        <p class="text-sm text-gray-600">Switch to staff mode to see the user experience</p>
                    </div>
                </div>
                <a href="{{ route('dashboard', ['view' => 'staff']) }}"
                    class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg transition-all shadow-md">
                    Switch to Staff View
                </a>
            </div>
        </div>
    </div>
@endsection
