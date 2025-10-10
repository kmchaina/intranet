@extends('layouts.dashboard')
@section('title', $celebrationTitle)
@section('content')
    <div class="max-w-5xl mx-auto pb-32">
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Birthdays', 'href' => route('birthdays.index')],
            ['label' => $user->name],
        ]" />

        <div class="card-premium overflow-hidden mb-8">
            <div
                class="bg-gradient-to-r {{ $celebrationType === 'birthday' ? 'from-blue-600 to-purple-600' : 'from-green-600 to-teal-600' }} p-10 text-white">
                <div class="flex items-center gap-6">
                    <div
                        class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-4xl backdrop-blur-sm">
                        {{ substr($user->name, 0, 1) }}</div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">
                            @if ($celebrationType === 'birthday')
                                🎂 {{ $user->name }}'s Birthday
                            @else
                                🎉 {{ $user->name }}'s Work Anniversary
                            @endif
                        </h1>
                        <p class="text-white/90 text-lg mb-4">
                            @if ($celebrationType === 'birthday')
                                Celebrate {{ $user->name }} with a kind message below.
                            @else
                                Congratulate {{ $user->name }} on their journey with us.
                            @endif
                        </p>

                    </div>
                </div>
            </div>
        </div>

        @if (!$hasWished)
            <div class="card-premium mb-8" id="send-wish">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        {{ $celebrationType === 'birthday' ? '🎂' : '🎉' }} Send Your
                        {{ $celebrationType === 'birthday' ? 'Birthday' : 'Anniversary' }} Wish
                    </h2>
                    <form action="{{ route('birthdays.wishes.store', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="celebration_type" value="{{ $celebrationType }}">
                        <div>
                            <textarea name="message" rows="3" required
                                class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 resize-none"
                                placeholder="Write your wish here... 🎉"></textarea>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="is_public" value="1" checked
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-700">Make this wish public</span>
                            </label>
                            <button type="submit"
                                class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-2 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">Send
                                Wish 🎉</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="space-y-6">
            @php $commonEmojis = ['🎉','🎂','❤️','🥳','🎁','👏','🔥','💯']; @endphp
            @forelse($wishes as $wish)
                <div class="card-premium">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($wish->sender->name, 0, 1) }}</div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-bold text-gray-900">{{ $wish->sender->name }}</h3>
                                        @if (!$wish->is_public)
                                            <span
                                                class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Private</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $wish->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if ($wish->sender_id === auth()->id())
                                <form action="{{ route('birthdays.wishes.destroy', $wish) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
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
                        <div class="mb-4">
                            <p class="text-gray-800 leading-relaxed">{{ $wish->message }}</p>
                        </div>

                        <div class="flex items-center gap-2 mb-4 flex-wrap" id="reactions-{{ $wish->id }}">
                            @foreach ($commonEmojis as $emoji)
                                @php
                                    $count = $wish->getReactionCount($emoji);
                                    $has = $wish->hasUserReacted($emoji, auth()->id());
                                    $reactors = collect($wish->reactions[$emoji] ?? [])
                                        ->map(fn($id) => \App\Models\User::find($id)->name ?? 'Unknown')
                                        ->take(3);
                                @endphp
                                @if ($count > 0)
                                    <button type="button" data-wish-id="{{ $wish->id }}"
                                        data-emoji="{{ $emoji }}"
                                        onclick="toggleReaction({{ $wish->id }},'{{ $emoji }}')"
                                        title="{{ $reactors->join(', ') }}{{ $count > 3 ? ' and ' . ($count - 3) . ' others' : '' }}"
                                        class="reaction-btn flex items-center gap-1 px-2 py-1 rounded-full text-sm transition {{ $has ? 'bg-blue-100 text-blue-700 ring-2 ring-blue-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"><span
                                            class="text-base">{{ $emoji }}</span><span
                                            class="text-xs font-medium reaction-count">{{ $count }}</span></button>
                                @endif
                            @endforeach
                            <div class="relative">
                                <button type="button" onclick="toggleEmojiPicker({{ $wish->id }})"
                                    class="emoji-btn flex items-center gap-1 px-3 py-2 rounded-full text-sm bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700 border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg><span class="text-xs">React</span>
                                </button>
                                <div id="emoji-picker-{{ $wish->id }}"
                                    class="hidden absolute top-full left-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-xl p-3 z-20 min-w-max">
                                    <div class="grid grid-cols-4 gap-2">
                                        @foreach ($commonEmojis as $emoji)
                                            <button type="button" class="p-2 hover:bg-gray-100 rounded-lg text-xl"
                                                onclick="addReaction({{ $wish->id }},'{{ $emoji }}'); toggleEmojiPicker({{ $wish->id }})">{{ $emoji }}</button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mb-2">
                            <button type="button"
                                class="reply-btn flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium text-sm"
                                onclick="toggleReplyForm({{ $wish->id }})">
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

                        <div id="reply-form-{{ $wish->id }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                            <form action="{{ route('birthdays.wishes.store', $user) }}" method="POST"
                                class="space-y-3">
                                @csrf
                                <input type="hidden" name="celebration_type" value="{{ $celebrationType }}">
                                <input type="hidden" name="parent_wish_id" value="{{ $wish->id }}">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr(auth()->user()->name, 0, 1) }}</div>
                                    <div class="flex-1">
                                        <textarea name="message" rows="2" required
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 resize-none text-sm"
                                            placeholder="Write a reply..."></textarea>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm"
                                        onclick="toggleReplyForm({{ $wish->id }})">Cancel</button>
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Reply</button>
                                </div>
                            </form>
                        </div>

                        @if ($wish->replies->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200 space-y-3">
                                @foreach ($wish->replies as $reply)
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ substr($reply->sender->name, 0, 1) }}</div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="font-medium text-gray-900 text-sm">{{ $reply->sender->name }}
                                                </h4>
                                                <span
                                                    class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-700 text-sm">{{ $reply->message }}</p>
                                            <!-- Reply Reactions -->
                                            <div class="flex items-center gap-2 mt-2 flex-wrap"
                                                id="reactions-{{ $reply->id }}">
                                                @foreach ($commonEmojis as $emoji)
                                                    @php
                                                        $rcount = $reply->getReactionCount($emoji);
                                                        $rhas = $reply->hasUserReacted($emoji, auth()->id());
                                                        $rnames = collect($reply->reactions[$emoji] ?? [])
                                                            ->map(fn($id) => \App\Models\User::find($id)->name ?? 'Unknown')
                                                            ->take(3);
                                                    @endphp
                                                    @if ($rcount > 0)
                                                        <button type="button" data-wish-id="{{ $reply->id }}"
                                                            data-emoji="{{ $emoji }}"
                                                            onclick="toggleReaction({{ $reply->id }},'{{ $emoji }}')"
                                                            title="{{ $rnames->join(', ') }}{{ $rcount > 3 ? ' and ' . ($rcount - 3) . ' others' : '' }}"
                                                            class="reaction-btn flex items-center gap-1 px-2 py-1 rounded-full text-xs transition {{ $rhas ? 'bg-blue-100 text-blue-700 ring-2 ring-blue-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"><span
                                                                class="text-sm">{{ $emoji }}</span><span
                                                                class="text-[10px] font-medium reaction-count">{{ $rcount }}</span></button>
                                                    @endif
                                                @endforeach
                                                <div class="relative">
                                                    <button type="button"
                                                        onclick="toggleEmojiPicker({{ $reply->id }})"
                                                        class="emoji-btn flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700 border border-gray-200">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg><span>React</span>
                                                    </button>
                                                    <div id="emoji-picker-{{ $reply->id }}"
                                                        class="hidden absolute top-full left-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-xl p-2 z-20 min-w-max">
                                                        <div class="grid grid-cols-4 gap-1">
                                                            @foreach ($commonEmojis as $emoji)
                                                                <button type="button"
                                                                    class="p-1 hover:bg-gray-100 rounded-lg text-base"
                                                                    onclick="addReaction({{ $reply->id }},'{{ $emoji }}'); toggleEmojiPicker({{ $reply->id }})">{{ $emoji }}</button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($reply->sender_id === auth()->id())
                                            <form action="{{ route('birthdays.wishes.destroy', $reply) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
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
                            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">Send
                            First Wish 🎉</a>
                    @endif
                </div>
            @endforelse
        </div>
        <div class="h-24"></div> <!-- extra bottom space so new reactions near end are visible -->
    </div>

    @push('styles')
        <style>
            .reaction-btn,
            .emoji-btn,
            .reply-btn {
                cursor: pointer
            }

            .reaction-btn {
                transition: background .15s, transform .15s
            }

            .reaction-btn:hover {
                transform: translateY(-2px)
            }

            .emoji-btn:hover,
            .reply-btn:hover {
                background: rgba(0, 0, 0, 0.04)
            }

            [id^="emoji-picker-"] {
                animation: fadeIn .12s ease
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(4px)
                }

                to {
                    opacity: 1;
                    transform: translateY(0)
                }
            }

            .reaction-flash {
                position: relative;
            }

            .reaction-flash:after {
                content: "";
                position: absolute;
                inset: 0;
                border-radius: 9999px;
                box-shadow: 0 0 0 0 rgba(59, 130, 246, .55);
                animation: flashPulse .9s ease-out forwards;
                pointer-events: none
            }

            @keyframes flashPulse {
                0% {
                    box-shadow: 0 0 0 0 rgba(59, 130, 246, .55);
                    background: rgba(191, 219, 254, .6)
                }

                70% {
                    box-shadow: 0 0 0 8px rgba(59, 130, 246, 0);
                    background: rgba(191, 219, 254, .25)
                }

                100% {
                    box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
                    background: transparent
                }
            }

            /* When scrolling into view, leave comfortable breathing room below */
            .reaction-btn {
                scroll-margin-bottom: 140px;
            }

            /* Drop-up positioning for emoji picker when near bottom */
            [id^="emoji-picker-"].drop-up {
                top: auto;
                bottom: 100%;
                margin-top: 0;
                margin-bottom: .5rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function toggleReplyForm(id) {
                const f = document.getElementById('reply-form-' + id);
                if (!f) return;
                f.classList.toggle('hidden');
                if (!f.classList.contains('hidden')) {
                    const ta = f.querySelector('textarea');
                    if (ta) setTimeout(() => ta.focus(), 40);
                }
            }

            function toggleEmojiPicker(id) {
                // Close others
                document.querySelectorAll('[id^="emoji-picker-"]').forEach(p => {
                    if (p.id !== `emoji-picker-${id}`) p.classList.add('hidden')
                });
                const picker = document.getElementById('emoji-picker-' + id);
                if (!picker) return;
                picker.classList.toggle('hidden');
                if (!picker.classList.contains('hidden')) {
                    // Reset any previous positioning
                    picker.classList.remove('drop-up');
                    // Allow layout to paint then measure
                    requestAnimationFrame(() => {
                        const rect = picker.getBoundingClientRect();
                        const overflowBottom = rect.bottom - (window.innerHeight - 12);
                        if (overflowBottom > 0) {
                            // Switch to drop-up if it would overflow bottom
                            picker.classList.add('drop-up');
                            const upRect = picker.getBoundingClientRect();
                            // If still clipped at top, scroll the button into view
                            if (upRect.top < 0) {
                                const btn = picker.parentElement?.querySelector('.emoji-btn');
                                btn?.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        }
                    });
                }
            }

            function addReaction(id, emoji) {
                fetch(`/birthday-wishes/${id}/reactions`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        emoji
                    })
                }).then(r => r.json()).then(d => {
                    if (d.success) updateReactionButton(id, emoji, d.reaction_count, d.has_reacted, d.reactors || [], d
                        .total_reactors || 0);
                });
            }

            function toggleReaction(id, emoji) {
                const btn = document.querySelector(`[data-wish-id='${id}'][data-emoji='${emoji}']`);
                const has = btn?.classList.contains('bg-blue-100');
                fetch(`/birthday-wishes/${id}/reactions`, {
                    method: has ? 'DELETE' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        emoji
                    })
                }).then(r => r.json()).then(d => {
                    if (d.success) updateReactionButton(id, emoji, d.reaction_count, d.has_reacted, d.reactors || [], d
                        .total_reactors || 0);
                });
            }

            function updateReactionButton(id, emoji, count, has, reactors = [], total = 0) {
                const c = document.getElementById(`reactions-${id}`);
                if (!c) return;
                let b = c.querySelector(`[data-wish-id='${id}'][data-emoji='${emoji}']`);
                if (count <= 0) {
                    if (b) b.remove();
                    return;
                }
                let isNew = false;
                if (!b) {
                    const add = c.querySelector('.emoji-btn')?.parentNode;
                    b = document.createElement('button');
                    isNew = true;
                    b.type = 'button';
                    b.dataset.wishId = id;
                    b.dataset.emoji = emoji;
                    b.className =
                        'reaction-btn flex items-center gap-1 px-2 py-1 rounded-full text-sm transition bg-gray-100 text-gray-600';
                    b.innerHTML =
                        `<span class="text-base">${emoji}</span><span class='text-xs font-medium reaction-count'>${count}</span>`;
                    b.onclick = () => toggleReaction(id, emoji);
                    if (add && add.parentNode) add.parentNode.insertBefore(b, add);
                } else {
                    const s = b.querySelector('.reaction-count');
                    if (s) s.textContent = count;
                }
                b.title = reactors.slice(0, 3).join(', ') + (total > 3 ? ` and ${total-3} others` : '');
                if (has) {
                    b.classList.add('bg-blue-100', 'text-blue-700', 'ring-2', 'ring-blue-200');
                    b.classList.remove('bg-gray-100', 'text-gray-600');
                } else {
                    b.classList.add('bg-gray-100', 'text-gray-600');
                    b.classList.remove('bg-blue-100', 'text-blue-700', 'ring-2', 'ring-blue-200');
                }
                // Flash effect
                b.classList.remove('reaction-flash');
                void b.offsetWidth;
                b.classList.add('reaction-flash');
                ensureVisible(b, isNew);
            }

            function ensureVisible(el, isNew) {
                if (!isNew) return; // only scroll on new buttons to reduce jumpiness
                const safePad = 120; // desired free space below
                const rect = el.getBoundingClientRect();
                // If fully visible with safe padding, do nothing
                if (rect.bottom < window.innerHeight - 10 && rect.top > 0) return;
                // Try scrollable ancestor first (excluding body)
                const scroller = findScrollableAncestor(el);
                if (scroller) {
                    const sRect = scroller.getBoundingClientRect();
                    const overBottom = rect.bottom - (sRect.bottom - safePad);
                    if (overBottom > 0) {
                        scroller.scrollBy({
                            top: overBottom,
                            behavior: 'smooth'
                        });
                        return;
                    }
                    const overTop = (sRect.top + 40) - rect.top; // keep some top space
                    if (overTop > 0) {
                        scroller.scrollBy({
                            top: -overTop,
                            behavior: 'smooth'
                        });
                        return;
                    }
                }
                // Fallback to window scroll
                if (rect.bottom > window.innerHeight - 20) {
                    window.scrollBy({
                        top: rect.bottom - (window.innerHeight - safePad),
                        behavior: 'smooth'
                    });
                } else if (rect.top < 0) {
                    window.scrollBy({
                        top: rect.top - 40,
                        behavior: 'smooth'
                    });
                }
            }

            function findScrollableAncestor(el) {
                let p = el.parentElement;
                while (p) {
                    const style = getComputedStyle(p);
                    if ((style.overflowY === 'auto' || style.overflowY === 'scroll') && p.scrollHeight > p.clientHeight) {
                        return p;
                    }
                    p = p.parentElement;
                }
                return null;
            }
            document.addEventListener('click', e => {
                if (!e.target.closest('.relative')) {
                    document.querySelectorAll('[id^="emoji-picker-"]').forEach(p => p.classList.add('hidden'));
                }
            });
            console.log('Birthday wishes script loaded');
        </script>
    @endpush
@endsection
