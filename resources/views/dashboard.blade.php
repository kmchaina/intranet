@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Welcome Back, ' . auth()->user()->name)
@section('page-subtitle', 'Here\'s what\'s happening at NIMR today')

@section('content')
    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Announcements -->
        <div class="stats-card rounded-3xl p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Announcements</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Announcement::count() }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        +3 this week
                    </p>
                </div>
                <div class="w-16 h-16 icon-gradient-blue rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Staff -->
        <div class="stats-card rounded-3xl p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Staff</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::count() }}</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Active members
                    </p>
                </div>
                <div class="w-16 h-16 icon-gradient-green rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Research Centers -->
        <div class="stats-card rounded-3xl p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Research Centers</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Centre::count() }}</p>
                    <p class="text-sm text-purple-600 dark:text-purple-400 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364-.636l-.707.707M21 12h-1M19.071 19.071l-.707-.707M12 21v-1m-6.364.636l.707-.707M3 12h1M4.929 4.929l.707.707" />
                        </svg>
                        Nationwide
                    </p>
                </div>
                <div class="w-16 h-16 icon-gradient-purple rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0v-4a2 2 0 012-2h2a2 2 0 012 2v4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Research Stations -->
        <div class="stats-card rounded-3xl p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Research Stations</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Station::count() }}</p>
                    <p class="text-sm text-orange-600 dark:text-orange-400 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        Active sites
                    </p>
                </div>
                <div class="w-16 h-16 icon-gradient-orange rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid: 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Announcements + Recent Activity -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Recent Announcements -->
            <div class="card-modern rounded-3xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Recent Announcements</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">Stay updated with the latest news</p>
                    </div>
                    <a href="{{ route('announcements.index') }}" class="btn-primary text-sm px-4 py-2">
                        View All
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse(\App\Models\Announcement::with('creator')->latest()->take(3)->get() as $announcement)
                        <div class="announcement-card p-6 rounded-2xl">
                            <div class="flex items-start space-x-4">
                                <div
                                    class="w-12 h-12 icon-gradient-blue rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                        {{ $announcement->title }}
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-3">
                                        {{ Str::limit(strip_tags($announcement->content), 150) }}
                                    </p>
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center text-gray-500 dark:text-gray-400">
                                            <span class="font-medium">{{ $announcement->creator->name }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $announcement->created_at->diffForHumans() }}</span>
                                        </div>
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-medium rounded-full 
                                        {{ $announcement->priority === 'high'
                                            ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                            : ($announcement->priority === 'medium'
                                                ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                                                : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300') }}">
                                            {{ ucfirst($announcement->priority) }} Priority
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No announcements yet</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Be the first to share important updates with
                                your team.</p>
                            @if (auth()->user()->canCreateAnnouncements())
                                <a href="{{ route('announcements.create') }}"
                                    class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Announcement
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity Timeline -->
            <div class="card-modern rounded-3xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Recent Activity</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">Latest updates across NIMR</p>
                    </div>
                    <a href="#" class="btn-primary text-sm px-4 py-2">
                        View All
                    </a>
                </div>

                <div class="space-y-4">
                    <!-- System Activity -->
                    <div class="announcement-card p-6 rounded-2xl">
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 icon-gradient-blue rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    NIMR System
                                </h4>
                                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-3">
                                    Dashboard launched successfully. All systems are operational and running smoothly.
                                </p>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                                        <span class="font-medium">System Administrator</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ now()->format('M j, Y g:i A') }}</span>
                                    </div>
                                    <span
                                        class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        System Event
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->isSuperAdmin())
                        <!-- Admin Access Activity -->
                        <div class="announcement-card p-6 rounded-2xl">
                            <div class="flex items-start space-x-4">
                                <div
                                    class="w-12 h-12 icon-gradient-green rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                        Administrator Access
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-3">
                                        Super admin privileges activated. Full system access granted for administrative
                                        tasks.
                                    </p>
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center text-gray-500 dark:text-gray-400">
                                            <span class="font-medium">{{ auth()->user()->name }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ now()->subHours(2)->format('M j, Y g:i A') }}</span>
                                        </div>
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            Admin Access
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- User Login Activity -->
                    <div class="announcement-card p-6 rounded-2xl">
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 icon-gradient-purple rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    {{ auth()->user()->name }}
                                </h4>
                                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-3">
                                    Successfully signed in to NIMR Intranet. Welcome back to the system.
                                </p>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                                        <span class="font-medium">User Login</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ now()->subHours(3)->format('M j, Y g:i A') }}</span>
                                    </div>
                                    <span
                                        class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                        User Activity
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-8">
            <!-- Quick Actions -->
            <div class="card-modern rounded-3xl p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Quick Actions</h3>
                <div class="space-y-3">
                    @if (auth()->user()->canCreateAnnouncements())
                        <a href="{{ route('announcements.create') }}"
                            class="quick-action-card flex items-center p-4 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-2xl border border-blue-100 dark:border-blue-800/30 group">
                            <div
                                class="w-10 h-10 icon-gradient-blue rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 dark:text-white">New Announcement</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Share important updates</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endif

                    <a href="{{ route('announcements.index') }}"
                        class="quick-action-card flex items-center p-4 bg-gradient-to-r from-green-50 to-teal-50 dark:from-green-900/20 dark:to-teal-900/20 rounded-2xl border border-green-100 dark:border-green-800/30 group">
                        <div
                            class="w-10 h-10 icon-gradient-green rounded-xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white">View All Posts</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Browse announcements</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="quick-action-card flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-2xl border border-purple-100 dark:border-purple-800/30 group">
                        <div
                            class="w-10 h-10 icon-gradient-purple rounded-xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white">Update Profile</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your account</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- System Status -->
            <div class="card-modern rounded-3xl p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">System Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">NIMR Platform</span>
                        </div>
                        <span class="text-sm text-green-600 dark:text-green-400 font-semibold">Operational</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Database Services</span>
                        </div>
                        <span class="text-sm text-green-600 dark:text-green-400 font-semibold">Healthy</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Authentication</span>
                        </div>
                        <span class="text-sm text-green-600 dark:text-green-400 font-semibold">Active</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Email Services</span>
                        </div>
                        <span class="text-sm text-yellow-600 dark:text-yellow-400 font-semibold">Monitoring</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Last updated</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ now()->format('M j, Y g:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Weather Widget -->
            <div class="card-modern rounded-3xl p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Today in Dar es Salaam</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">28°C</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Partly Cloudy</div>
                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">Feels like 31°C</div>
                    </div>
                    <div class="text-6xl">⛅</div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Humidity</span>
                            <div class="font-semibold text-gray-900 dark:text-white">75%</div>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Wind</span>
                            <div class="font-semibold text-gray-900 dark:text-white">12 km/h</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Summary -->
            <div class="card-modern rounded-3xl p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Activity Summary</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Today's Logins</span>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">24</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">New Announcements</span>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">3</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Active Users</span>
                        <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">18</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">System Status</span>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
