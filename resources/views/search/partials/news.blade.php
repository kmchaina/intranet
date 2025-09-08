<div class="space-y-4">
    @foreach($items as $news)
        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <a href="{{ route('news.show', $news) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                {{ $news->title }}
                            </a>
                        </h3>
                        @if($news->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Featured
                            </span>
                        @endif
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($news->priority === 'high') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($news->priority === 'normal') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                            {{ ucfirst($news->priority) }}
                        </span>
                    </div>
                    
                    @if($news->excerpt)
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                            {{ Str::limit($news->excerpt, 150) }}
                        </p>
                    @else
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                            {{ Str::limit(strip_tags($news->content), 150) }}
                        </p>
                    @endif
                    
                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 space-x-4">
                        <span>By {{ $news->author->name }}</span>
                        <span>{{ $news->published_at->format('M j, Y') }}</span>
                        @if($news->location)
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ ucfirst($news->location) }}
                            </span>
                        @endif
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $news->views_count ?? 0 }} views
                        </span>
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            {{ $news->likes_count ?? 0 }} likes
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
