@extends('layouts.dashboard')

@section('title', $news->title)

@section('content')
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li>
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700 transition-colors">Dashboard</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('news.index') }}" class="hover:text-gray-700 transition-colors">News Feed</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li class="text-gray-900 font-medium truncate">
                    {{ $news && is_object($news) ? $news->title : 'News Article' }}</li>
            </ol>
        </nav>

        <!-- Article Header -->
        <article class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <!-- Featured Image -->
            @if ($news && is_object($news) && $news->featured_image)
                <div class="aspect-w-16 aspect-h-9">
                    <img src="{{ asset('storage/' . $news->featured_image) }}"
                        alt="{{ $news && is_object($news) ? $news->title : 'News Article' }}"
                        class="w-full h-96 object-cover">
                </div>
            @endif

            <!-- Article Content -->
            <div class="p-8">
                <!-- Meta Information -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800">
                            {{ $news && is_object($news) ? $news->location_display : 'Unknown Location' }}
                        </span>
                        @if ($news && is_object($news) && $news->priority && $news->priority !== 'normal')
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $news && is_object($news) ? $news->priority_color : 'bg-gray-100 text-gray-800' }}">
                                {{ $news && is_object($news) && $news->priority ? ucfirst($news->priority) : 'Normal' }}
                                Priority
                            </span>
                        @endif
                        @if ($news && is_object($news) && $news->is_featured)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                Featured
                            </span>
                        @endif
                    </div>

                    @can('update', $news)
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('news.edit', $news) }}"
                                class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit
                            </a>
                        </div>
                    @endcan
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4 leading-tight">
                    {{ $news && is_object($news) ? $news->title : 'News Article' }}</h1>

                <!-- Author and Date Information -->
                <div class="flex items-center justify-between text-sm text-gray-600 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            By <span
                                class="font-medium text-gray-900">{{ $news && is_object($news) && $news->author ? $news->author->name : 'Unknown Author' }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $news && is_object($news) && $news->published_at ? $news->published_at->format('F j, Y \a\t g:i A') : 'Unknown Date' }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                            {{ $news && is_object($news) ? $news->reading_time : '' }}
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            {{ $news && is_object($news) ? $news->views_count : 0 }}
                            {{ $news && is_object($news) && $news->views_count ? Str::plural('view', $news->views_count) : 'views' }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="prose prose-lg max-w-none text-gray-900 leading-relaxed">
                    {!! $news && is_object($news) && $news->content ? nl2br(e($news->content)) : 'No content available' !!}
                </div>

                <!-- Tags (if you add tags in the future) -->
                @if ($news->tags ?? false)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-700">Tags:</span>
                            @foreach ($news->tags as $tag)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Engagement Section -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <!-- Like Button -->
                            <button id="like-btn" data-news-id="{{ $news && is_object($news) ? $news->id : 0 }}"
                                class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors duration-200 {{ isset($isLiked) && $isLiked ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                <svg class="w-5 h-5" fill="{{ isset($isLiked) && $isLiked ? 'currentColor' : 'none' }}"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                                <span id="like-count">{{ $news && is_object($news) ? $news->likes_count : 0 }}</span>
                                <span>{{ isset($isLiked) && $isLiked ? 'Liked' : 'Like' }}</span>
                            </button>

                            <!-- Share Button -->
                            <button onclick="shareNews()"
                                class="flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                    </path>
                                </svg>
                                <span>Share</span>
                            </button>
                        </div>

                        <div class="text-sm text-gray-500">
                            Last updated: {{ $news->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        @if ($news->allow_comments)
            <div class="mt-8 bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    Comments ({{ $news->comments_count }})
                </h3>

                <!-- Comment Form -->
                <form id="comment-form" class="mb-8">
                    @csrf
                    <input type="hidden" name="news_id" value="{{ $news->id }}">
                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Add a comment</label>
                        <textarea name="content" id="comment" rows="3" placeholder="Share your thoughts..."
                            class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm resize-none"></textarea>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Post Comment
                    </button>
                </form>

                <!-- Comments List -->
                <div id="comments-list">
                    @foreach ($news->comments()->whereNull('parent_id')->latest()->get() as $comment)
                        <div class="border-b border-gray-200 pb-6 mb-6 last:border-b-0 last:pb-0 last:mb-0">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                                        <span
                                            class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ $comment->content }}</p>

                                    <!-- Reply button -->
                                    <button onclick="toggleReplyForm({{ $comment->id }})"
                                        class="mt-2 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                        Reply
                                    </button>

                                    <!-- Reply form (hidden by default) -->
                                    <form id="reply-form-{{ $comment->id }}" class="mt-4 hidden reply-form">
                                        @csrf
                                        <input type="hidden" name="news_id" value="{{ $news->id }}">
                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                        <div class="flex space-x-3">
                                            <textarea name="content" rows="2" placeholder="Write a reply..."
                                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm resize-none"></textarea>
                                            <button type="submit"
                                                class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                Reply
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Replies -->
                                    @if ($comment->replies->count() > 0)
                                        <div class="mt-4 space-y-4">
                                            @foreach ($comment->replies as $reply)
                                                <div class="flex items-start space-x-3 pl-6 border-l-2 border-gray-200">
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center">
                                                            <span class="text-white text-xs font-medium">
                                                                {{ substr($reply->user->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-1">
                                                            <span
                                                                class="font-medium text-gray-900">{{ $reply->user->name }}</span>
                                                            <span
                                                                class="text-sm text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-gray-700">{{ $reply->content }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($news->comments()->whereNull('parent_id')->count() === 0)
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No comments yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Be the first to share your thoughts on this news.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Related News -->
        @if ($relatedNews->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Related News</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($relatedNews as $related)
                        <article
                            class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                            @if ($related && is_object($related) && $related->featured_image)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ asset('storage/' . $related->featured_image) }}"
                                        alt="{{ $related && is_object($related) ? $related->title : 'Related News' }}"
                                        class="w-full h-32 object-cover">
                                </div>
                            @endif

                            <div class="p-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-2">
                                    {{ $related && is_object($related) ? $related->location_display : 'Unknown Location' }}
                                </span>

                                <h4 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <a href="{{ route('news.show', $related) }}"
                                        class="hover:text-teal-600 transition-colors">
                                        {{ $related && is_object($related) ? $related->title : 'Related News' }}
                                    </a>
                                </h4>

                                <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                    {{ $related && is_object($related) && $related->excerpt ? $related->excerpt : 'No excerpt available' }}
                                </p>

                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $related && is_object($related) && $related->published_at ? $related->published_at->diffForHumans() : 'Unknown Date' }}</span>
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        {{ $related && is_object($related) ? $related->views_count : 0 }}
                                    </span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .aspect-w-16 {
                position: relative;
                padding-bottom: 56.25%;
            }

            .aspect-w-16>* {
                position: absolute;
                height: 100%;
                width: 100%;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
            }

            .prose {
                line-height: 1.7;
            }

            .prose p {
                margin-bottom: 1rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Like functionality
            document.getElementById('like-btn').addEventListener('click', function() {
                const newsId = this.dataset.newsId;
                const likeCount = document.getElementById('like-count');
                const button = this;

                fetch(`/news/${newsId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            likeCount.textContent = data.likes_count;

                            const svg = button.querySelector('svg');
                            const span = button.querySelector('span:last-child');

                            if (data.liked) {
                                button.className =
                                    'flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors duration-200 bg-red-100 text-red-700 hover:bg-red-200';
                                svg.setAttribute('fill', 'currentColor');
                                span.textContent = 'Liked';
                            } else {
                                button.className =
                                    'flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200';
                                svg.setAttribute('fill', 'none');
                                span.textContent = 'Like';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            // Comment functionality
            document.getElementById('comment-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const newsId = formData.get('news_id');

                fetch(`/news/${newsId}/comment`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Simple reload for now
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            // Reply forms
            document.querySelectorAll('.reply-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const newsId = formData.get('news_id');

                    fetch(`/news/${newsId}/comment`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload(); // Simple reload for now
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });

            // Toggle reply forms
            function toggleReplyForm(commentId) {
                const form = document.getElementById(`reply-form-${commentId}`);
                form.classList.toggle('hidden');

                if (!form.classList.contains('hidden')) {
                    form.querySelector('textarea').focus();
                }
            }

            // Share functionality
            function shareNews() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $news && is_object($news) ? $news->title : 'News Article' }}',
                        text: '{{ $news && is_object($news) && $news->excerpt ? $news->excerpt : 'Check out this news article' }}',
                        url: window.location.href
                    });
                } else {
                    // Fallback to copying URL
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        alert('Link copied to clipboard!');
                    });
                }
            }
        </script>
    @endpush
@endsection
