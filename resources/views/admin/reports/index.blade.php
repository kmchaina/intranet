@extends('layouts.dashboard')

@section('title', 'Reports Overview')
@section('page-title', 'Reports Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Navigation Tabs -->
        <div class="mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                <a href="{{ route('admin.reports.index') }}"
                    class="border-indigo-500 text-indigo-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Overview
                </a>
                @if (auth()->user()->isHqAdmin() || auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.reports.organizational') }}"
                        class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Organizational
                    </a>
                @endif
                @if (auth()->user()->isCentreAdmin() || auth()->user()->isHqAdmin() || auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.reports.centre') }}"
                        class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Centre Insights
                    </a>
                @endif
            </nav>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_users']) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['active_users']) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10m-5 6h5a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v8a2 2 0 002 2h5z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Announcements</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_announcements']) }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7a1 1 0 011-1h16a1 1 0 011 1v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Documents</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_documents']) }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-card">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">New Users</h3>
                            <ul class="mt-2 space-y-2 text-sm text-gray-600">
                                @foreach ($recentActivity['recent_users'] as $recentUser)
                                    <li class="flex justify-between">
                                        <span>{{ $recentUser->name }}</span>
                                        <span>{{ $recentUser->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Announcements</h3>
                            <ul class="mt-2 space-y-2 text-sm text-gray-600">
                                @foreach ($recentActivity['recent_announcements'] as $announcement)
                                    <li class="flex justify-between">
                                        <span>{{ $announcement->title }}</span>
                                        <span>{{ $announcement->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-card">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">User Engagement</h2>
                    <ul class="space-y-4 text-sm text-gray-600">
                        <li class="flex justify-between">
                            <span>Active creators</span>
                            <span>{{ $userEngagement['active_creators'] }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Messaging users</span>
                            <span>{{ $userEngagement['messaging_users'] }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Average messages/user</span>
                            <span>{{ $userEngagement['avg_messages_per_user'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Stats -->
        <div class="mt-8 bg-white rounded-lg shadow-card">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Content Trends (30 days)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Announcements</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Documents</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Events</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Polls</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-600">
                            @foreach ($contentStats['content_trends'] as $date => $trend)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $date }}</td>
                                    <td class="px-6 py-4">{{ $trend['announcements'] }}</td>
                                    <td class="px-6 py-4">{{ $trend['documents'] }}</td>
                                    <td class="px-6 py-4">{{ $trend['events'] }}</td>
                                    <td class="px-6 py-4">{{ $trend['polls'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
