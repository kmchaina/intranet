@extends('layouts.dashboard')
@section('title', 'News Feed')

@section('content')
    <div class="space-y-6">
        <x-breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'News Feed']]" />

        <!-- Header Card -->
        <div class="card-premium overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-8 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">News Feed</h1>
                            <p class="text-white/90 mt-1">Stay updated with the latest news from NIMR</p>
                        </div>
                    </div>
                    @can('create', App\Models\News::class)
                        <a href="{{ route('news.create') }}" class="btn btn-ghost text-white hover:bg-white/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create News
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- News Grid -->
        @if ($news->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($news as $article)
                    <article
                        class="card-premium overflow-hidden group hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <!-- Featured Image -->
                        @if ($article && $article->featured_image)
                            <div class="relative overflow-hidden h-48">
                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent">
                                </div>
                                <div class="absolute bottom-3 left-3">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-white/90 text-blue-700 backdrop-blur-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        {{ $article->location ?? 'NIMR' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div
                                class="h-48 bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                        @endif

                        <!-- Card Content -->
                        <div class="p-6">
                            <h3
                                class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('news.show', $article) }}">{{ $article->title }}</a>
                            </h3>

                            <p class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                {{ $article->excerpt ?? Str::limit(strip_tags($article->content ?? ''), 150) }}
                            </p>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $article->published_at ? $article->published_at->diffForHumans() : $article->created_at->diffForHumans() }}</span>
                                </div>
                                <a href="{{ route('news.show', $article) }}"
                                    class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                    Read more
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($news->hasPages())
                <div class="card-premium p-6">
                    {{ $news->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="card-premium p-12">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <p class="empty-state-title">No news articles found</p>
                    <p class="empty-state-description">There are currently no published news articles to display.</p>
                    @can('create', App\Models\News::class)
                        <a href="{{ route('news.create') }}" class="btn btn-primary mt-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create First Article
                        </a>
                    @endcan
                </div>
            </div>
        @endif
    </div>
@endsection
