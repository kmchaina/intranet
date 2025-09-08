@extends('layouts.dashboard')

@section('title', 'Staff Dashboard')

@section('page-title')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-medium bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent tracking-tight">
                Welcome back, <span class="font-bold text-orange-500">
                    @php
                        $nameParts = explode(' ', auth()->user()->name);
                        $titles = ['Dr.', 'Dr', 'Prof.', 'Prof', 'Mr.', 'Mr', 'Mrs.', 'Mrs', 'Ms.', 'Ms', 'Miss'];
                        $firstName = $nameParts[0];
                        foreach ($titles as $title) {
                            if (stripos($firstName, $title) === 0) {
                                $firstName = isset($nameParts[1]) ? $nameParts[1] : $firstName;
                                break;
                            }
                        }
                    @endphp
                    {{ $firstName }}
                </span>!
            </h1>
            <p class="text-gray-600 mt-1">{{ $userRole }} - {{ auth()->user()->centre->name ?? auth()->user()->station->name ?? 'NIMR Headquarters' }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
            <p class="text-xs text-gray-400">{{ now()->format('g:i A') }}</p>
        </div>
    </div>
@endsection

@section('content')
    <!-- Top Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Recent Announcements Card -->
        <div class="group relative bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="relative p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">📢 Announcements</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $recentAnnouncements->count() }}</p>
                        <div class="flex items-center mt-2">
                            <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                            <span class="text-xs text-gray-600">Recent updates</span>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
            </div>
            <div class="px-6 pb-6">
                <a href="{{ route('announcements.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700 group-hover:underline">
                    View all announcements
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- My Tasks Card -->
        <div class="group relative bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="relative p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">✅ My Tasks</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">0</p>
                        <div class="flex items-center mt-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-xs text-gray-600">All caught up!</span>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
            </div>
            <div class="px-6 pb-6">
                <a href="#" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-700 group-hover:underline">
                    Manage tasks
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Password Vault Card -->
        <div class="group relative bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="relative p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">🔐 Password Vault</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">Secure</p>
                        <div class="flex items-center mt-2">
                            <div class="w-2 h-2 bg-purple-400 rounded-full mr-2"></div>
                            <span class="text-xs text-gray-600">Protected access</span>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-violet-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
            </div>
            <div class="px-6 pb-6">
                <a href="#" class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-700 group-hover:underline">
                    Access vault
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Events Card -->
        <div class="group relative bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="relative p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">📅 Events</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">2</p>
                        <div class="flex items-center mt-2">
                            <div class="w-2 h-2 bg-pink-400 rounded-full mr-2"></div>
                            <span class="text-xs text-gray-600">This week</span>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-pink-50 to-rose-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
            </div>
            <div class="px-6 pb-6">
                <a href="#" class="inline-flex items-center text-sm font-medium text-pink-600 hover:text-pink-700 group-hover:underline">
                    View calendar
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Announcements -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">📢 Recent Announcements</h2>
                        <a href="{{ route('announcements.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View all</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentAnnouncements && $recentAnnouncements->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentAnnouncements as $announcement)
                                <div class="p-4 border border-gray-100 rounded-lg hover:border-gray-200 transition-colors group">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                                                <a href="{{ route('announcements.show', $announcement) }}">{{ $announcement->title }}</a>
                                            </h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->content, 120) }}</p>
                                            <div class="flex items-center mt-2 space-x-4">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                    @if($announcement->priority === 'high') bg-red-100 text-red-800
                                                    @elseif($announcement->priority === 'medium') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst($announcement->priority) }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $announcement->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                            <p class="text-sm text-gray-500">No announcements available</p>
                            <p class="text-xs text-gray-400 mt-1">Check back later for updates</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Documents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">📄 Recent Documents</h2>
                        <a href="{{ route('documents.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View all</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentDocuments && $recentDocuments->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentDocuments as $document)
                                <div class="p-4 border border-gray-100 rounded-lg hover:border-gray-200 transition-colors group">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-violet-600 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l2.414 2.414A1 1 0 0116 6.414V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-medium text-gray-900 group-hover:text-purple-600 transition-colors">
                                                <a href="{{ route('documents.show', $document) }}">{{ $document->title }}</a>
                                            </h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($document->description, 80) }}</p>
                                            <div class="flex items-center mt-2 space-x-4">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                    @if($document->access_level === 'public') bg-green-100 text-green-800
                                                    @elseif($document->access_level === 'internal') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($document->access_level) }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $document->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l2.414 2.414A1 1 0 0116 6.414V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm text-gray-500">No documents available</p>
                            <p class="text-xs text-gray-400 mt-1">Documents will appear here when uploaded</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Birthday Celebrations -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">🎂 Birthday Celebrations</h3>
                </div>
                <div class="p-6">
                    @if($todayBirthdays && $todayBirthdays->count() > 0)
                        <div class="space-y-4">
                            @foreach($todayBirthdays as $birthdayUser)
                                <div class="flex items-center space-x-3 p-3 rounded-lg bg-gradient-to-r from-pink-50 to-purple-50 border border-pink-100">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-medium text-lg">🎂</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900">{{ $birthdayUser->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            🎉 Happy Birthday! 
                                            @if($birthdayUser->centre)
                                                • {{ $birthdayUser->centre->name }}
                                            @elseif($birthdayUser->station)
                                                • {{ $birthdayUser->station->name }}
                                            @else
                                                • NIMR Headquarters
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-xs text-gray-500">🎁 Let's celebrate together!</p>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl">🎂</span>
                            </div>
                            <p class="text-sm text-gray-500">No birthdays today</p>
                            <p class="text-xs text-gray-400 mt-1">Check back tomorrow!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent News -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">📰 Latest News</h3>
                        <a href="{{ route('news.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View all</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentNews && $recentNews->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentNews as $news)
                                <div class="group">
                                    <h4 class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                                        <a href="{{ route('news.show', $news) }}">{{ $news->title }}</a>
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($news->excerpt ?? $news->content, 80) }}</p>
                                    <div class="flex items-center mt-2 text-xs text-gray-500">
                                        <span>{{ $news->location }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $news->published_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"/>
                            </svg>
                            <p class="text-sm text-gray-500">No news available</p>
                            <p class="text-xs text-gray-400 mt-1">Check back later for updates</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">🔗 Quick Links</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('documents.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l2.414 2.414A1 1 0 0116 6.414V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Document Library</span>
                        </a>
                        
                        <a href="{{ route('polls.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Polls & Surveys</span>
                        </a>
                        
                        <a href="{{ route('news.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Latest News</span>
                        </a>
                        
                        <a href="{{ route('profile.show') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">My Profile</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
