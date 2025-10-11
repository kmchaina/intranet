@extends('layouts.dashboard')
@section('title', $news->title)

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Breadcrumbs --}}
        <div class="animate-fade-in">
            <x-breadcrumbs :items="[
                ['label' => 'Dashboard', 'href' => route('dashboard')],
                ['label' => 'News Feed', 'href' => route('news.index')],
                ['label' => Str::limit($news->title, 50)],
            ]" />
        </div>

        {{-- Main Article Card --}}
        <article class="card-premium overflow-hidden">

            {{-- Featured Image --}}
            @if ($news && $news->featured_image)
                <div class="relative h-96 overflow-hidden">
                    <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                </div>
            @endif

            {{-- Article Content --}}
            <div class="p-8">

                {{-- Meta Information --}}
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $news->location ?? 'NIMR' }}
                    </span>

                    @if ($news->is_featured)
                        <span
                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            Featured
                        </span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="text-4xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $news->title }}
                </h1>

                {{-- Author and Date Information --}}
                <div
                    class="flex flex-wrap items-center justify-between text-sm text-gray-600 mb-8 pb-6 border-b border-gray-200">
                    <div class="flex flex-wrap items-center gap-6">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <div class="font-medium text-gray-900">
                                    {{ $news->published_at ? $news->published_at->format('M j, Y') : $news->created_at->format('M j, Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $news->published_at ? $news->published_at->format('g:i A') : $news->created_at->format('g:i A') }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <div>
                                <div class="font-medium text-gray-900">
                                    {{ $news->views_count ?? 0 }} {{ Str::plural('view', $news->views_count ?? 0) }}
                                </div>
                                <div class="text-xs text-gray-500">Total views</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 mt-4 sm:mt-0">
                        Last updated: {{ $news->updated_at->diffForHumans() }}
                    </div>
                </div>

                {{-- Content --}}
                <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed mb-8">
                    {!! nl2br(e($news->content ?? 'No content available')) !!}
                </div>

                {{-- Tags --}}
                @if (isset($news->tags) && is_array($news->tags) && count($news->tags) > 0)
                    <div class="mb-8 pb-6 border-b border-gray-200">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-gray-700 mr-2">Tags:</span>
                            @foreach ($news->tags as $tag)
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Attachments --}}
            @if (isset($news->attachments) && $news->attachments->count() > 0)
                <div class="px-8 pb-8">
                    <div class="border-t border-gray-200 pt-6">

                        @php
                            $images = $news->attachments->filter(fn($a) => $a->isImage());
                            $documents = $news->attachments->filter(fn($a) => !$a->isImage());
                        @endphp

                        {{-- Image Gallery --}}
                        @if ($images->count() > 0)
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Images ({{ $images->count() }})
                                </h3>

                                @if ($images->count() === 1)
                                    {{-- Single image - large display --}}
                                    @php $image = $images->first(); @endphp
                                    <div class="rounded-lg overflow-hidden border border-gray-200 cursor-pointer hover:border-blue-400 transition-colors"
                                        onclick="openImageModal('{{ asset('storage/' . $image->file_path) }}', '{{ $image->original_name }}')">
                                        <img src="{{ asset('storage/' . $image->file_path) }}"
                                            alt="{{ $image->original_name }}"
                                            class="w-full h-auto max-h-[600px] object-contain bg-gray-50">
                                    </div>
                                @else
                                    {{-- Multiple images - grid layout --}}
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach ($images as $image)
                                            <div class="group relative aspect-square rounded-lg overflow-hidden border border-gray-200 cursor-pointer hover:border-blue-400 transition-colors"
                                                onclick="openImageModal('{{ asset('storage/' . $image->file_path) }}', '{{ $image->original_name }}')">
                                                <img src="{{ asset('storage/' . $image->file_path) }}"
                                                    alt="{{ $image->original_name }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                <div
                                                    class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Documents Section --}}
                        @if ($documents->count() > 0)
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Documents ({{ $documents->count() }})
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($documents as $attachment)
                                        <a href="{{ route('news.download-attachment', $attachment) }}"
                                            class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-all">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $attachment->original_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $attachment->formatted_size }}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

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
        </article>

        {{-- Related News --}}
        @if (isset($relatedNews) && $relatedNews->count() > 0)
            <div class="mt-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Related News</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($relatedNews as $related)
                        <article class="card-premium group hover:-translate-y-1 transition-all duration-300">
                            @if ($related && is_object($related) && $related->featured_image)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ asset('storage/' . $related->featured_image) }}"
                                        alt="{{ $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @endif

                            <div class="p-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-2">
                                    {{ $related && is_object($related) ? $related->location ?? 'NIMR' : 'NIMR' }}
                                </span>

                                <h4
                                    class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    <a href="{{ route('news.show', $related) }}">
                                        {{ $related && is_object($related) ? $related->title : 'Related News' }}
                                    </a>
                                </h4>

                                @if ($related && is_object($related) && isset($related->excerpt))
                                    <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                        {{ $related->excerpt }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>
                                        {{ $related && is_object($related) && $related->published_at ? $related->published_at->diffForHumans() : 'Recently' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ $related && is_object($related) ? $related->views_count ?? 0 : 0 }}
                                    </span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Back Button --}}
        <div class="flex justify-center">
            <a href="{{ route('news.index') }}"
                class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">
                ← Back to News Feed
            </a>
        </div>

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

            // Close modal on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageModal();
                }
            });
        </script>
    @endpush

@endsection
