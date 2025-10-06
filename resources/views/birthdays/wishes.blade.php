@extends('layouts.dashboard')

@section('title', $celebrationTitle)

@section('content')
    <div class="max-w-5xl mx-auto">
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Birthdays', 'href' => route('birthdays.index')],
            ['label' => $user->name],
        ]" />

        <!-- Celebration Header -->
        <div class="card-premium overflow-hidden mb-8">
            <div
                class="bg-gradient-to-r {{ $celebrationType === 'birthday' ? 'from-blue-600 to-purple-600' : 'from-green-600 to-teal-600' }} p-10 text-white">
                <div class="flex items-center gap-6">
                    <!-- Avatar -->
                    <div
                        class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-4xl backdrop-blur-sm">
                        {{ substr($user->name, 0, 1) }}
                    </div>

                    <!-- Celebration Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-5xl">{{ $celebrationType === 'birthday' ? '🎂' : '🏆' }}</span>
                            <div>
                                <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                                @if ($celebrationType === 'birthday' && $user->getAge())
                                    <p class="text-white/90 text-lg">Turns {{ $user->getAge() }} today!</p>
                                @endif
                            </div>
                        </div>
                        <p class="text-white/80 text-lg">
                            {{ $celebrationType === 'birthday' ? 'Birthday' : 'Work Anniversary' }} Celebration
                        </p>
                    </div>

                    <!-- Celebration Stats -->
                    <div class="text-center">
                        <div class="text-3xl font-bold">{{ $wishes->count() }}</div>
                        <div class="text-white/80 text-sm">Wishes</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Send Wish Form -->
        @if (!$hasWished)
            <div class="card-premium mb-8">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="text-2xl">💬</span>
                        Send Your Wish
                    </h2>
                    <form action="{{ route('birthdays.wishes.store', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="celebration_type" value="{{ $celebrationType }}">

                        <div>
                            <textarea name="message" rows="3" required
                                class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 resize-none"
                                placeholder="Write your birthday wish here... 🎉"></textarea>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_public" value="1" checked
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Make this wish public</span>
                            </label>

                            <button type="submit"
                                class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-2 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                                Send Wish 🎉
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Wishes Feed -->
        <div class="space-y-6">
            @forelse ($wishes as $wish)
                <div class="card-premium">
                    <div class="p-6">
                        <!-- Wish Header -->
                        <div class="flex items-start gap-4 mb-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                {{ substr($wish->sender->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-bold text-gray-900">{{ $wish->sender->name }}</h3>
                                    @if (!$wish->is_public)
                                        <span
                                            class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Private</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ $wish->created_at->diffForHumans() }}</p>
                            </div>
                            @if ($wish->sender_id === auth()->id())
                                <form action="{{ route('birthdays.wishes.destroy', $wish) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this wish?')"
                                        class="text-red-500 hover:text-red-700 p-1 hover:bg-red-50 rounded transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Wish Message -->
                        <div class="mb-4">
                            <p class="text-gray-800 leading-relaxed">{{ $wish->message }}</p>
                        </div>

                        <!-- Reactions -->
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center gap-1">
                                @php
                                    $commonEmojis = ['🎉', '🎂', '❤️', '🥳', '🎁', '👏', '🔥', '💯'];
                                @endphp
                                @foreach ($commonEmojis as $emoji)
                                    @php
                                        $count = $wish->getReactionCount($emoji);
                                        $hasReacted = $wish->hasUserReacted($emoji, auth()->id());
                                    @endphp
                                    @if ($count > 0 || $hasReacted)
                                        <button onclick="toggleReaction({{ $wish->id }}, '{{ $emoji }}')"
                                            class="flex items-center gap-1 px-2 py-1 rounded-full text-sm transition-colors {{ $hasReacted ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                            <span>{{ $emoji }}</span>
                                            <span class="text-xs">{{ $count }}</span>
                                        </button>
                                    @endif
                                @endforeach

                                <!-- Add Reaction Button -->
                                <div class="relative">
                                    <button onclick="toggleEmojiPicker({{ $wish->id }})"
                                        class="flex items-center gap-1 px-2 py-1 rounded-full text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </button>

                                    <div id="emoji-picker-{{ $wish->id }}"
                                        class="hidden absolute top-full left-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg p-2 z-10">
                                        <div class="flex gap-1">
                                            @foreach ($commonEmojis as $emoji)
                                                <button
                                                    onclick="addReaction({{ $wish->id }}, '{{ $emoji }}'); toggleEmojiPicker({{ $wish->id }})"
                                                    class="p-1 hover:bg-gray-100 rounded transition-colors">
                                                    {{ $emoji }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reply Button -->
                        <div class="flex items-center gap-4">
                            <button onclick="toggleReplyForm({{ $wish->id }})"
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Reply
                                @if ($wish->reply_count > 0)
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $wish->reply_count }}</span>
                                @endif
                            </button>
                        </div>

                        <!-- Reply Form (Hidden by default) -->
                        <div id="reply-form-{{ $wish->id }}" class="hidden mt-4 pt-4 border-t border-gray-200">
                            <form action="{{ route('birthdays.wishes.store', $user) }}" method="POST"
                                class="space-y-3">
                                @csrf
                                <input type="hidden" name="celebration_type" value="{{ $celebrationType }}">
                                <input type="hidden" name="parent_wish_id" value="{{ $wish->id }}">

                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <textarea name="message" rows="2" required
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 resize-none text-sm"
                                            placeholder="Write a reply..."></textarea>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="toggleReplyForm({{ $wish->id }})"
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Reply
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Replies -->
                        @if ($wish->replies->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="space-y-3">
                                    @foreach ($wish->replies as $reply)
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                                {{ substr($reply->sender->name, 0, 1) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h4 class="font-medium text-gray-900 text-sm">
                                                        {{ $reply->sender->name }}</h4>
                                                    <span
                                                        class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-gray-700 text-sm">{{ $reply->message }}</p>
                                            </div>
                                            @if ($reply->sender_id === auth()->id())
                                                <form action="{{ route('birthdays.wishes.destroy', $reply) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Delete this reply?')"
                                                        class="text-red-500 hover:text-red-700 p-1 hover:bg-red-50 rounded transition-colors">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="card-premium text-center py-12">
                    <div class="text-6xl mb-4">🎂</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No wishes yet</h3>
                    <p class="text-gray-600 mb-6">Be the first to send a birthday wish!</p>
                    @if (!$hasWished)
                        <a href="#send-wish"
                            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                            Send First Wish 🎉
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleReplyForm(wishId) {
                const form = document.getElementById(`reply-form-${wishId}`);
                if (form) {
                    form.classList.toggle('hidden');
                }
            }

            function toggleEmojiPicker(wishId) {
                const picker = document.getElementById(`emoji-picker-${wishId}`);
                if (picker) {
                    picker.classList.toggle('hidden');
                }
            }

            // Close emoji pickers when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.relative')) {
                    document.querySelectorAll('[id^="emoji-picker-"]').forEach(picker => {
                        picker.classList.add('hidden');
                    });
                }
            });

            function addReaction(wishId, emoji) {
                console.log('Adding reaction:', wishId, emoji);
                fetch(`/birthday-wishes/${wishId}/reactions`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            emoji: emoji
                        })
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            location.reload(); // Simple refresh for now
                        } else {
                            alert('Failed to add reaction: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error adding reaction: ' + error.message);
                    });
            }

            function toggleReaction(wishId, emoji) {
                console.log('Toggling reaction:', wishId, emoji);
                fetch(`/birthday-wishes/${wishId}/reactions`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            emoji: emoji
                        })
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            location.reload(); // Simple refresh for now
                        } else {
                            alert('Failed to remove reaction: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error removing reaction: ' + error.message);
                    });
            }
        </script>
    @endpush
@endsection
