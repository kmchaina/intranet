@extends('layouts.dashboard')

@section('title', $announcement->title)
@section('page-title', $announcement->title)
@section('page-subtitle', 'Published by ' . $announcement->creator->name . ' on ' .
    $announcement->published_at->format('M j, Y'))

    @php
        use Illuminate\Support\Facades\Storage;
    @endphp

@section('content')
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="bg-white/95 backdrop-blur-md rounded-xl border border-gray-200 shadow-lg p-6 mb-6">
                <a href="{{ route('announcements.index') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Announcements
                </a>
            </div>

            <!-- Announcement Content -->
            <article class="bg-white/95 backdrop-blur-md rounded-xl border border-gray-200 shadow-lg p-8 lg:p-12">
                <!-- Header -->
                <header class="mb-8 pb-6 border-b border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
                        <div class="flex items-start space-x-4">
                            <!-- Priority Icon -->
                            <div
                                class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg
                            @if ($announcement->priority === 'high') icon-gradient-red
                            @elseif($announcement->priority === 'medium') icon-gradient-yellow  
                            @else icon-gradient-green @endif">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if ($announcement->priority === 'high')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    @elseif($announcement->priority === 'medium')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @endif
                                </svg>
                            </div>

                            <div>
                                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white leading-tight">
                                    {{ $announcement->title }}
                                </h1>
                            </div>
                        </div>

                        <!-- Actions -->
                        @if (auth()->user()->can('update', $announcement) || auth()->user()->can('delete', $announcement))
                            <div class="flex items-center space-x-3">
                                @can('update', $announcement)
                                    <a href="{{ route('announcements.edit', $announcement) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                @endcan

                                @can('delete', $announcement)
                                    <form action="{{ route('announcements.destroy', $announcement) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @endif
                    </div>

                    <!-- Meta Information -->
                    <div class="flex flex-wrap items-center gap-6 text-sm">
                        <!-- Category Badge -->
                        <span
                            class="inline-flex px-4 py-2 rounded-full font-medium
                        @if ($announcement->category === 'urgent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                        @elseif($announcement->category === 'event') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                        @elseif($announcement->category === 'policy') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                        @elseif($announcement->category === 'training') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                            {{ ucfirst($announcement->category) }}
                        </span>

                        <!-- Priority Badge -->
                        <span
                            class="inline-flex px-4 py-2 rounded-full font-medium
                        @if ($announcement->priority === 'high') bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300
                        @elseif($announcement->priority === 'medium') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300
                        @else bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 @endif">
                            {{ ucfirst($announcement->priority) }} Priority
                        </span>

                        <!-- Author -->
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-medium">{{ $announcement->creator->name }}</span>
                        </div>

                        <!-- Date -->
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $announcement->published_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>

                        <!-- Expiry Date -->
                        @if ($announcement->expires_at)
                            <div class="flex items-center text-orange-600 dark:text-orange-400">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Expires {{ $announcement->expires_at->format('M j, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </header>

                <!-- Content -->
                <div class="prose prose-lg dark:prose-invert max-w-none">
                    {!! nl2br(e($announcement->content)) !!}
                </div>

                <!-- Attachments -->
                @if ($announcement->attachments->count() > 0)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            Attachments ({{ $announcement->attachments->count() }})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($announcement->attachments as $attachment)
                                <a href="{{ route('announcements.download-attachment', $attachment) }}"
                                    class="flex items-center p-4 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition-all duration-200 group">
                                    <div class="flex-shrink-0 mr-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
                                            <i
                                                class="{{ $attachment->getFileIcon() }} text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate">
                                            {{ $attachment->original_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $attachment->getFormattedSize() }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 ml-2">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Target Audience Info -->
                @if ($announcement->target_scope !== 'all')
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Target Audience</h3>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-blue-800 dark:text-blue-200">
                                    @switch($announcement->target_scope)
                                        @case('headquarters')
                                            This announcement is targeted to NIMR Headquarters staff only.
                                        @break

                                        @case('my_centre')
                                            This announcement is targeted to staff in the same centre as the author.
                                        @break

                                        @case('my_centre_stations')
                                            This announcement is targeted to staff in the same centre and its stations.
                                        @break

                                        @case('my_station')
                                            This announcement is targeted to staff in the same station as the author.
                                        @break

                                        @case('all_centres')
                                            This announcement is targeted to all research centres.
                                        @break

                                        @case('all_stations')
                                            This announcement is targeted to all research stations.
                                        @break

                                        @case('specific')
                                            This announcement is targeted to specific centres and/or stations.
                                        @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Views Counter -->
                <div class="mt-8 pt-6 border-t border-white/20">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>{{ $announcement->views_count }}
                            {{ Str::plural('view', $announcement->views_count) }}</span>
                    </div>
                </div>
            </article>
        </div>
    </div>

    @push('scripts')
        <script>
            // Mark announcement as read when page loads
            document.addEventListener('DOMContentLoaded', function() {
                fetch(`{{ route('announcements.mark-read', $announcement) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                    }
                }).catch(console.error);
            });
        </script>
    @endpush
@endsection
