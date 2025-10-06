@extends('layouts.dashboard')
@section('title', $announcement->title)

@section('page-title')
    <div class="animate-fade-in">
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Announcements', 'href' => route('announcements.index')],
            ['label' => Str::limit($announcement->title, 50)],
        ]" />
    </div>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Main Announcement Card --}}
        <article class="card-premium overflow-hidden">
            {{-- Header Banner --}}
            <div
                class="bg-gradient-to-r @if ($announcement->priority === 'urgent') from-red-500 to-red-600 @elseif($announcement->priority === 'high') from-orange-500 to-orange-600 @elseif($announcement->priority === 'medium') from-nimr-primary-500 to-nimr-primary-600 @else from-nimr-neutral-400 to-nimr-neutral-500 @endif p-6 text-white">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span
                                class="inline-flex px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold uppercase">
                                {{ $announcement->category }}
                            </span>
                            <span
                                class="inline-flex px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold uppercase">
                                {{ $announcement->priority }} Priority
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold mb-4">{{ $announcement->title }}</h1>

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-white/90">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-medium">{{ $announcement->creator->name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $announcement->published_at ? $announcement->published_at->format('M j, Y \a\t g:i A') : $announcement->created_at->format('M j, Y \a\t g:i A') }}</span>
                            </div>
                            @if ($announcement->target_scope !== 'all')
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    <span class="capitalize">{{ str_replace('_', ' ', $announcement->target_scope) }}</span>
                                </div>
                            @endif
                            @if ($announcement->expires_at)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Expires {{ $announcement->expires_at->diffForHumans() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    @if (auth()->user()->can('update', $announcement) || auth()->user()->can('delete', $announcement))
                        <div class="flex flex-col gap-2 shrink-0">
                            @can('update', $announcement)
                                <a href="{{ route('announcements.edit', $announcement) }}"
                                    class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-medium rounded-lg transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                            @endcan
                            @can('delete', $announcement)
                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-white/20 hover:bg-red-600 backdrop-blur-sm text-white font-medium rounded-lg transition-all">
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
            </div>

            {{-- Content --}}
            <div class="p-8">
                <div class="prose prose-lg max-w-none text-nimr-neutral-800 leading-relaxed">
                    {!! nl2br(e($announcement->content)) !!}
                </div>
            </div>

            {{-- Attachments --}}
            @if ($announcement->attachments->count() > 0)
                {{-- Document Preview Section --}}
                <div class="px-8 pb-8">
                    <div class="border-t border-nimr-neutral-200 pt-6">
                        <h3 class="text-lg font-bold text-nimr-neutral-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-nimr-primary-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            Document Preview
                        </h3>

                        {{-- Document Preview Area --}}
                        <div id="documentPreview"
                            class="w-full h-[600px] border border-nimr-neutral-200 rounded-lg overflow-hidden mb-6">
                            {{-- Document will be loaded here automatically --}}
                        </div>

                        {{-- Attachment List --}}
                        <div>
                            <h4 class="text-sm font-semibold text-nimr-neutral-700 mb-3">Available Documents
                                ({{ $announcement->attachments->count() }})</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($announcement->attachments as $index => $attachment)
                                    <div class="group relative">
                                        <div class="attachment-item flex items-center gap-3 p-3 rounded-lg border border-nimr-neutral-200 hover:border-nimr-primary-400 hover:bg-nimr-primary-50 transition-all duration-200 cursor-pointer {{ $index === 0 ? 'border-nimr-primary-400 bg-nimr-primary-50' : '' }}"
                                            onclick="loadDocument('{{ $attachment->file_path }}', '{{ $attachment->original_name }}', '{{ $attachment->mime_type }}', {{ $index }})">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-nimr-primary-500 to-nimr-primary-600 flex items-center justify-center">
                                                    @if ($attachment->isImage())
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    @elseif(str_contains($attachment->mime_type, 'pdf'))
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-white" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-nimr-neutral-900 truncate">
                                                    {{ $attachment->original_name }}
                                                </p>
                                                <p class="text-xs text-nimr-neutral-500">{{ $attachment->formatted_size }}
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('announcements.download-attachment', $attachment) }}"
                                                    class="p-1 bg-green-500 hover:bg-green-600 text-white rounded transition-colors"
                                                    title="Download" onclick="event.stopPropagation()">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Footer Stats --}}
            <div class="px-8 pb-6">
                <div class="flex items-center justify-between pt-6 border-t border-nimr-neutral-200">
                    <div class="flex items-center gap-6 text-sm text-nimr-neutral-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="font-medium">{{ $announcement->views_count }}
                                {{ Str::plural('view', $announcement->views_count) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Last updated {{ $announcement->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <a href="{{ route('announcements.index') }}"
                        class="inline-flex items-center text-sm font-medium text-nimr-primary-600 hover:text-nimr-primary-700 hover:underline">
                        ← Back to Announcements
                    </a>
                </div>
            </div>
        </article>

    </div>


    @push('scripts')
        <script>
            // Auto-load first PDF when page loads
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded, checking for attachments...');
                @if ($announcement->attachments->count() > 0)
                    const attachments = @json($announcement->attachments);
                    console.log('All attachments:', attachments);

                    // Find first PDF attachment
                    const firstPdf = attachments.find(attachment => attachment.mime_type === 'application/pdf');

                    if (firstPdf) {
                        console.log('First PDF found:', firstPdf);
                        const pdfIndex = attachments.findIndex(attachment => attachment.id === firstPdf.id);
                        loadDocument(firstPdf.file_path, firstPdf.original_name, firstPdf.mime_type, pdfIndex);
                    } else {
                        console.log('No PDF found, showing first attachment as download-only');
                        const firstAttachment = attachments[0];
                        loadDocument(firstAttachment.file_path, firstAttachment.original_name, firstAttachment
                            .mime_type, 0);
                    }
                @else
                    console.log('No attachments found');
                @endif
            });

            function loadDocument(filePath, fileName, mimeType, index) {
                console.log('loadDocument called with:', {
                    filePath,
                    fileName,
                    mimeType,
                    index
                });
                const previewArea = document.getElementById('documentPreview');

                if (!previewArea) {
                    console.error('Preview area not found!');
                    return;
                }

                // Update active document in the list
                document.querySelectorAll('.attachment-item').forEach((item, i) => {
                    if (i === index) {
                        item.classList.add('border-nimr-primary-400', 'bg-nimr-primary-50');
                    } else {
                        item.classList.remove('border-nimr-primary-400', 'bg-nimr-primary-50');
                    }
                });

                // Show loading
                previewArea.innerHTML = `
                    <div class="flex items-center justify-center h-full bg-nimr-neutral-50">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-nimr-primary-600 mx-auto mb-4"></div>
                            <p class="text-nimr-neutral-600">Loading ${fileName}...</p>
                        </div>
                    </div>
                `;

                // Handle different file types - PDFs only for preview
                if (mimeType === 'application/pdf') {
                    console.log('Loading PDF:', `/storage/${filePath}`);
                    previewArea.innerHTML = `
                        <div class="w-full h-full">
                            <iframe src="/storage/${filePath}#toolbar=0" class="w-full h-full border-0 rounded-lg" onerror="console.error('Failed to load PDF:', this.src)"></iframe>
                        </div>
                    `;
                } else {
                    console.log('Non-PDF file type:', mimeType, '- showing download option');
                    previewArea.innerHTML = `
                        <div class="flex items-center justify-center h-full bg-nimr-neutral-50">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-nimr-neutral-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <p class="text-nimr-neutral-600 mb-4">Preview available for PDFs only</p>
                                <p class="text-sm text-nimr-neutral-500 mb-4">Click download to view this file</p>
                                <p class="text-xs text-nimr-neutral-400">File: ${fileName}</p>
                            </div>
                        </div>
                    `;
                }
            }
        </script>
    @endpush

@endsection
