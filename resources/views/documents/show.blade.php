@extends('layouts.dashboard')

@section('title', $document->title)
@section('page-title', $document->title)
@section('page-subtitle', 'Document Details')

@section('content')
    <div class="p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Header with Actions -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                <div class="flex items-center mb-4 lg:mb-0">
                    <a href="{{ route('documents.index') }}"
                        class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors mr-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Documents
                    </a>
                </div>

                <div class="flex flex-wrap gap-3">
                    <!-- Download Button -->
                    @if (!$document->requires_download_permission || auth()->user()->canDownloadDocument($document))
                        <a href="{{ route('documents.download', $document) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download
                        </a>
                    @else
                        <button disabled
                            class="inline-flex items-center px-4 py-2 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed opacity-60">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m0 0v2m0-2h2m-2 0h-2m9-12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Permission Required
                        </button>
                    @endif

                    <!-- Edit Button (if authorized) -->
                    @if (auth()->user()->canUpdateDocument($document))
                        <a href="{{ route('documents.edit', $document) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                    @endif

                    <!-- Delete Button (if authorized) -->
                    @if (auth()->user()->canDeleteDocument($document))
                        <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this document? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="xl:col-span-2 space-y-8">
                    <!-- Compact Document Header -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-start space-x-4">
                                <!-- File Icon -->
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                        @php
                                            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                                            $iconClass = match (strtolower($extension)) {
                                                'pdf' => 'text-red-500',
                                                'doc', 'docx' => 'text-blue-500',
                                                'xls', 'xlsx' => 'text-green-500',
                                                'ppt', 'pptx' => 'text-orange-500',
                                                'jpg', 'jpeg', 'png', 'gif' => 'text-purple-500',
                                                'mp4', 'avi', 'mov' => 'text-pink-500',
                                                'mp3', 'wav' => 'text-yellow-500',
                                                'zip', 'rar' => 'text-gray-500',
                                                default => 'text-gray-400',
                                            };
                                        @endphp
                                        <svg class="w-6 h-6 {{ $iconClass }}" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Document Info -->
                                <div class="flex-1 min-w-0">
                                    <h1 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $document->title }}
                                    </h1>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ strtoupper($extension) }}
                                        Document • {{ $document->getFileSize() }}</p>

                                    @if ($document->description)
                                        <p class="text-gray-700 dark:text-gray-300 text-sm line-clamp-2 mb-2">
                                            {{ $document->description }}</p>
                                    @endif

                                    <!-- Tags -->
                                    @if ($document->tags)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach (array_slice(explode(',', $document->tags), 0, 3) as $tag)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                                    #{{ trim($tag) }}
                                                </span>
                                            @endforeach
                                            @if (count(explode(',', $document->tags)) > 3)
                                                <span class="text-xs text-gray-500 dark:text-gray-400 px-1">
                                                    +{{ count(explode(',', $document->tags)) - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> <!-- Document Preview -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Document Preview</h3>
                        </div>

                        <div class="p-6">
                            @php
                                $extension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                                $fileUrl = \Illuminate\Support\Facades\Storage::url($document->file_path);
                            @endphp

                            @if (in_array($extension, ['pdf']))
                                <!-- PDF Preview -->
                                <div class="w-full" style="height: 600px;">
                                    <iframe src="{{ $fileUrl }}"
                                        class="w-full h-full border border-gray-300 dark:border-gray-600 rounded-lg"
                                        type="application/pdf">
                                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">
                                            Your browser doesn't support PDF preview.
                                            <a href="{{ route('documents.download', $document) }}"
                                                class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                                Download the file
                                            </a> instead.
                                        </p>
                                    </iframe>
                                </div>
                            @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                                <!-- Image Preview -->
                                <div class="text-center">
                                    <img src="{{ $fileUrl }}" alt="{{ $document->title }}"
                                        class="max-w-full h-auto rounded-lg shadow-lg mx-auto" style="max-height: 600px;">
                                </div>
                            @elseif(in_array($extension, ['txt', 'md', 'log']))
                                <!-- Text File Preview -->
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 overflow-auto"
                                    style="max-height: 600px;">
                                    <pre class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ \Illuminate\Support\Facades\Storage::get($document->file_path) }}</pre>
                                </div>
                            @elseif(in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'webm']))
                                <!-- Video Preview -->
                                <div class="text-center">
                                    <video controls class="max-w-full h-auto rounded-lg shadow-lg mx-auto"
                                        style="max-height: 600px;">
                                        <source src="{{ $fileUrl }}" type="video/{{ $extension }}">
                                        Your browser does not support the video tag.
                                        <a href="{{ route('documents.download', $document) }}"
                                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                            Download the file
                                        </a> instead.
                                    </video>
                                </div>
                            @elseif(in_array($extension, ['mp3', 'wav', 'ogg', 'm4a']))
                                <!-- Audio Preview -->
                                <div class="text-center">
                                    <audio controls class="w-full max-w-md mx-auto">
                                        <source src="{{ $fileUrl }}" type="audio/{{ $extension }}">
                                        Your browser does not support the audio element.
                                        <a href="{{ route('documents.download', $document) }}"
                                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                            Download the file
                                        </a> instead.
                                    </audio>
                                </div>
                            @elseif(in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']))
                                <!-- Office Documents Preview (using Google Docs Viewer) -->
                                <div class="w-full" style="height: 600px;">
                                    <iframe
                                        src="https://docs.google.com/viewer?url={{ urlencode(request()->getSchemeAndHttpHost() . $fileUrl) }}&embedded=true"
                                        class="w-full h-full border border-gray-300 dark:border-gray-600 rounded-lg">
                                        <div class="text-center py-8">
                                            <p class="text-gray-500 dark:text-gray-400 mb-4">
                                                Preview not available for this document type.
                                            </p>
                                            <a href="{{ route('documents.download', $document) }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Download Document
                                            </a>
                                        </div>
                                    </iframe>
                                </div>
                            @else
                                <!-- Unsupported File Type -->
                                <div class="text-center py-12">
                                    <div
                                        class="w-20 h-20 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Preview Not
                                        Available</h4>
                                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                                        This file type ({{ strtoupper($extension) }}) cannot be previewed in the browser.
                                    </p>
                                    <a href="{{ route('documents.download', $document) }}"
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Download Document
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div> <!-- Comments Section (Future Feature) -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Comments</h3>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Comments feature coming soon</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Document Properties -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Document Properties</h3>

                        <div class="space-y-4">
                            <!-- Category -->
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Category</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white capitalize">{{ $document->category }}</span>
                            </div>

                            <!-- Access Level -->
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Access Level</span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                    {{ $document->access_level === 'public' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                    {{ $document->access_level === 'restricted' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}
                                    {{ $document->access_level === 'confidential' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : '' }}">
                                    {{ ucfirst($document->access_level) }}
                                </span>
                            </div>

                            <!-- Visibility -->
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Visibility</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' ', $document->visibility_scope) }}</span>
                            </div>

                            <!-- Version -->
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Version</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $document->version }}</span>
                            </div>

                            <!-- File Size -->
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">File Size</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $document->getFileSize() }}</span>
                            </div>

                            @if ($document->expires_at)
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Expires</span>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white">{{ $document->expires_at->format('M j, Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Information -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Upload Information</h3>

                        <div class="space-y-4">
                            <!-- Uploaded By -->
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-blue-600 dark:text-blue-400 font-semibold text-sm">
                                        {{ substr($document->uploader->name, 0, 2) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $document->uploader->name }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $document->uploader->email }}
                                    </p>
                                </div>
                            </div>

                            <!-- Upload Date -->
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Uploaded</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $document->created_at->format('M j, Y') }}</span>
                            </div>

                            <!-- Last Modified -->
                            @if ($document->updated_at != $document->created_at)
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Modified</span>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white">{{ $document->updated_at->format('M j, Y') }}</span>
                                </div>
                            @endif

                            <!-- Download Count -->
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Downloads</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $document->download_count }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>

                        <div class="space-y-3">
                            <!-- Share Document -->
                            <button onclick="copyShareLink()"
                                class="w-full flex items-center px-4 py-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Copy Share Link</span>
                            </button>

                            <!-- Print Document Info -->
                            <button onclick="window.print()"
                                class="w-full flex items-center px-4 py-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Print Info</span>
                            </button>

                            <!-- Report Issue -->
                            <button
                                class="w-full flex items-center px-4 py-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Report Issue</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function copyShareLink() {
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(function() {
                    // Show success message (you can integrate with your notification system)
                    alert('Document link copied to clipboard!');
                }, function(err) {
                    console.error('Could not copy text: ', err);
                });
            }
        </script>
    @endpush
@endsection
