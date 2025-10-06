@extends('layouts.dashboard')

@section('title', 'Centre Reports')
@section('page-title', 'Centre Insights')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="bg-white rounded-lg shadow-card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Centre Overview</h2>
            @if (empty($centreStats))
                <p class="text-gray-600">You are not assigned to a centre. Centre-level reports are unavailable.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg text-center">
                        <p class="text-sm text-gray-500 uppercase">Total Users</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $centreStats['total_users'] }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg text-center">
                        <p class="text-sm text-gray-500 uppercase">Active Users</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $centreStats['active_users'] }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg text-center">
                        <p class="text-sm text-gray-500 uppercase">Announcements</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $centreStats['total_announcements'] }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg text-center">
                        <p class="text-sm text-gray-500 uppercase">Documents</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $centreStats['total_documents'] }}</p>
                    </div>
                </div>
            @endif
        </div>

        @if (!empty($stationStats))
            <div class="bg-white rounded-lg shadow-card p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Stations in Centre</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Station</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Users</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Active Users</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-600">
                            @foreach ($stationStats as $station)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $station['station']->name }}</td>
                                    <td class="px-6 py-4">{{ $station['total_users'] }}</td>
                                    <td class="px-6 py-4">{{ $station['active_users'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if (!empty($centreActivity))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-card p-6">
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Recent Users</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        @foreach ($centreActivity['recent_users'] as $recentUser)
                            <li class="flex justify-between">
                                <span>{{ $recentUser->name }}</span>
                                <span>{{ $recentUser->created_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white rounded-lg shadow-card p-6">
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Recent Announcements</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        @foreach ($centreActivity['recent_announcements'] as $announcement)
                            <li class="flex justify-between">
                                <span>{{ $announcement->title }}</span>
                                <span>{{ $announcement->created_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (!empty($centreContent))
            <div class="bg-white rounded-lg shadow-card p-6">
                <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Content by Category</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Announcements</h4>
                        <ul class="mt-2 space-y-1 text-sm text-gray-600">
                            @foreach ($centreContent['announcements_by_category'] as $category)
                                <li class="flex justify-between">
                                    <span>{{ $category->category ?? 'Uncategorised' }}</span>
                                    <span>{{ $category->count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Documents</h4>
                        <ul class="mt-2 space-y-1 text-sm text-gray-600">
                            @foreach ($centreContent['documents_by_category'] as $category)
                                <li class="flex justify-between">
                                    <span>{{ $category->category ?? 'Uncategorised' }}</span>
                                    <span>{{ $category->count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
