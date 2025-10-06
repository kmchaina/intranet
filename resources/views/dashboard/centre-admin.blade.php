@extends('layouts.dashboard')
@section('title', 'Centre Admin Dashboard')

@section('page-title')
    @php
        $primary = $themeColors['primary'] ?? 'violet';
        $accent = $themeColors['accent'] ?? 'fuchsia';
    @endphp
    <div class="flex items-center justify-between animate-fade-in">
        <div>
            <h1 class="text-3xl font-bold">
                <span
                    class="bg-gradient-to-r from-{{ $primary }}-600 to-{{ $accent }}-600 bg-clip-text text-transparent">
                    {{ $centreName ?? 'Centre' }} Administration
                </span>
            </h1>
            <p class="text-nimr-neutral-600 mt-1">{{ $userRole }} • Centre Management</p>
        </div>
        <div
            class="w-12 h-12 bg-gradient-to-br from-{{ $primary }}-500 to-{{ $accent }}-600 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-8">

        {{-- Centre Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="stat-card">
                <div class="stat-card-icon bg-{{ $primary }}-100 text-{{ $primary }}-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $centreUsers->count() ?? 0 }}</div>
                <div class="stat-card-label">Centre Staff</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon bg-orange-100 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $centreStations ?? 0 }}</div>
                <div class="stat-card-label">Field Stations</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon bg-nimr-primary-100 text-nimr-primary-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $centreAnnouncements->count() ?? 0 }}</div>
                <div class="stat-card-label">Announcements</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $centreDocuments ?? 0 }}</div>
                <div class="stat-card-label">Documents</div>
            </div>
        </div>

        {{-- Centre Management --}}
        <div class="card-premium p-8">
            <h2 class="text-2xl font-bold text-nimr-neutral-900 mb-6 flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-{{ $primary }}-500 to-{{ $accent }}-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                Centre Management
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.centre.users.index') }}"
                    class="group p-6 rounded-xl border-2 border-nimr-neutral-200 hover:border-{{ $primary }}-400 hover:bg-{{ $primary }}-50 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-{{ $primary }}-400 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-nimr-neutral-900 group-hover:text-{{ $primary }}-700">Centre Users</h3>
                            <p class="text-sm text-nimr-neutral-600">Manage centre staff</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.centre.staff.index') }}"
                    class="group p-6 rounded-xl border-2 border-nimr-neutral-200 hover:border-pink-400 hover:bg-pink-50 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-pink-400 to-{{ $accent }}-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-nimr-neutral-900 group-hover:text-pink-700">Staff Directory</h3>
                            <p class="text-sm text-nimr-neutral-600">View all staff</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.centres.index') }}"
                    class="group p-6 rounded-xl border-2 border-nimr-neutral-200 hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-nimr-neutral-900 group-hover:text-indigo-700">Stations</h3>
                            <p class="text-sm text-nimr-neutral-600">Manage stations</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Centre Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card-premium overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-{{ $primary }}-50 to-{{ $accent }}-50 border-b border-{{ $primary }}-100">
                    <h3 class="text-lg font-bold text-nimr-neutral-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-{{ $primary }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        Centre Announcements
                    </h3>
                </div>
                <div class="p-6">
                    @if (isset($centreAnnouncements) && $centreAnnouncements->count())
                        <div class="space-y-3">
                            @foreach ($centreAnnouncements->take(5) as $announcement)
                                <a href="{{ route('announcements.show', $announcement) }}"
                                    class="block p-3 rounded-lg border border-nimr-neutral-200 hover:border-{{ $primary }}-300 hover:bg-{{ $primary }}-50 transition-all">
                                    <p class="font-medium text-nimr-neutral-900 line-clamp-1">{{ $announcement->title }}
                                    </p>
                                    <p class="text-xs text-nimr-neutral-500 mt-1">
                                        {{ $announcement->creator->name }} •
                                        {{ $announcement->created_at->diffForHumans() }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-nimr-neutral-500 text-center py-8">No announcements yet</p>
                    @endif
                </div>
            </div>

            <div class="card-premium overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-orange-100">
                    <h3 class="text-lg font-bold text-nimr-neutral-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Recent Activity
                    </h3>
                </div>
                <div class="p-6">
                    @if (isset($recentCentreActivity) && $recentCentreActivity->count())
                        <div class="space-y-4">
                            @foreach ($recentCentreActivity->take(5) as $activity)
                                <div class="flex items-start gap-3 text-sm">
                                    <div class="w-2 h-2 bg-{{ $primary }}-500 rounded-full mt-1.5"></div>
                                    <div class="flex-1">
                                        <p class="text-nimr-neutral-900">
                                            <span class="font-medium">{{ $activity['user'] }}</span>
                                            {{ $activity['type'] === 'announcement' ? 'created an announcement' : ($activity['type'] === 'poll' ? 'created a poll' : 'uploaded a document') }}:
                                            <a href="{{ $activity['url'] }}"
                                                class="text-nimr-primary-600 hover:underline">{{ $activity['title'] }}</a>
                                        </p>
                                        <p class="text-xs text-nimr-neutral-500 mt-1">
                                            {{ $activity['created_at']->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-nimr-neutral-500 text-center py-8">No recent activity</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
