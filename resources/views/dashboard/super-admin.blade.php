@extends('layouts.dashboard')
@section('title', 'Super Admin Dashboard')

@section('page-title')
    @php
        $primary = $themeColors['primary'] ?? 'amber';
        $accent = $themeColors['accent'] ?? 'yellow';
    @endphp
    <div class="flex items-center justify-between animate-fade-in">
        <div>
            <h1 class="text-3xl font-bold">
                <span
                    class="bg-gradient-to-r from-{{ $primary }}-600 to-{{ $accent }}-600 bg-clip-text text-transparent">
                    System Administration
                </span>
            </h1>
            <p class="text-nimr-neutral-600 mt-1">{{ $userRole }} • Full System Control</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-sm text-nimr-neutral-600 font-medium">{{ now()->format('l, F j, Y') }}</p>
                <p class="text-xs text-nimr-neutral-500">{{ now()->format('g:i A') }}</p>
            </div>
            <div
                class="w-12 h-12 bg-gradient-to-br from-{{ $primary }}-500 to-{{ $accent }}-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-8">

        {{-- System Overview Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Users --}}
            <div class="stat-card">
                <div class="stat-card-icon bg-nimr-primary-100 text-nimr-primary-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $adminStats['totalUsers'] ?? 0 }}</div>
                <div class="stat-card-label">Total Users</div>
                <div class="stat-card-trend text-green-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>{{ $adminStats['activeUsers'] ?? 0 }} active</span>
                </div>
            </div>

            {{-- Total Centres --}}
            <div class="stat-card">
                <div class="stat-card-icon bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $adminStats['totalCentres'] ?? 0 }}</div>
                <div class="stat-card-label">Research Centres</div>
            </div>

            {{-- Total Stations --}}
            <div class="stat-card">
                <div class="stat-card-icon bg-orange-100 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $adminStats['totalStations'] ?? 0 }}</div>
                <div class="stat-card-label">Field Stations</div>
            </div>

            {{-- System Health --}}
            <div class="stat-card">
                <div class="stat-card-icon bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="stat-card-value text-green-600">Healthy</div>
                <div class="stat-card-label">System Status</div>
                <div class="stat-card-trend text-green-600">
                    <span class="inline-flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span>All systems operational</span>
                </div>
            </div>
        </div>

        {{-- Management Quick Actions --}}
        <div class="card-premium p-8">
            <h2 class="text-2xl font-bold text-nimr-neutral-900 mb-6 flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-{{ $primary }}-500 to-orange-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                System Management
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- User Management --}}
                <a href="{{ route('admin.users.index') }}"
                    class="group p-6 rounded-xl border-2 border-nimr-neutral-200 hover:border-nimr-primary-400 hover:bg-nimr-primary-50 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-nimr-primary-400 to-nimr-primary-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-nimr-neutral-900 group-hover:text-nimr-primary-700">User Management
                            </h3>
                            <p class="text-sm text-nimr-neutral-600">Manage all system users</p>
                        </div>
                    </div>
                </a>

                {{-- Centre Management --}}
                <a href="{{ route('admin.centres.index') }}"
                    class="group p-6 rounded-xl border-2 border-nimr-neutral-200 hover:border-purple-400 hover:bg-purple-50 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-nimr-neutral-900 group-hover:text-purple-700">Centres</h3>
                            <p class="text-sm text-nimr-neutral-600">Manage research centres</p>
                        </div>
                    </div>
                </a>

                {{-- Station Management --}}
                <a href="{{ route('admin.stations.index') }}"
                    class="group p-6 rounded-xl border-2 border-nimr-neutral-200 hover:border-orange-400 hover:bg-orange-50 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-nimr-neutral-900 group-hover:text-orange-700">Stations</h3>
                            <p class="text-sm text-nimr-neutral-600">Manage field stations</p>
                        </div>
                    </div>
                </a>

                {{-- Settings --}}
                <a href="{{ route('admin.settings.index') }}"
                    class="group p-6 rounded-xl border-2 border-nimr-neutral-200 hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-nimr-neutral-900 group-hover:text-indigo-700">Settings</h3>
                            <p class="text-sm text-nimr-neutral-600">System configuration</p>
                        </div>
                    </div>
                </a>

                {{-- Reports --}}
                <a href="{{ route('admin.reports.index') }}"
                    class="group p-6 rounded-xl border-2 border-nimr-neutral-200 hover:border-green-400 hover:bg-green-50 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-nimr-neutral-900 group-hover:text-green-700">Reports</h3>
                            <p class="text-sm text-nimr-neutral-600">Analytics & insights</p>
                        </div>
                    </div>
                </a>

                {{-- Logs --}}
                <a href="{{ route('admin.logs.index') }}"
                    class="group p-6 rounded-xl border-2 border-nimr-neutral-200 hover:border-gray-400 hover:bg-gray-50 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-nimr-neutral-900 group-hover:text-gray-700">System Logs</h3>
                            <p class="text-sm text-nimr-neutral-600">Activity monitoring</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Announcements --}}
            <div class="card-premium overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-nimr-primary-50 to-indigo-50 border-b border-nimr-primary-100">
                    <h3 class="text-lg font-bold text-nimr-neutral-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-nimr-primary-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        Recent Announcements
                    </h3>
                </div>
                <div class="p-6">
                    @if (isset($recentAnnouncements) && $recentAnnouncements->count())
                        <div class="space-y-3">
                            @foreach ($recentAnnouncements->take(5) as $announcement)
                                <a href="{{ route('announcements.show', $announcement) }}"
                                    class="block p-3 rounded-lg border border-nimr-neutral-200 hover:border-nimr-primary-300 hover:bg-nimr-primary-50 transition-all">
                                    <p class="font-medium text-nimr-neutral-900 line-clamp-1">{{ $announcement->title }}
                                    </p>
                                    <p class="text-xs text-nimr-neutral-500 mt-1">
                                        {{ $announcement->created_at->diffForHumans() }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-nimr-neutral-500 text-center py-8">No recent announcements</p>
                    @endif
                </div>
            </div>

            {{-- System Activity --}}
            <div class="card-premium overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100">
                    <h3 class="text-lg font-bold text-nimr-neutral-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        System Activity
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-nimr-neutral-600">System running smoothly</span>
                            <span class="ml-auto text-xs text-nimr-neutral-500">Just now</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <span class="text-nimr-neutral-600">{{ $adminStats['totalUsers'] ?? 0 }} users active
                                today</span>
                            <span class="ml-auto text-xs text-nimr-neutral-500">Today</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                            <span class="text-nimr-neutral-600">{{ $adminStats['totalAnnouncements'] ?? 0 }} total
                                announcements</span>
                            <span class="ml-auto text-xs text-nimr-neutral-500">All time</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
