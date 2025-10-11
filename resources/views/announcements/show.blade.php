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
                class="bg-gradient-to-r @if ($announcement->priority === 'urgent') from-red-500 to-red-600 @elseif($announcement->priority === 'high') from-orange-500 to-orange-600 @elseif($announcement->priority === 'medium') from-blue-500 to-blue-600 @else from-gray-400 to-gray-500 @endif p-6 text-white">
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
                                    <span
                                        class="capitalize">{{ match ($announcement->target_scope) {
                                            'my_centre' => 'Centre Level',
                                            'my_station' => 'Station Level',
                                            'my_centre_stations' => 'Centre and Stations',
                                            'all_centres' => 'All Centres',
                                            'all_stations' => 'All Stations',
                                            'headquarters' => 'Headquarters',
                                            'specific' => 'Specific Locations',
                                            default => str_replace('_', ' ', $announcement->target_scope),
                                        } }}</span>
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
                </div>
            </div>

            {{-- Content and Attachments Layout --}}
            <div class="flex flex-col lg:flex-row gap-6 p-8">
                {{-- Main Content --}}
                <div class="flex-1 min-w-0">
                    <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>
                </div>

                {{-- Attachments Sidebar --}}
                @if ($announcement->attachments->count() > 0)
                    <div class="lg:w-80 flex-shrink-0">
                        <div class="sticky top-6">

                            @php
                                $images = $announcement->attachments->filter(fn($a) => $a->isImage());
                                $documents = $announcement->attachments->filter(fn($a) => !$a->isImage());
                            @endphp

                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <h3
                                    class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2 uppercase tracking-wide">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    Attachments ({{ $announcement->attachments->count() }})
                                </h3>

                                <div class="space-y-3">
                                    {{-- Images --}}
                                    @if ($images->count() > 0)
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">
                                                Images</p>
                                            <div class="space-y-2">
                                                @foreach ($images as $image)
                                                    <div class="group relative rounded-lg overflow-hidden border border-gray-200 cursor-pointer hover:border-blue-400 transition-colors"
                                                        onclick="openImageModal('{{ asset('storage/' . $image->file_path) }}', '{{ $image->original_name }}')">
                                                        <div class="aspect-video relative">
                                                            <img src="{{ asset('storage/' . $image->file_path) }}"
                                                                alt="{{ $image->original_name }}"
                                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                            <div
                                                                class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                                                <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="p-2 bg-white">
                                                            <p class="text-xs font-medium text-gray-900 truncate">
                                                                {{ $image->original_name }}
                                                            </p>
                                                            <p class="text-xs text-gray-500">{{ $image->formatted_size }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Documents --}}
                                    @if ($documents->count() > 0)
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">
                                                Documents</p>
                                            <div class="space-y-2">
                                                @foreach ($documents as $attachment)
                                                    <div class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-white hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 cursor-pointer"
                                                        onclick="openDocumentModal('{{ asset('storage/' . $attachment->file_path) }}', '{{ $attachment->original_name }}', '{{ $attachment->mime_type }}')">
                                                        <div class="flex-shrink-0">
                                                            <div
                                                                class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                                                @if (str_contains($attachment->mime_type, 'pdf'))
                                                                    <svg class="w-5 h-5 text-white" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                    </svg>
                                                                @elseif (str_contains($attachment->mime_type, 'word') || str_contains($attachment->mime_type, 'document'))
                                                                    <svg class="w-5 h-5 text-white" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                @elseif (str_contains($attachment->mime_type, 'excel') || str_contains($attachment->mime_type, 'spreadsheet'))
                                                                    <svg class="w-5 h-5 text-white" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-5 h-5 text-white" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                                {{ $attachment->original_name }}
                                                            </p>
                                                            <p class="text-xs text-gray-500">
                                                                {{ $attachment->formatted_size }}</p>
                                                        </div>
                                                        <div class="flex-shrink-0 flex gap-1">
                                                            <button type="button"
                                                                onclick="event.stopPropagation(); openDocumentModal('{{ asset('storage/' . $attachment->file_path) }}', '{{ $attachment->original_name }}', '{{ $attachment->mime_type }}')"
                                                                class="p-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors shadow-sm hover:shadow"
                                                                title="View">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                            </button>
                                                            <a href="{{ route('announcements.download-attachment', $attachment) }}"
                                                                onclick="event.stopPropagation()"
                                                                class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors shadow-sm hover:shadow"
                                                                title="Download">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Image Modal --}}
            <div id="imageModal" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4"
                onclick="closeImageModal()">
                <div class="relative max-w-7xl max-h-full">
                    <button onclick="closeImageModal()"
                        class="absolute top-4 right-4 text-white hover:text-gray-300 bg-black/50 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <img id="modalImage" src="" alt="" class="max-w-full max-h-[90vh] object-contain"
                        onclick="event.stopPropagation()">
                    <p id="modalCaption" class="text-white text-center mt-4 text-sm"></p>
                </div>
            </div>

            {{-- Document Preview Modal --}}
            <div id="documentModal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
                onclick="closeDocumentModal()">
                <div class="relative w-full max-w-6xl h-[90vh] bg-white rounded-xl shadow-2xl"
                    onclick="event.stopPropagation()">
                    {{-- Header --}}
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                        <div class="flex-1 min-w-0">
                            <h3 id="documentModalTitle" class="text-lg font-semibold text-gray-900 truncate"></h3>
                        </div>
                        <button onclick="closeDocumentModal()"
                            class="ml-4 text-gray-500 hover:text-gray-700 p-2 hover:bg-gray-200 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    {{-- Preview Area --}}
                    <div id="documentPreview" class="w-full h-[calc(90vh-4rem)]">
                        {{-- Document will be loaded here --}}
                    </div>
                </div>
            </div>

            {{-- Footer Stats --}}
            <div class="px-8 pb-6">
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-6 text-sm text-gray-600">
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
                        class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">
                        ← Back to Announcements
                    </a>
                </div>
            </div>
        </article>

    </div>


    @push('scripts')
        <script>
            // Image modal functions
            function openImageModal(imageSrc, imageAlt) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                const modalCaption = document.getElementById('modalCaption');

                modalImage.src = imageSrc;
                modalImage.alt = imageAlt;
                modalCaption.textContent = imageAlt;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Document modal functions
            function openDocumentModal(filePath, fileName, mimeType) {
                const modal = document.getElementById('documentModal');
                const title = document.getElementById('documentModalTitle');
                const previewArea = document.getElementById('documentPreview');

                title.textContent = fileName;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Show loading
                previewArea.innerHTML = `
                    <div class="flex items-center justify-center h-full bg-gray-50">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                            <p class="text-gray-600">Loading ${fileName}...</p>
                        </div>
                    </div>
                `;

                // Handle different file types
                if (mimeType === 'application/pdf') {
                    previewArea.innerHTML = `
                        <iframe src="${filePath}#toolbar=1" class="w-full h-full border-0"></iframe>
                    `;
                } else if (mimeType.includes('image/')) {
                    previewArea.innerHTML = `
                        <div class="flex items-center justify-center h-full bg-gray-900 p-4">
                            <img src="${filePath}" alt="${fileName}" class="max-w-full max-h-full object-contain">
                        </div>
                    `;
                } else if (mimeType.includes('text/') || mimeType.includes('json')) {
                    // Load text files
                    fetch(filePath)
                        .then(response => response.text())
                        .then(text => {
                            previewArea.innerHTML = `
                                <div class="h-full overflow-auto p-6 bg-gray-50">
                                    <pre class="whitespace-pre-wrap font-mono text-sm text-gray-800">${escapeHtml(text)}</pre>
                                </div>
                            `;
                        })
                        .catch(error => {
                            previewArea.innerHTML = `
                                <div class="flex items-center justify-center h-full bg-gray-50">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-600">Error loading file</p>
                                    </div>
                                </div>
                            `;
                        });
                } else {
                    previewArea.innerHTML = `
                        <div class="flex items-center justify-center h-full bg-gray-50">
                            <div class="text-center max-w-md">
                                <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-700 font-medium mb-2">${fileName}</p>
                                <p class="text-gray-500 text-sm mb-4">Preview not available for this file type</p>
                                <a href="${filePath}" download class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download File
                                </a>
                            </div>
                        </div>
                    `;
                }
            }

            function closeDocumentModal() {
                const modal = document.getElementById('documentModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Close modals on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageModal();
                    closeDocumentModal();
                }
            });
        </script>
    @endpush

@endsection
