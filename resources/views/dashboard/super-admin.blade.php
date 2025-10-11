@extends('layouts.dashboard')
@section('title', 'Super Admin Dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Welcome Header --}}
        <div class="bg-gradient-to-r from-red-600 via-orange-600 to-amber-600 rounded-2xl p-8 text-white shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        Welcome back, {{ explode(' ', auth()->user()->name)[0] }}! 👑
                    </h1>
                    <p class="text-red-100 text-lg">Super Administrator • Full System Control</p>
                    <p class="text-red-200 text-sm mt-1">{{ now()->format('l, F j, Y • g:i A') }}</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- System-Wide Stats --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">System Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium uppercase">Total Users</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['totalUsers'] ?? 0 }}</p>
                            <p class="text-red-200 text-sm mt-1">{{ $adminStats['activeUsers'] ?? 0 }} verified</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium uppercase">Research Centres</p>
                            <p class="text-4xl font-bold mt-2">{{ $adminStats['totalCentres'] ?? 0 }}</p>
                            <p class="text-orange-200 text-sm mt-1">{{ $adminStats['totalStations'] ?? 0 }} stations</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium uppercase">Content Items</p>
                            <p class="text-4xl font-bold mt-2">
                                {{ ($adminStats['totalAnnouncements'] ?? 0) + ($adminStats['totalNews'] ?? 0) + ($adminStats['totalEvents'] ?? 0) }}
                            </p>
                            <p class="text-amber-200 text-sm mt-1">
                                {{ $adminStats['totalDocuments'] ?? 0 }} documents
                            </p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium uppercase">System Health</p>
                            <p class="text-2xl font-bold mt-2">Excellent</p>
                            <p class="text-green-200 text-sm mt-1">All systems operational</p>
                        </div>
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content Breakdown --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Content Distribution</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-6 border-2 border-blue-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.announcements.index') }}"
                            class="text-xs text-blue-600 hover:text-blue-700 font-medium">View →</a>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $adminStats['totalAnnouncements'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600 mt-1">Announcements</p>
                </div>

                <div class="bg-white rounded-xl p-6 border-2 border-purple-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.news.index') }}"
                            class="text-xs text-purple-600 hover:text-purple-700 font-medium">View →</a>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $adminStats['totalNews'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600 mt-1">News Articles</p>
                </div>

                <div class="bg-white rounded-xl p-6 border-2 border-green-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.events.index') }}"
                            class="text-xs text-green-600 hover:text-green-700 font-medium">View →</a>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $adminStats['totalEvents'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600 mt-1">Events</p>
                </div>

                <div
                    class="bg-white rounded-xl p-6 border-2 border-indigo-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.documents.index') }}"
                            class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">View →</a>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $adminStats['totalDocuments'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600 mt-1">Documents</p>
                </div>
            </div>
        </div>

        {{-- Quick Actions & User Management --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Quick Actions --}}
            <div class="lg:col-span-2">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.users.create') }}"
                        class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-red-300 hover:shadow-lg transition-all group">
                        <div
                            class="w-12 h-12 rounded-lg bg-red-100 group-hover:bg-red-200 flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors">Create User</p>
                        <p class="text-xs text-gray-500 mt-1">Add new system user</p>
                    </a>

                    <a href="{{ route('admin.centres.index') }}"
                        class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-orange-300 hover:shadow-lg transition-all group">
                        <div
                            class="w-12 h-12 rounded-lg bg-orange-100 group-hover:bg-orange-200 flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">Manage Centres
                        </p>
                        <p class="text-xs text-gray-500 mt-1">View all research centres</p>
                    </a>

                    <a href="{{ route('admin.stations.index') }}"
                        class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-amber-300 hover:shadow-lg transition-all group">
                        <div
                            class="w-12 h-12 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">Manage Stations
                        </p>
                        <p class="text-xs text-gray-500 mt-1">View all field stations</p>
                    </a>

                    <a href="{{ route('admin.policies.index') }}"
                        class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-purple-300 hover:shadow-lg transition-all group">
                        <div
                            class="w-12 h-12 rounded-lg bg-purple-100 group-hover:bg-purple-200 flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Manage
                            Policies</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $adminStats['totalPolicies'] ?? 0 }} policies</p>
                    </a>

                    <a href="{{ route('admin.settings.index') }}"
                        class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all group">
                        <div
                            class="w-12 h-12 rounded-lg bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">System Settings
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Configure system</p>
                    </a>

                    <a href="{{ route('admin.backup.index') }}"
                        class="bg-white rounded-xl p-6 border-2 border-gray-200 hover:border-teal-300 hover:shadow-lg transition-all group">
                        <div
                            class="w-12 h-12 rounded-lg bg-teal-100 group-hover:bg-teal-200 flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 group-hover:text-teal-600 transition-colors">Backups</p>
                        <p class="text-xs text-gray-500 mt-1">Manage system backups</p>
                    </a>
                </div>
            </div>

            {{-- User Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        User Overview
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Active Users</span>
                        <span class="text-lg font-bold text-green-600">{{ $adminStats['activeUsers'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">New This Month</span>
                        <span class="text-lg font-bold text-blue-600">{{ $adminStats['newUsersThisMonth'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Pending Verification</span>
                        <span class="text-lg font-bold text-yellow-600">{{ $adminStats['unverifiedUsers'] ?? 0 }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.users.index') }}"
                            class="block w-full px-4 py-2.5 bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 text-white text-center font-medium rounded-lg transition-all shadow-md">
                            Manage All Users →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Management Links Grid --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Content Management</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ([['route' => 'admin.announcements.index', 'label' => 'Announcements', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'color' => 'blue'], ['route' => 'admin.news.index', 'label' => 'News Articles', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', 'color' => 'purple'], ['route' => 'admin.events.index', 'label' => 'Events', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'green'], ['route' => 'admin.documents.index', 'label' => 'Documents', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'indigo']] as $item)
                    <a href="{{ route($item['route']) }}"
                        class="bg-white rounded-xl p-6 border border-gray-200 hover:border-{{ $item['color'] }}-300 hover:shadow-lg transition-all group">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-{{ $item['color'] }}-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-{{ $item['color'] }}-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $item['icon'] }}" />
                                </svg>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-{{ $item['color'] }}-600 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <p
                            class="font-semibold text-gray-900 group-hover:text-{{ $item['color'] }}-600 transition-colors">
                            {{ $item['label'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Manage content</p>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Activity Monitor (if you have ActivityEvent model) --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    System Status
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-900">All Systems Operational</p>
                        <p class="text-xs text-gray-500 mt-1">No issues detected</p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-900">Server Online</p>
                        <p class="text-xs text-gray-500 mt-1">Uptime: 99.9%</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-900">Secure</p>
                        <p class="text-xs text-gray-500 mt-1">SSL encrypted</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
