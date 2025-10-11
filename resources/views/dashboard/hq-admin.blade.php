@extends('layouts.dashboard')
@section('title', 'HQ Admin Dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Welcome Header --}}
        <div class="bg-gradient-to-r from-orange-600 via-amber-600 to-yellow-600 rounded-2xl p-8 text-white shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        Welcome, {{ explode(' ', auth()->user()->name)[0] }}! 🏢
                    </h1>
                    <p class="text-orange-100 text-lg">Headquarters Administrator</p>
                    <p class="text-orange-200 text-sm mt-1">{{ now()->format('l, F j, Y • g:i A') }}</p>
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

        {{-- HQ Stats --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Headquarters Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium uppercase">HQ Staff</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['hqUsers'] ?? 0 }}</p>
                            <p class="text-orange-200 text-sm mt-1">{{ $adminStats['activeHqUsers'] ?? 0 }} active</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium uppercase">My Announcements</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['myAnnouncements'] ?? 0 }}</p>
                            <p class="text-amber-200 text-sm mt-1">{{ $adminStats['publishedContent'] ?? 0 }} published</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium uppercase">My Documents</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['myDocuments'] ?? 0 }}</p>
                            <p class="text-yellow-200 text-sm mt-1">{{ $adminStats['myPolicies'] ?? 0 }} policies</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium uppercase">My Content</p>
                            <p class="text-4xl font-bold mt-2">
                                {{ ($adminStats['myNews'] ?? 0) + ($adminStats['myEvents'] ?? 0) }}
                            </p>
                            <p class="text-blue-200 text-sm mt-1">News & Events</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.hq.users.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-orange-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-orange-100 group-hover:bg-orange-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">Manage HQ Staff</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $adminStats['hqUsers'] ?? 0 }} staff members</p>
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

                <a href="{{ route('admin.policies.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-purple-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-purple-100 group-hover:bg-purple-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Manage Policies
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ $adminStats['myPolicies'] ?? 0 }} policies</p>
                </a>

                <a href="{{ route('admin.news.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-pink-300 hover:shadow-lg transition-all group">
                    <div
                        class="w-12 h-12 rounded-lg bg-pink-100 group-hover:bg-pink-200 flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 group-hover:text-pink-600 transition-colors">My News</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $adminStats['myNews'] ?? 0 }} articles</p>
                </a>
            </div>
        </div>

        {{-- Content Performance --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">My Content Performance</h2>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Published Content Chart --}}
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Content Overview</h3>
                        <p class="text-sm text-gray-500 mt-1">All content you've created</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <p class="text-3xl font-bold text-blue-600">{{ $adminStats['myAnnouncements'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600 mt-1">Announcements</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <p class="text-3xl font-bold text-purple-600">{{ $adminStats['myNews'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600 mt-1">News Articles</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-3xl font-bold text-green-600">{{ $adminStats['myEvents'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600 mt-1">Events</p>
                            </div>
                            <div class="text-center p-4 bg-indigo-50 rounded-lg">
                                <p class="text-3xl font-bold text-indigo-600">{{ $adminStats['myDocuments'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600 mt-1">Documents</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Total Content Created</span>
                                <span class="text-2xl font-bold text-orange-600">
                                    {{ ($adminStats['myAnnouncements'] ?? 0) + ($adminStats['myNews'] ?? 0) + ($adminStats['myEvents'] ?? 0) + ($adminStats['myDocuments'] ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Announcements (30d)</span>
                            <span
                                class="text-lg font-bold text-blue-600">{{ $recentStats['announcements_this_month'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Documents (7d)</span>
                            <span
                                class="text-lg font-bold text-indigo-600">{{ $recentStats['documents_this_week'] ?? 0 }}</span>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500">System-wide metrics</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Management Grid --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Management Tools</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.announcements.index') }}"
                    class="bg-white rounded-xl p-6 border border-gray-200 hover:border-orange-300 hover:shadow-lg transition-all group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 text-lg">Manage Announcements</p>
                    <p class="text-sm text-gray-500 mt-1">View and manage your announcements</p>
                </a>

                <a href="{{ route('admin.news.index') }}"
                    class="bg-white rounded-xl p-6 border border-gray-200 hover:border-orange-300 hover:shadow-lg transition-all group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 text-lg">Manage News</p>
                    <p class="text-sm text-gray-500 mt-1">View and manage your news articles</p>
                </a>

                <a href="{{ route('admin.policies.index') }}"
                    class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-orange-300 hover:shadow-lg transition-all group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 text-lg">Manage Policies</p>
                    <p class="text-sm text-gray-500 mt-1">Official policies & SOPs</p>
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
                        <p class="font-semibold text-gray-900">Need to see the staff view?</p>
                        <p class="text-sm text-gray-600">Switch to staff mode to see what regular users see</p>
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
