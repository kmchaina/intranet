@extends('layouts.dashboard')
@section('title', 'Centre Admin Dashboard')

@section('page-title')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 bg-clip-text text-transparent tracking-tight">
            Centre Command Hub
        </h1>
        <p class="text-gray-600 mt-1 text-lg">{{ $userRole }} - {{ auth()->user()->location->name ?? 'Centre Management' }} Operations</p>
    </div>
    <div class="flex items-center space-x-4">
        <div class="text-right">
            <p class="text-sm font-medium text-gray-900">{{ now()->format('l, F j, Y') }}</p>
            <p class="text-xs text-gray-500">{{ now()->format('g:i A T') }}</p>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>
</div>
@endsection

@section('content')

@include('dashboard.partials.adoption-widget')
@include('dashboard.partials.badges-widget')

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
    <div class="lg:col-span-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium bg-white/20 px-2 py-1 rounded-full">CENTRE REACH</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">{{ $adminStats['centreStations'] ?? 0 }}</h3>
                    <p class="text-green-100 text-sm">Research Stations</p>
                    <div class="mt-4 flex items-center text-xs">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <span>{{ $adminStats['centreUsers'] ?? 0 }} researchers active</span>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium bg-white/20 px-2 py-1 rounded-full">COMMUNICATIONS</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">{{ $adminStats['centreAnnouncements'] ?? 0 }}</h3>
                    <p class="text-blue-100 text-sm">Centre Announcements</p>
                    <div class="mt-4 flex items-center text-xs">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Last 30 days: {{ $recentStats['announcements_this_month'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium bg-white/20 px-2 py-1 rounded-full">RESOURCES</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">{{ $adminStats['centreDocuments'] ?? 0 }}</h3>
                    <p class="text-purple-100 text-sm">Centre Documents</p>
                    <div class="mt-4 flex items-center text-xs">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span>This week: {{ $recentStats['documents_this_week'] ?? 0 }} uploaded</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold mb-2">Centre Management Hub</h2>
                        <p class="text-green-100">Essential centre administration and communications</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="{{ route('announcements.create') }}" class="group relative bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6 hover:border-green-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg group-hover:bg-green-600 transition-colors mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-green-800">Centre Announcement</h3>
                            <p class="text-sm text-gray-600 mb-3">Communicate with all centre stations</p>
                            <span class="inline-flex items-center text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full"> Centre-wide </span>
                        </div>
                    </a>
                    <a href="{{ route('documents.create') }}" class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6 hover:border-blue-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg group-hover:bg-blue-600 transition-colors mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-blue-800">Research Documents</h3>
                            <p class="text-sm text-gray-600 mb-3">Share centre research and protocols</p>
                            <span class="inline-flex items-center text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full"> Research Hub </span>
                        </div>
                    </a>
                    <a href="{{ route('admin.stations.index') }}" class="group relative bg-gradient-to-br from-purple-50 to-violet-50 border-2 border-purple-200 rounded-xl p-6 hover:border-purple-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg group-hover:bg-purple-600 transition-colors mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-purple-800">Station Management</h3>
                            <p class="text-sm text-gray-600 mb-3">Oversee research stations</p>
                            <span class="inline-flex items-center text-xs font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded-full"> Operations </span>
                        </div>
                    </a>
                    <a href="{{ route('admin.centre.staff.index') }}" class="group relative bg-gradient-to-br from-orange-50 to-amber-50 border-2 border-orange-200 rounded-xl p-6 hover:border-orange-400 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center shadow-lg group-hover:bg-orange-600 transition-colors mb-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-orange-800">Staff Management</h3>
                            <p class="text-sm text-gray-600 mb-3">Manage centre researchers</p>
                            <span class="inline-flex items-center text-xs font-medium text-orange-600 bg-orange-100 px-2 py-1 rounded-full"> Personnel </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Centre Activity Stream</h2>
                        <p class="text-gray-600 mt-1">Real-time centre communications and station updates</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></div>
                            Centre Operations
                        </span>
                        <button class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @if($recentAnnouncements && $recentAnnouncements->count() > 0)
                    @foreach($recentAnnouncements->take(5) as $announcement)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $announcement->title }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->content, 100) }}</p>
                                        </div>
                                        <span class="text-xs text-gray-500 whitespace-nowrap ml-4">{{ $announcement->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex items-center mt-3 space-x-4">
                                        <span class="text-xs font-medium text-gray-700">{{ $announcement->creator->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $announcement->creator->location->name ?? 'Centre' }}</span>
                                        <span class="inline-flex items-center text-xs font-medium text-{{ $announcement->priority === 'urgent' ? 'red' : ($announcement->priority === 'high' ? 'orange' : 'green') }}-600 bg-{{ $announcement->priority === 'urgent' ? 'red' : ($announcement->priority === 'high' ? 'orange' : 'green') }}-100 px-2 py-1 rounded-full">{{ ucfirst($announcement->priority) }} Priority</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-12 text-center text-gray-500">
                        <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Recent Centre Activity</h3>
                        <p class="text-sm text-gray-500">Centre communications and station updates will appear here</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="lg:col-span-4">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-violet-500 p-4 text-white">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold">Station Status</h3>
                </div>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @if($stationStats ?? false)
                        @foreach($stationStats as $station)
                            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-200">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">{{ $station['name'] }}</span>
                                        <p class="text-xs text-gray-600">{{ $station['location'] ?? 'Research Station' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-purple-600">{{ $station['users_count'] ?? 0 }} users</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-xs text-gray-500">No stations configured</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Centre Performance</h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Research Activity</span>
                    <div class="flex items-center">
                        <div class="w-16 h-2 bg-gray-200 rounded-full mr-2">
                            <div class="w-4/5 h-2 bg-green-500 rounded-full"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-900">80%</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Station Connectivity</span>
                    <div class="flex items-center text-green-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                        </svg>
                        <span class="text-xs font-medium">Online</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Resource Access</span>
                    <div class="flex items-center">
                        <div class="w-16 h-2 bg-gray-200 rounded-full mr-2">
                            <div class="w-3/4 h-2 bg-blue-500 rounded-full"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-900">75%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Quick Tools</h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('polls.create') }}" class="flex flex-col items-center p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                        <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Centre Poll</span>
                    </a>
                    <a href="{{ route('events.create') }}" class="flex flex-col items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                        <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Schedule Event</span>
                    </a>
                    <a href="{{ route('admin.reports.centre') }}" class="flex flex-col items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">Centre Reports</span>
                    </a>
                    <a href="{{ route('password-vault.index') }}" class="flex flex-col items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-center">My Vault</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
