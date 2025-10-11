@extends('layouts.dashboard')
@section('title', 'Centre Admin Dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Welcome Header --}}
        <div class="bg-gradient-to-r from-yellow-600 via-amber-600 to-orange-600 rounded-2xl p-8 text-white shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        Welcome, {{ explode(' ', auth()->user()->name)[0] }}! 🏛️
                    </h1>
                    <p class="text-yellow-100 text-lg">Centre Administrator</p>
                    <p class="text-yellow-200 text-sm mt-1">
                        {{ auth()->user()->centre->name ?? 'Your Centre' }} • {{ now()->format('l, F j, Y') }}
                    </p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Centre Stats --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Centre Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium uppercase">Centre Staff</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['centreUsers'] ?? 0 }}</p>
                            <p class="text-yellow-200 text-sm mt-1">{{ $adminStats['activeCentreUsers'] ?? 0 }} active</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium uppercase">Stations</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['centreStations'] ?? 0 }}</p>
                            <p class="text-amber-200 text-sm mt-1">{{ $adminStats['stationAdmins'] ?? 0 }} admins</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium uppercase">My Announcements</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['myAnnouncements'] ?? 0 }}</p>
                            <p class="text-green-200 text-sm mt-1">Content posted</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium uppercase">My Content</p>
                            <p class="text-4xl font-bold mt-2">
                                {{ ($adminStats['myNews'] ?? 0) + ($adminStats['myDocuments'] ?? 0) }}
                            </p>
                            <p class="text-blue-200 text-sm mt-1">News & Documents</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Station Performance Comparison --}}
        @if (isset($stationStats) && $stationStats->count() > 0)
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-4">Station Performance</h2>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-amber-50">
                        <h3 class="text-lg font-semibold text-gray-900">Stations in Your Centre</h3>
                        <p class="text-sm text-gray-600 mt-1">Compare staff allocation across stations</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($stationStats as $station)
                                <div
                                    class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-yellow-300 hover:shadow-md transition-all">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-2xl font-bold text-gray-900">{{ $station['users_count'] ?? 0 }}</span>
                                    </div>
                                    <p class="font-semibold text-gray-900">{{ $station['name'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Staff members</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Quick Actions --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.centre.users.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-yellow-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-yellow-100 group-hover:bg-yellow-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-yellow-600 transition-colors">Manage Staff</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $adminStats['centreUsers'] ?? 0 }} in centre</p>
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
                    <p class="text-xs text-gray-500 mt-1">{{ $adminStats['myAnnouncements'] ?? 0 }} created</p>
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
                    <p class="text-xs text-gray-500 mt-1">{{ $adminStats['myNews'] ?? 0 }} articles</p>
                </a>

                <a href="{{ route('admin.documents.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-indigo-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-indigo-100 group-hover:bg-indigo-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">My Documents</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $adminStats['myDocuments'] ?? 0 }} files</p>
                </a>
            </div>
        </div>

        {{-- Management Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Content Management --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        My Content Summary
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.announcements.index') }}"
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-yellow-50 transition-colors group">
                        <span class="text-sm text-gray-700 group-hover:text-yellow-700">Announcements</span>
                        <span class="text-lg font-bold text-blue-600">{{ $adminStats['myAnnouncements'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.news.index') }}"
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-yellow-50 transition-colors group">
                        <span class="text-sm text-gray-700 group-hover:text-yellow-700">News Articles</span>
                        <span class="text-lg font-bold text-purple-600">{{ $adminStats['myNews'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.events.index') }}"
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-yellow-50 transition-colors group">
                        <span class="text-sm text-gray-700 group-hover:text-yellow-700">Events</span>
                        <span class="text-lg font-bold text-green-600">{{ $adminStats['myEvents'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.documents.index') }}"
                        class="flex items-center justify-between p-3 rounded-lg hover:bg-yellow-50 transition-colors group">
                        <span class="text-sm text-gray-700 group-hover:text-yellow-700">Documents</span>
                        <span class="text-lg font-bold text-indigo-600">{{ $adminStats['myDocuments'] ?? 0 }}</span>
                    </a>
                </div>
            </div>

            {{-- Management Links --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Management Tools
                    </h3>
                </div>
                <div class="p-6 space-y-2">
                    <a href="{{ route('admin.centre.users.index') }}"
                        class="block p-4 rounded-lg border border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 transition-all group">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900 group-hover:text-yellow-700">Centre Staff</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('admin.station.reports.index') }}"
                        class="block p-4 rounded-lg border border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 transition-all group">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900 group-hover:text-yellow-700">Station Reports</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                </div>
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
                    class="px-6 py-2.5 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white font-medium rounded-lg transition-all shadow-md">
                    Switch to Staff View
                </a>
            </div>
        </div>
    </div>
@endsection
