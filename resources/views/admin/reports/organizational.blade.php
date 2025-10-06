@extends('layouts.dashboard')

@section('title', 'Organizational Reports')
@section('page-title', 'Organizational Insights')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="bg-white rounded-lg shadow-card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Hierarchy Stats</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <p class="text-sm text-gray-500 uppercase">Total Centres</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $hierarchyStats['total_centres'] }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <p class="text-sm text-gray-500 uppercase">Total Stations</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $hierarchyStats['total_stations'] }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <p class="text-sm text-gray-500 uppercase">Total Departments</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $hierarchyStats['total_departments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">User Distribution</h2>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">By Role</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        @foreach ($userDistribution['by_role'] as $role)
                            <li class="flex justify-between">
                                <span>{{ str_replace('_', ' ', ucwords($role->role)) }}</span>
                                <span>{{ $role->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">By Centre</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        @foreach ($userDistribution['by_centre'] as $centre)
                            <li class="flex justify-between">
                                <span>{{ $centre->centre->name ?? 'No Centre' }}</span>
                                <span>{{ $centre->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">By Station</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        @foreach ($userDistribution['by_station'] as $station)
                            <li class="flex justify-between">
                                <span>{{ $station->station->name ?? 'No Station' }}</span>
                                <span>{{ $station->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Activity by Unit</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Announcements</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        @foreach ($activityByUnit['announcements_by_centre'] as $centre)
                            <li class="flex justify-between">
                                <span>{{ $centre->name }}</span>
                                <span>{{ $centre->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Documents</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        @foreach ($activityByUnit['documents_by_centre'] as $centre)
                            <li class="flex justify-between">
                                <span>{{ $centre->name }}</span>
                                <span>{{ $centre->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Content By Unit</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Announcements</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        @foreach ($contentByUnit['by_centre']['announcements'] as $centre)
                            <li class="flex justify-between">
                                <span>{{ $centre->name }}</span>
                                <span>{{ $centre->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Documents</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                        @foreach ($contentByUnit['by_centre']['documents'] as $centre)
                            <li class="flex justify-between">
                                <span>{{ $centre->name }}</span>
                                <span>{{ $centre->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
