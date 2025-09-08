@extends('layouts.dashboard')

@section('title', 'Station Admin Dashboard')

@section('page-title')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-medium bg-gradient-to-r from-orange-600 via-amber-600 to-yellow-600 bg-clip-text text-transparent tracking-tight">
                Station Administration
            </h1>
            <p class="text-gray-600 mt-1">{{ $userRole }} - {{ auth()->user()->location->name ?? 'Station Management' }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
            <p class="text-xs text-gray-400">{{ now()->format('g:i A') }}</p>
        </div>
    </div>
@endsection

@section('content')
    <!-- Station Management Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Station Announcements -->
        <div class="group relative bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="relative p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">📢 Announcements</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $adminStats['stationAnnouncements'] ?? 0 }}</p>
                        <div class="flex items-center mt-2">
                            <div class="w-2 h-2 bg-orange-400 rounded-full mr-2"></div>
                            <span class="text-xs text-gray-600">Station-specific</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('announcements.index') }}" class="flex items-center justify-between text-sm font-medium text-gray-700 hover:text-orange-600 group transition-colors">
                    <span>Manage announcements</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Station Documents -->
        <div class="group relative bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="relative p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l2.414 2.414A1 1 0 0116 6.414V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">📄 Documents</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $adminStats['stationDocuments'] ?? 0 }}</p>
                        <div class="flex items-center mt-2">
                            <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                            <span class="text-xs text-gray-600">Station documents</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('documents.index') }}" class="flex items-center justify-between text-sm font-medium text-gray-700 hover:text-blue-600 group transition-colors">
                    <span>Manage documents</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Station Staff -->
        <div class="group relative bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="relative p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">👥 Staff</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $adminStats['stationStaff'] ?? 0 }}</p>
                        <div class="flex items-center mt-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-xs text-gray-600">At this station</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('admin.station.staff.index') }}" class="flex items-center justify-between text-sm font-medium text-gray-700 hover:text-green-600 group transition-colors">
                    <span>Manage staff</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Personal Vault -->
        <div class="group relative bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="relative p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">🔐 My Vault</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            <span id="vault-count">0</span>
                        </p>
                        <div class="flex items-center mt-2">
                            <div class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></div>
                            <span class="text-xs text-gray-600">Personal passwords</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('password-vault.index') }}" class="flex items-center justify-between text-sm font-medium text-gray-700 hover:text-indigo-600 group transition-colors">
                    <span>Access vault</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🚀 Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <a href="{{ route('announcements.create') }}" class="flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-colors group">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors mb-2">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-orange-700">Create Announcement</span>
                </a>

                <a href="{{ route('documents.create') }}" class="flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors group">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors mb-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l2.414 2.414A1 1 0 0116 6.414V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Upload Document</span>
                </a>

                <a href="{{ route('news.create') }}" class="flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors group">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors mb-2">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Write News</span>
                </a>

                <a href="{{ route('polls.create') }}" class="flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 transition-colors group">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors mb-2">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700">Create Poll</span>
                </a>

                <a href="{{ route('events.create') }}" class="flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-red-300 hover:bg-red-50 transition-colors group">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors mb-2">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-700">Schedule Event</span>
                </a>

                <a href="{{ route('todos.create') }}" class="flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-colors group">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors mb-2">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Add Task</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Dashboard Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Station Overview -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">📊 Station Overview</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $adminStats['stationStaff'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Staff Members</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $adminStats['stationAnnouncements'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Announcements</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $adminStats['stationDocuments'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Documents</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $adminStats['stationProjects'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Projects</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Station Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-6">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">📈 Recent Station Activity</h3>
                </div>
                <div class="p-6">
                    @if($recentAnnouncements && $recentAnnouncements->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentAnnouncements->take(5) as $announcement)
                                <div class="flex items-start space-x-4 p-4 rounded-lg border border-gray-100">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-amber-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $announcement->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->content, 100) }}</p>
                                        <div class="flex items-center mt-2 space-x-4">
                                            <span class="text-xs text-gray-500">{{ $announcement->created_at->diffForHumans() }}</span>
                                            <span class="text-xs text-gray-500">{{ $announcement->creator->name }}</span>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $announcement->priority === 'urgent' ? 'red' : ($announcement->priority === 'high' ? 'orange' : 'gray') }}-100 text-{{ $announcement->priority === 'urgent' ? 'red' : ($announcement->priority === 'high' ? 'orange' : 'gray') }}-800">
                                                {{ ucfirst($announcement->priority) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="text-gray-500">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Station Admin Sidebar -->
        <div class="space-y-6">
            <!-- Station Staff -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">👥 Station Staff</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($staffList ?? false)
                            @foreach($staffList as $staff)
                                <div class="flex items-center space-x-3 p-3 rounded-lg border border-gray-100">
                                    <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">{{ strtoupper(substr($staff['name'], 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $staff['name'] }}</h4>
                                        <p class="text-sm text-gray-600">{{ $staff['role'] ?? 'Staff' }}</p>
                                    </div>
                                    <div class="w-2 h-2 bg-{{ $staff['active'] ?? true ? 'green' : 'gray' }}-400 rounded-full"></div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <p class="text-sm text-gray-500">No staff members</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pending Tasks -->
            @if($pendingItems && (($pendingItems['draftAnnouncements'] ?? 0) > 0 || ($pendingItems['draftPolls'] ?? 0) > 0))
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">⏳ Pending Items</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @if(($pendingItems['draftAnnouncements'] ?? 0) > 0)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-yellow-800">Draft Announcements</p>
                                        <p class="text-sm text-yellow-600">{{ $pendingItems['draftAnnouncements'] }} awaiting publication</p>
                                    </div>
                                    <a href="{{ route('announcements.index', ['status' => 'draft']) }}" class="text-yellow-600 hover:text-yellow-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endif

                            @if(($pendingItems['draftPolls'] ?? 0) > 0)
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-blue-800">Draft Polls</p>
                                        <p class="text-sm text-blue-600">{{ $pendingItems['draftPolls'] }} ready to publish</p>
                                    </div>
                                    <a href="{{ route('polls.index', ['status' => 'draft']) }}" class="text-blue-600 hover:text-blue-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Station Resources -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">🔧 Station Resources</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.station.reports.index') }}" class="flex items-center p-3 rounded-lg border border-gray-100 hover:border-gray-200 hover:bg-gray-50 transition-colors group">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 group-hover:text-orange-600">Station Reports</h4>
                                <p class="text-sm text-gray-600 mt-1">View station analytics</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>

                        <a href="{{ route('admin.station.equipment.index') }}" class="flex items-center p-3 rounded-lg border border-gray-100 hover:border-gray-200 hover:bg-gray-50 transition-colors group">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 group-hover:text-orange-600">Equipment Management</h4>
                                <p class="text-sm text-gray-600 mt-1">Track station equipment</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>

                        <a href="{{ route('admin.station.projects.index') }}" class="flex items-center p-3 rounded-lg border border-gray-100 hover:border-gray-200 hover:bg-gray-50 transition-colors group">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 group-hover:text-orange-600">Research Projects</h4>
                                <p class="text-sm text-gray-600 mt-1">Manage station projects</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
