@extends('layouts.dashboard')

@section('title', 'Document Library')
@section('page-title', 'Document Library')
@section('page-subtitle', 'Access and manage organizational documents')

@section('content')
    <div class="p-6">
        <div class="max-w-7xl mx-auto" x-data="documentsApp()">
            <!-- Dynamic Header Section -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                <div class="mb-4 lg:mb-0">
                    <!-- Department View Header -->
                    <div x-show="viewMode === 'departments'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform translate-y-1"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📁 Document Library</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            Browse documents by department or explore all documents
                        </p>
                    </div>

                    <!-- Department Documents Header -->
                    <div x-show="viewMode === 'all'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform translate-y-1"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        <div class="flex items-center space-x-3">
                            <button @click="backToOverview()" 
                                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white" x-text="selectedDepartment + ' Documents'"></h1>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">
                                    Browse all documents in the <span x-text="selectedDepartment"></span> department
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                    <!-- View Toggle (only shown in document list mode) -->
                    <div x-show="viewMode === 'all'" class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                        <button @click="setDocumentView('grid')" :class="documentViewMode === 'grid' ? 'view-toggle active' : 'view-toggle'"
                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Grid
                        </button>
                        <button @click="setDocumentView('list')" :class="documentViewMode === 'list' ? 'view-toggle active' : 'view-toggle'"
                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            List
                        </button>
                    </div>

                    <!-- Upload Button (Admin Only) -->
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('documents.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Upload Document
                        </a>
                    @endif
                </div>
            </div>

            <!-- Department Overview Cards (View 1) -->
            <div x-show="viewMode === 'departments'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Human Resources Department -->
                    <div @click="selectDepartment('Human Resources')" 
                        class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden bg-blue-50 border-blue-200 hover:bg-blue-100 dark:bg-blue-900/20 dark:border-blue-800 dark:hover:bg-blue-900/30">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="text-4xl mr-4">👥</div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Human Resources</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Employee policies, forms, and procedures</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    <span x-text="getDepartmentCount('administrative')"></span> documents
                                </span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Information Technology Department -->
                    <div @click="selectDepartment('Information Technology')" 
                        class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden bg-green-50 border-green-200 hover:bg-green-100 dark:bg-green-900/20 dark:border-green-800 dark:hover:bg-green-900/30">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="text-4xl mr-4">💻</div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Information Technology</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">IT policies, guidelines, and technical documentation</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    <span x-text="getDepartmentCount('general')"></span> documents
                                </span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Research & Development Department -->
                    <div @click="selectDepartment('Research & Development')" 
                        class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden bg-purple-50 border-purple-200 hover:bg-purple-100 dark:bg-purple-900/20 dark:border-purple-800 dark:hover:bg-purple-900/30">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="text-4xl mr-4">🔬</div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Research & Development</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Research protocols, studies, and scientific documentation</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                    <span x-text="getDepartmentCount('research')"></span> documents
                                </span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Finance Department -->
                    <div @click="selectDepartment('Finance')" 
                        class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden bg-amber-50 border-amber-200 hover:bg-amber-100 dark:bg-amber-900/20 dark:border-amber-800 dark:hover:bg-amber-900/30">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="text-4xl mr-4">💰</div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Finance</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Financial policies, procedures, and guidelines</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300">
                                    <span x-text="getDepartmentCount('policy')"></span> documents
                                </span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Administration Department -->
                    <div @click="selectDepartment('Administration')" 
                        class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden bg-indigo-50 border-indigo-200 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:border-indigo-800 dark:hover:bg-indigo-900/30">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="text-4xl mr-4">🏢</div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Administration</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">General administrative documents and procedures</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                                    <span x-text="getDepartmentCount('administrative')"></span> documents
                                </span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Training & Development Department -->
                    <div @click="selectDepartment('Training & Development')" 
                        class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden bg-orange-50 border-orange-200 hover:bg-orange-100 dark:bg-orange-900/20 dark:border-orange-800 dark:hover:bg-orange-900/30">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="text-4xl mr-4">🎓</div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Training & Development</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Training materials, courses, and development resources</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300">
                                    <span x-text="getDepartmentCount('training')"></span> documents
                                </span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- All Documents Card -->
                    <div @click="selectDepartment('All Departments')" 
                        class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden bg-gray-50 border-gray-200 hover:bg-gray-100 dark:bg-gray-900/20 dark:border-gray-700 dark:hover:bg-gray-900/30">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="text-4xl mr-4">📚</div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Documents</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Browse all available documents across departments</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                    {{ $documents->total() ?? 0 }} documents
                                </span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Documents -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Total Documents</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Documents -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['recent'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">This Week</div>
                            </div>
                        </div>
                    </div>

                    <!-- Popular Documents -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['popular'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Most Downloaded</div>
                            </div>
                        </div>
                    </div>

                    <!-- My Downloads -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['myDownloads'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">My Downloads</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Access Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">📌 Quick Access</h3>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Recently viewed & popular documents</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Recent Documents Quick Access -->
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Recently Added
                            </h4>
                            @if(isset($documents) && $documents->count() > 0)
                                @foreach($documents->take(3) as $document)
                                    <a href="{{ route('documents.show', $document) }}" 
                                        class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                                {{ $document->title }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $document->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">No recent documents</p>
                            @endif
                        </div>

                        <!-- Popular Documents Quick Access -->
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Most Downloaded
                            </h4>
                            @php
                                $popularDocs = isset($documents) ? $documents->where('download_count', '>', 0)->sortByDesc('download_count')->take(3) : collect();
                            @endphp
                            @if($popularDocs->count() > 0)
                                @foreach($popularDocs as $document)
                                    <a href="{{ route('documents.show', $document) }}" 
                                        class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-purple-600 dark:group-hover:text-purple-400">
                                                {{ $document->title }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $document->download_count ?? 0 }} downloads
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">No downloads yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section (View 2) -->
            <div x-show="viewMode === 'all'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-8">
                <form method="GET" action="{{ route('documents.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                🔍 Search Documents
                            </label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search by title, description..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                📂 Category
                            </label>
                            <select id="category" name="category" x-model="selectedCategory"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All Categories</option>
                                <option value="general">General</option>
                                <option value="policy">Policy</option>
                                <option value="research">Research</option>
                                <option value="administrative">Administrative</option>
                                <option value="training">Training</option>
                            </select>
                        </div>

                        <!-- Access Level Filter -->
                        <div>
                            <label for="access_level"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                🔒 Access Level
                            </label>
                            <select id="access_level" name="access_level"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All Access Levels</option>
                                <option value="public" {{ request('access_level') === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="restricted" {{ request('access_level') === 'restricted' ? 'selected' : '' }}>Restricted</option>
                                <option value="confidential" {{ request('access_level') === 'confidential' ? 'selected' : '' }}>Confidential</option>
                            </select>
                        </div>

                        <!-- Tag Filter -->
                        <div>
                            <label for="tag" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                🏷️ Tags
                            </label>
                            <input type="text" id="tag" name="tag" value="{{ request('tag') }}"
                                placeholder="Search by tag..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z" />
                            </svg>
                            Apply Filters
                        </button>
                        <a href="{{ route('documents.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Documents Display (View 2) -->
            <div x-show="viewMode === 'all'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                
                @if (isset($documents) && $documents->count() > 0)
                    <!-- Grid View -->
                    <div x-show="documentViewMode === 'grid'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                            @foreach ($documents as $document)
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                                    <!-- Document Icon -->
                                    <div class="p-6 text-center border-b border-gray-100 dark:border-gray-700">
                                        <div class="flex justify-center mb-3">
                                            <div
                                                class="w-16 h-16 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 truncate"
                                            title="{{ $document->title }}">
                                            {{ $document->title }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                            {{ $document->description ?: 'No description available' }}
                                        </p>
                                    </div>

                                    <!-- Document Details -->
                                    <div class="p-4 space-y-3">
                                        <!-- Category and Access Level -->
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                {{ ucfirst($document->category ?? 'general') }}
                                            </span>
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                {{ ucfirst($document->access_level ?? 'public') }}
                                            </span>
                                        </div>

                                        <!-- File Info -->
                                        <div
                                            class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                            <span>{{ $document->file_size ?? 'Unknown size' }}</span>
                                            <span>{{ $document->download_count ?? 0 }} downloads</span>
                                        </div>

                                        <!-- Uploader and Date -->
                                        <div
                                            class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-100 dark:border-gray-700">
                                            <span>{{ $document->uploader->name ?? 'Unknown' }}</span>
                                            <span>{{ $document->created_at->format('M j, Y') }}</span>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex space-x-2 pt-3">
                                            <a href="{{ route('documents.show', $document) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg text-sm transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>
                                            <a href="{{ route('documents.download', $document) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-lg text-sm transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- List View -->
                    <div x-show="documentViewMode === 'list'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        class="space-y-4 mb-8">
                        @foreach ($documents as $document)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center space-x-4">
                                        <!-- Document Icon -->
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-12 h-12 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Document Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0 pr-4">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                                        {{ $document->title }}
                                                    </h3>
                                                    @if ($document->description)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                                            {{ $document->description }}
                                                        </p>
                                                    @endif

                                                    <!-- Metadata -->
                                                    <div
                                                        class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-500 dark:text-gray-400">
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            {{ $document->uploader->name ?? 'Unknown' }}
                                                        </span>
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-8 0h8M5 7v13a2 2 0 002 2h10a2 2 0 002-2V7H5z" />
                                                            </svg>
                                                            {{ $document->file_size ?? 'Unknown size' }}
                                                        </span>
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            {{ $document->download_count ?? 0 }} downloads
                                                        </span>
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-8 0h8M5 7v13a2 2 0 002 2h10a2 2 0 002-2V7H5z" />
                                                            </svg>
                                                            {{ $document->created_at->format('M j, Y') }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Badges and Actions -->
                                                <div class="flex flex-col items-end space-y-3">
                                                    <!-- Category and Access Level -->
                                                    <div class="flex flex-col items-end space-y-2">
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                            {{ ucfirst($document->category ?? 'general') }}
                                                        </span>
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                            {{ ucfirst($document->access_level ?? 'public') }}
                                                        </span>
                                                    </div>

                                                    <!-- Actions -->
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('documents.show', $document) }}"
                                                            class="inline-flex items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg text-sm transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            View
                                                        </a>
                                                        <a href="{{ route('documents.download', $document) }}"
                                                            class="inline-flex items-center px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-lg text-sm transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center">
                        {{ $documents->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div
                            class="w-24 h-24 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No documents found</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6" x-text="selectedDepartment === 'All Departments' ? 'No documents available at the moment.' : `No documents found in the ${selectedDepartment} department.`">
                        </p>
                        <div class="space-x-4">
                            <button @click="backToOverview()"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                                ← Back to Overview
                            </button>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('documents.create') }}"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Upload Document
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* View Toggle Styles */
            .view-toggle {
                color: #6b7280;
                background-color: transparent;
            }

            .view-toggle.active {
                color: #1f2937;
                background-color: #ffffff;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }

            .dark .view-toggle {
                color: #9ca3af;
            }

            .dark .view-toggle.active {
                color: #f9fafb;
                background-color: #374151;
            }

            /* Department Cards Animation */
            .department-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .department-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            /* Line clamp utility for older browsers */
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function documentsApp() {
                return {
                    viewMode: 'departments', // 'departments' or 'all'
                    selectedDepartment: '',
                    selectedCategory: '{{ request('category') }}',
                    documentViewMode: 'list', // 'grid' or 'list'
                    
                    // Department to category mapping
                    departmentCategories: {
                        'Human Resources': 'administrative',
                        'Information Technology': 'general',
                        'Research & Development': 'research',
                        'Finance': 'policy',
                        'Administration': 'administrative',
                        'Training & Development': 'training',
                        'All Departments': ''
                    },

                    init() {
                        // Load saved preferences
                        this.documentViewMode = localStorage.getItem('documentsViewMode') || 'list';
                        
                        // Check if we're coming back from a filtered view
                        const urlParams = new URLSearchParams(window.location.search);
                        if (urlParams.has('category') || urlParams.has('search') || urlParams.has('access_level') || urlParams.has('tag')) {
                            this.viewMode = 'all';
                            this.selectedDepartment = 'All Departments';
                        }
                    },

                    selectDepartment(departmentName) {
                        this.selectedDepartment = departmentName;
                        this.viewMode = 'all';
                        
                        // Update the category filter based on department
                        const category = this.departmentCategories[departmentName] || '';
                        
                        // Build URL with category filter
                        const url = new URL(window.location.href);
                        url.search = ''; // Clear existing params
                        
                        if (category) {
                            url.searchParams.set('category', category);
                        }
                        
                        // Redirect to filtered view
                        window.location.href = url.toString();
                    },

                    backToOverview() {
                        this.viewMode = 'departments';
                        this.selectedDepartment = '';
                        
                        // Clear filters and go back to base URL
                        const url = new URL(window.location.href);
                        url.search = '';
                        window.location.href = url.toString();
                    },

                    setDocumentView(viewType) {
                        this.documentViewMode = viewType;
                        localStorage.setItem('documentsViewMode', viewType);
                    },

                    getDepartmentCount(category) {
                        // Use actual counts from the backend
                        const counts = @json($categoryCounts ?? []);
                        return counts[category] || 0;
                    }
                };
            }

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Escape key to go back to overview
                if (e.key === 'Escape') {
                    const app = Alpine.$data(document.querySelector('[x-data]'));
                    if (app && app.viewMode === 'all') {
                        app.backToOverview();
                    }
                }
                
                // Alt + G for grid view
                if (e.altKey && e.key === 'g') {
                    e.preventDefault();
                    const app = Alpine.$data(document.querySelector('[x-data]'));
                    if (app && app.viewMode === 'all') {
                        app.setDocumentView('grid');
                    }
                }
                
                // Alt + L for list view
                if (e.altKey && e.key === 'l') {
                    e.preventDefault();
                    const app = Alpine.$data(document.querySelector('[x-data]'));
                    if (app && app.viewMode === 'all') {
                        app.setDocumentView('list');
                    }
                }
            });
        </script>
    @endpush
@endsection
