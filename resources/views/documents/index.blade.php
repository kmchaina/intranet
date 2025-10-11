@extends('layouts.dashboard')
@section('title', 'Document Library')
@section('content')
    <div class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-100/40 min-h-screen">
        <div class="max-w-7xl mx-auto p-6">
            <x-breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Documents']]" />
            <x-page.header title="Document Library">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </x-slot:icon>
            </x-page.header>

            <!-- Department Filter Section (Quick chips) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter by Department</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('documents.index') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ !request('category') ? 'bg-blue-100 text-blue-800 border-blue-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} border">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        All Documents <span
                            class="ml-2 bg-white/80 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $stats['total'] ?? 0 }}</span>
                    </a>

                    {{-- Policies Tab (Special Category) --}}
                    <a href="{{ route('documents.index', ['category' => 'policy']) }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ request('category') === 'policy' ? 'bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-800 border-purple-300 shadow-md' : 'bg-gradient-to-r from-purple-50 to-indigo-50 text-purple-700 hover:from-purple-100 hover:to-indigo-100 border-purple-200' }} border">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        📋 Policies & SOPs
                        <span
                            class="ml-2 {{ request('category') === 'policy' ? 'bg-purple-200 text-purple-900' : 'bg-white/80 text-purple-700' }} px-2 py-0.5 rounded-full text-xs font-semibold">
                            {{ $categoryCounts['policy'] ?? 0 }}
                        </span>
                    </a>

                    @foreach ($allDepartments as $deptKey => $deptName)
                        @php
                            $colorClasses = match ($deptKey) {
                                'human_resources' => [
                                    'active' =>
                                        'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border-green-300 shadow-md',
                                    'inactive' =>
                                        'bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 hover:from-green-100 hover:to-emerald-100 border-green-200',
                                    'badge_active' => 'bg-green-200 text-green-900',
                                    'badge_inactive' => 'bg-white/80 text-green-700',
                                ],
                                'ict' => [
                                    'active' =>
                                        'bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 border-blue-300 shadow-md',
                                    'inactive' =>
                                        'bg-gradient-to-r from-blue-50 to-cyan-50 text-blue-700 hover:from-blue-100 hover:to-cyan-100 border-blue-200',
                                    'badge_active' => 'bg-blue-200 text-blue-900',
                                    'badge_inactive' => 'bg-white/80 text-blue-700',
                                ],
                                'internal_audit' => [
                                    'active' =>
                                        'bg-gradient-to-r from-red-100 to-orange-100 text-red-800 border-red-300 shadow-md',
                                    'inactive' =>
                                        'bg-gradient-to-r from-red-50 to-orange-50 text-red-700 hover:from-red-100 hover:to-orange-100 border-red-200',
                                    'badge_active' => 'bg-red-200 text-red-900',
                                    'badge_inactive' => 'bg-white/80 text-red-700',
                                ],
                                'legal' => [
                                    'active' =>
                                        'bg-gradient-to-r from-violet-100 to-purple-100 text-violet-800 border-violet-300 shadow-md',
                                    'inactive' =>
                                        'bg-gradient-to-r from-violet-50 to-purple-50 text-violet-700 hover:from-violet-100 hover:to-purple-100 border-violet-200',
                                    'badge_active' => 'bg-violet-200 text-violet-900',
                                    'badge_inactive' => 'bg-white/80 text-violet-700',
                                ],
                                'procurement' => [
                                    'active' =>
                                        'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border-yellow-300 shadow-md',
                                    'inactive' =>
                                        'bg-gradient-to-r from-yellow-50 to-amber-50 text-yellow-700 hover:from-yellow-100 hover:to-amber-100 border-yellow-200',
                                    'badge_active' => 'bg-yellow-200 text-yellow-900',
                                    'badge_inactive' => 'bg-white/80 text-yellow-700',
                                ],
                                'drira' => [
                                    'active' =>
                                        'bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-800 border-teal-300 shadow-md',
                                    'inactive' =>
                                        'bg-gradient-to-r from-teal-50 to-cyan-50 text-teal-700 hover:from-teal-100 hover:to-cyan-100 border-teal-200',
                                    'badge_active' => 'bg-teal-200 text-teal-900',
                                    'badge_inactive' => 'bg-white/80 text-teal-700',
                                ],
                                'drcp' => [
                                    'active' =>
                                        'bg-gradient-to-r from-cyan-100 to-sky-100 text-cyan-800 border-cyan-300 shadow-md',
                                    'inactive' =>
                                        'bg-gradient-to-r from-cyan-50 to-sky-50 text-cyan-700 hover:from-cyan-100 hover:to-sky-100 border-cyan-200',
                                    'badge_active' => 'bg-cyan-200 text-cyan-900',
                                    'badge_inactive' => 'bg-white/80 text-cyan-700',
                                ],
                                'public_relations' => [
                                    'active' =>
                                        'bg-gradient-to-r from-pink-100 to-rose-100 text-pink-800 border-pink-300 shadow-md',
                                    'inactive' =>
                                        'bg-gradient-to-r from-pink-50 to-rose-50 text-pink-700 hover:from-pink-100 hover:to-rose-100 border-pink-200',
                                    'badge_active' => 'bg-pink-200 text-pink-900',
                                    'badge_inactive' => 'bg-white/80 text-pink-700',
                                ],
                                'finance' => [
                                    'active' =>
                                        'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border-emerald-300 shadow-md',
                                    'inactive' =>
                                        'bg-gradient-to-r from-emerald-50 to-green-50 text-emerald-700 hover:from-emerald-100 hover:to-green-100 border-emerald-200',
                                    'badge_active' => 'bg-emerald-200 text-emerald-900',
                                    'badge_inactive' => 'bg-white/80 text-emerald-700',
                                ],
                                'planning' => [
                                    'active' =>
                                        'bg-gradient-to-r from-indigo-100 to-blue-100 text-indigo-800 border-indigo-300 shadow-md',
                                    'inactive' =>
                                        'bg-gradient-to-r from-indigo-50 to-blue-50 text-indigo-700 hover:from-indigo-100 hover:to-blue-100 border-indigo-200',
                                    'badge_active' => 'bg-indigo-200 text-indigo-900',
                                    'badge_inactive' => 'bg-white/80 text-indigo-700',
                                ],
                                default => [
                                    'active' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'inactive' => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                                    'badge_active' => 'bg-blue-200 text-blue-900',
                                    'badge_inactive' => 'bg-white/80 text-gray-600',
                                ],
                            };
                            $isActive = request('category') === $deptKey;
                        @endphp
                        <a href="{{ route('documents.index', ['category' => $deptKey]) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $isActive ? $colorClasses['active'] : $colorClasses['inactive'] }} border">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ $deptName }}
                            <span
                                class="ml-2 {{ $isActive ? $colorClasses['badge_active'] : $colorClasses['badge_inactive'] }} px-2 py-0.5 rounded-full text-xs font-semibold">
                                {{ $categoryCounts[$deptKey] ?? 0 }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Filter & Search (advanced) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Filter & Search</h3>
                        <span class="text-sm text-gray-500">{{ $documents->total() }} document(s) found</span>
                    </div>
                    <form method="GET" action="{{ route('documents.index') }}" class="space-y-4" data-auto-submit>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div class="md:col-span-2">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search by title, description, or file name..."
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                            <!-- Access Level -->
                            <div>
                                <select name="access_level"
                                    class="w-full py-2.5 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">All access levels</option>
                                    @foreach ($accessLevels as $level)
                                        <option value="{{ $level }}"
                                            {{ request('access_level') === $level ? 'selected' : '' }}>
                                            {{ ucfirst($level) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Tag -->
                            <div>
                                <select name="tag"
                                    class="w-full py-2.5 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">All tags</option>
                                    @foreach ($allTags as $tag)
                                        <option value="{{ $tag }}"
                                            {{ request('tag') === $tag ? 'selected' : '' }}>{{ $tag }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                                </svg>
                                Apply Filters
                            </button>
                            <a href="{{ route('documents.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Clear All
                            </a>
                            @if (request()->hasAny(['search', 'access_level', 'tag']))
                                <span class="text-sm text-gray-500">Filtering active</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Documents List View -->
            @if ($documents->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Documents
                                @if (request('category'))
                                    <span class="text-sm font-normal text-gray-500">in
                                        {{ ucfirst(request('category')) }}</span>
                                @endif
                            </h2>
                            <span class="text-sm text-gray-500">{{ $documents->total() }} document(s) found</span>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach ($documents as $document)
                            <div class="group hover:bg-gray-50 transition-colors duration-200">
                                <div class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-start space-x-4 flex-1">
                                            <!-- File Type Icon -->
                                            <div class="flex-shrink-0">
                                                @php
                                                    $extension = strtolower(
                                                        pathinfo($document->file_path ?? '', PATHINFO_EXTENSION),
                                                    );
                                                    $iconColor = match ($extension) {
                                                        'pdf' => 'text-red-600 bg-red-100',
                                                        'doc', 'docx' => 'text-blue-600 bg-blue-100',
                                                        'xls', 'xlsx' => 'text-green-600 bg-green-100',
                                                        'ppt', 'pptx' => 'text-orange-600 bg-orange-100',
                                                        'txt' => 'text-gray-600 bg-gray-100',
                                                        default => 'text-purple-600 bg-purple-100',
                                                    };
                                                @endphp
                                                <div
                                                    class="w-12 h-12 rounded-lg flex items-center justify-center {{ $iconColor }}">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <!-- Document Info -->
                                            <div class="flex-1 min-w-0">
                                                <h4
                                                    class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-1">
                                                    {{ $document->title ?? 'Untitled Document' }}</h4>
                                                @if ($document->description ?? false)
                                                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                                        {{ Str::limit($document->description, 150) }}</p>
                                                @endif
                                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                    <div class="flex items-center space-x-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z" />
                                                        </svg>
                                                        <span>{{ $document->category_name ?? 'Unknown' }}</span>
                                                    </div>
                                                    <div class="flex items-center space-x-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>{{ $document->created_at->format('M j, Y') }}</span>
                                                    </div>
                                                    @if ($document->file_size ?? false)
                                                        <div class="flex items-center space-x-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                            </svg>
                                                            <span>{{ number_format($document->file_size / 1024, 1) }}
                                                                KB</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Action Buttons -->
                                        <div class="flex items-center space-x-3">
                                            @php $extension = strtolower(pathinfo($document->file_path ?? '', PATHINFO_EXTENSION)); @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ strtoupper($extension) }}</span>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('documents.show', $document) }}"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
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
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
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
                        @endforeach
                    </div>
                    <!-- Pagination -->
                    @if ($documents->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            {{ $documents->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if (request('category'))
                            No {{ request('category') }} documents found
                        @else
                            No documents found
                        @endif
                    </h3>
                    <p class="text-gray-500 mb-4">
                        @if (request('category'))
                            Try selecting a different department or check back later for new documents.
                        @else
                            Documents will appear here once they are uploaded by administrators.
                        @endif
                    </p>
                    <a href="{{ route('documents.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        View All Documents
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
