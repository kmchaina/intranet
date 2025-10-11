@auth
    <div x-data="chatWidget()" x-init="init()" class="">
        <!-- Floating Button -->
        <button @click="toggle()"
            class="fixed z-40 bottom-5 right-5 w-14 h-14 rounded-full shadow-lg flex items-center justify-center text-white font-medium transition-all"
            :class="open ? 'bg-indigo-600' : 'bg-indigo-500 hover:bg-indigo-600'">
            <template x-if="!open">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8-.99 0-1.945-.124-2.843-.355-.548.31-1.78 1.018-3.657 1.602-.376.118-.752-.19-.671-.575.155-.73.389-1.802.453-2.022C3.907 17.64 3 15.393 3 13c0-4.418 4.03-8 9-8s9 3.582 9 8Z" />
                </svg>
            </template>
            <template x-if="open">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </template>
            <span x-show="totalUnread" x-transition
                class="absolute -top-1 -right-1 bg-red-600 text-[10px] px-1.5 py-0.5 rounded-full"
                x-text="totalUnread"></span>
        </button>

        <!-- Panel -->
        <div x-show="open" x-transition x-cloak
            class="fixed bottom-24 right-5 w-96 max-h-[70vh] bg-white rounded-xl shadow-2xl border flex flex-col z-40 overflow-hidden">
            <!-- Header -->
            <div class="px-4 py-3 border-b flex items-center justify-between bg-indigo-600 text-white">
                <div>
                    <h3 class="font-semibold text-sm">Messages</h3>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="startNewGroup()" class="text-[10px] bg-white/10 hover:bg-white/20 px-2 py-0.5 rounded">+
                        New</button>
                    <button @click="toggle()" class="text-white/80 hover:text-white text-xl leading-none">×</button>
                </div>
            </div>
            <div class="flex flex-1 overflow-hidden">
                <!-- Conversations -->
                <div class="w-40 border-r flex flex-col">
                    <div class="p-2">
                        <input x-model="filter" placeholder="Search" class="w-full text-[11px] px-2 py-1 border rounded" />
                    </div>
                    <div class="flex-1 overflow-y-auto custom-scroll divide-y">
                        <template x-for="c in filteredConversations()" :key="c.id">
                            <div @click="selectConversation(c)"
                                class="px-2 py-2 cursor-pointer text-[11px] flex flex-col gap-0.5 hover:bg-indigo-50"
                                :class="{ 'bg-indigo-100': current && current.id === c.id }">
                                <div class="flex justify-between gap-1 items-center">
                                    <span class="font-medium truncate" x-text="c.title"></span>
                                    <span x-show="c.unread" class="bg-red-600 text-white rounded-full text-[9px] px-1"
                                        x-text="c.unread"></span>
                                </div>
                                <div class="text-[10px] text-gray-500 truncate"
                                    x-text="c.last_message ? (c.last_message.deleted ? 'Message deleted' : (c.last_message.body || '[Attachment]')) : 'No messages' ">
                                </div>
                            </div>
                        </template>
                        <div x-show="!filteredConversations().length" class="p-3 text-[10px] text-gray-500">No chats</div>
                    </div>
                </div>
                <!-- Conversation Pane -->
                <div class="flex-1 flex flex-col">
                    <template x-if="current">
                        <div class="flex flex-col h-full">
                            <div class="px-3 py-2 border-b flex items-center justify-between gap-2">
                                <h4 class="font-medium text-sm truncate" x-text="current.title"></h4>
                                <div class="flex gap-1">
                                    <button @click="openSearch(); searchMode = 'current';"
                                        title="Search in conversation (Ctrl+K)"
                                        class="text-[10px] px-2 py-0.5 border rounded hover:bg-gray-50">
                                        🔍
                                    </button>
                                    <button @click="refreshCurrent()"
                                        class="text-[10px] px-2 py-0.5 border rounded hover:bg-gray-50">Refresh</button>
                                </div>
                            </div>
                            <div class="flex-1 overflow-y-auto p-3 space-y-2" x-ref="msgList">
                                <!-- Scroll sentinel for infinite scroll -->
                                <div x-ref="scrollSentinel" class="h-1"></div>

                                <!-- Loading indicator -->
                                <div x-show="loadingOlder" x-transition class="text-center py-2">
                                    <svg class="inline-block animate-spin w-4 h-4 text-indigo-600" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span class="ml-2 text-[10px] text-gray-500">Loading older messages...</span>
                                </div>

                                <!-- "No more messages" indicator -->
                                <div x-show="!hasMoreMessages && messages.length > 0" class="text-center py-2">
                                    <span class="text-[10px] text-gray-400">• Beginning of conversation •</span>
                                </div>

                                <template x-for="m in messages" :key="m.id">
                                    <div class="text-xs group transition-colors duration-300"
                                        :class="{ 'text-right': m.user.id === userId }" :data-message-id="m.id">
                                        <div class="inline-block max-w-[70%] px-2 py-1 rounded relative"
                                            :class="m.user.id === userId ? 'bg-indigo-600 text-white' : 'bg-gray-100'">
                                            <div class="text-[9px] opacity-70 mb-0.5 flex items-center gap-1.5">
                                                <span x-text="m.user.name"></span>
                                                <!-- Online presence indicator -->
                                                <span x-show="isUserOnline(m.user.id)"
                                                    class="inline-block w-2 h-2 rounded-full bg-green-500"
                                                    title="Online"></span>
                                            </div>
                                            <div x-text="m.body" class="whitespace-pre-wrap break-words"></div>
                                            <template x-if="m.attachments && m.attachments.length">
                                                <ul class="mt-1 space-y-0.5">
                                                    <template x-for="a in m.attachments" :key="a.url">
                                                        <li><a :href="a.url" target="_blank"
                                                                class="underline text-[10px]" x-text="a.name"></a></li>
                                                    </template>
                                                </ul>
                                            </template>
                                            <div class="text-[9px] opacity-50 mt-0.5" x-text="formatTime(m.at)"></div>
                                            <button x-show="canDelete(m)" @click="deleteMessage(m)"
                                                class="hidden group-hover:inline-block absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-4 h-4 text-[9px] leading-4">×</button>
                                        </div>
                                    </div>
                                </template>
                                <div class="text-[10px] text-gray-500 italic" x-show="typingUsers.length" x-transition>
                                    <span x-text="typingUsers.map(u=>u.name).join(', ') + ' typing...' "></span>
                                </div>
                            </div>
                            <form @submit.prevent="sendMessage" class="p-2 border-t flex flex-col gap-1">
                                <div class="flex gap-1 items-start">
                                    <textarea x-model="draft" @input.debounce.300ms="typing(); onDraftInput();" rows="2"
                                        class="flex-1 text-[11px] border rounded px-2 py-1" placeholder="Type..."></textarea>
                                    <div class="flex flex-col gap-1 w-16">
                                        <button type="button" @click="$refs.file.click()"
                                            class="text-[10px] border rounded px-2 py-1 bg-white">File</button>
                                        <button type="submit"
                                            class="text-[11px] bg-indigo-600 text-white rounded px-2 py-1 disabled:opacity-40"
                                            :disabled="!draft.trim() && !attachments.length">Send</button>
                                    </div>
                                    <input type="file" multiple class="hidden" x-ref="file"
                                        @change="handleFiles($event)" />
                                </div>
                                <!-- Draft status indicator -->
                                <div class="flex items-center gap-2 text-[9px] text-gray-500">
                                    <span x-show="draftSaving" x-transition class="flex items-center gap-1">
                                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Saving...
                                    </span>
                                    <span x-show="draftSaved" x-transition class="text-green-600">
                                        ✓ Draft saved
                                    </span>
                                </div>
                            </form>
                        </div>
                    </template>
                    <div x-show="!current" class="flex-1 flex items-center justify-center text-[11px] text-gray-500">
                        Select or create a chat</div>
                </div>
            </div>
        </div>

        <!-- New Group Modal -->
        <div x-show="showNew" x-cloak style="display:none"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white w-full max-w-sm rounded-xl shadow-lg p-5 flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h4 class="font-semibold text-sm">New Group</h4>
                    <button @click="cancelNew()" class="text-gray-500 hover:text-gray-700">×</button>
                </div>
                <div class="flex flex-col gap-3">
                    <label class="text-[11px] flex flex-col gap-1">
                        <span class="font-medium">Title</span>
                        <input x-model="newGroup.title" class="border rounded px-2 py-1 text-[12px]" />
                    </label>
                    <div class="flex flex-col gap-1">
                        <span class="text-[11px] font-medium">Add People</span>
                        <input x-model="userTerm" @input="searchUsers" placeholder="Type name or email"
                            class="border rounded px-2 py-1 text-[12px]" />
                        <div class="border rounded max-h-40 overflow-y-auto"
                            x-show="searchResults.length || searching || searchError || (userTerm.trim().length && !searching)">
                            <template x-for="u in searchResults" :key="u.id">
                                <div @click="toggleSelect(u)"
                                    class="px-2 py-1 text-[11px] cursor-pointer flex justify-between hover:bg-indigo-50"
                                    :class="{ 'bg-indigo-100': chosen.find(x => x.id === u.id) }">
                                    <span x-text="u.name + ' (' + u.email + ')'" class="truncate"></span>
                                    <span x-show="chosen.find(x=>x.id===u.id)" class="text-indigo-600">✔</span>
                                </div>
                            </template>
                            <div x-show="searching" class="px-2 py-1 text-[10px] text-gray-500">Searching...</div>
                            <div x-show="!searching && !searchError && !searchResults.length && userTerm.trim().length"
                                class="px-2 py-1 text-[10px] text-gray-500">No matches</div>
                            <div x-show="searchError" class="px-2 py-1 text-[10px] text-red-600" x-text="searchError">
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-1" x-show="chosen.length">
                            <template x-for="c in chosen" :key="c.id">
                                <span
                                    class="bg-indigo-600 text-white text-[10px] px-2 py-0.5 rounded flex items-center gap-1">
                                    <span x-text="c.name"></span>
                                    <button @click.prevent="toggleSelect(c)" class="leading-none">×</button>
                                </span>
                            </template>
                        </div>
                    </div>
                    <div class="text-[10px] text-red-600" x-text="newError"></div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button @click="cancelNew()" class="text-[11px] px-3 py-1 border rounded">Cancel</button>
                        <button @click="createGroup()"
                            class="text-[11px] px-3 py-1 bg-indigo-600 text-white rounded disabled:opacity-40"
                            :disabled="!canCreateGroup">Create</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Toasts -->
        <div class="fixed bottom-5 right-24 space-y-2" x-cloak>
            <template x-for="t in toasts" :key="t.id">
                <div class="px-3 py-2 rounded shadow text-white text-[11px]"
                    :class="{ 'bg-indigo-600': t.type==='info', 'bg-green-600': t.type==='success', 'bg-red-600': t.type==='error' }"
                    x-text="t.msg"></div>
            </template>
        </div>

        <!-- Phase 11C: Search Modal -->
        <div x-show="showSearch" x-cloak @click.self="closeSearch()"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-start justify-center pt-20 z-50"
            style="display: none;">
            <div @click.stop class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[70vh] flex flex-col">
                <!-- Search Header -->
                <div class="p-4 border-b">
                    <div class="flex items-center gap-2 mb-3">
                        <input x-ref="searchInput" x-model="searchQuery" @input.debounce.300ms="performSearch()"
                            @keydown.escape="closeSearch()" placeholder="Search messages..."
                            class="flex-1 px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        <button @click="closeSearch()" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800">
                            <span class="text-lg">×</span>
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <button @click="searchMode = 'all'; performSearch();"
                            :class="searchMode === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'"
                            class="px-3 py-1 text-xs rounded">
                            All Conversations
                        </button>
                        <button @click="searchMode = 'current'; performSearch();"
                            :class="searchMode === 'current' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'"
                            :disabled="!current" class="px-3 py-1 text-xs rounded disabled:opacity-50">
                            Current Only
                        </button>
                        <span class="text-xs text-gray-500 ml-auto self-center">
                            Press <kbd class="px-1 py-0.5 bg-gray-100 border rounded text-[10px]">Esc</kbd> to close
                        </span>
                    </div>
                </div>

                <!-- Search Results -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    <!-- Loading State -->
                    <div x-show="searchInProgress" class="text-center py-8">
                        <svg class="inline-block animate-spin w-6 h-6 text-indigo-600" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="text-sm text-gray-500 mt-2">Searching...</p>
                    </div>

                    <!-- No Query Yet -->
                    <div x-show="!searchQuery.trim() && !searchInProgress" class="text-center py-8 text-gray-400 text-sm">
                        <p>Type to search messages</p>
                        <p class="text-xs mt-2">Ctrl+K to open search</p>
                    </div>

                    <!-- Results -->
                    <template x-if="searchMode === 'all' && !searchInProgress">
                        <div>
                            <div x-show="searchQuery.trim() && globalSearchResults.length === 0"
                                class="text-center py-8 text-gray-500 text-sm">
                                No messages found
                            </div>
                            <template x-for="result in globalSearchResults"
                                :key="result.conversation_id + '-' + result.message_id">
                                <div @click="jumpToMessage(result)"
                                    class="p-3 border rounded-lg cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <span class="font-medium text-sm text-indigo-600"
                                            x-text="result.conversation_title"></span>
                                        <span class="text-xs text-gray-400" x-text="formatTime(result.created_at)"></span>
                                    </div>
                                    <div class="text-xs text-gray-600 mb-1">
                                        <span class="font-medium" x-text="result.user_name"></span>
                                    </div>
                                    <div class="text-sm text-gray-700" x-html="highlightMatch(result.body, searchQuery)">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="searchMode === 'current' && !searchInProgress">
                        <div>
                            <div x-show="searchQuery.trim() && currentSearchResults.length === 0"
                                class="text-center py-8 text-gray-500 text-sm">
                                No messages found in current conversation
                            </div>
                            <template x-for="result in currentSearchResults" :key="result.message_id">
                                <div @click="jumpToMessage(result)"
                                    class="p-3 border rounded-lg cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <span class="font-medium text-xs text-gray-600" x-text="result.user_name"></span>
                                        <span class="text-xs text-gray-400" x-text="formatTime(result.created_at)"></span>
                                    </div>
                                    <div class="text-sm text-gray-700" x-html="highlightMatch(result.body, searchQuery)">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <script>
        function chatWidget() {
            return {
                open: false,
                userId: {{ auth()->id() }},
                isSuperAdmin: {{ auth()->user()->isSuperAdmin() ? 'true' : 'false' }},
                conversations: [],
                current: null,
                messages: [],
                draft: '',
                attachments: [],
                filter: '',
                pollTimer: null,
                echoConnected: false,
                typingUsers: [],
                typingTimers: {},
                showNew: false,
                newGroup: {
                    title: ''
                },
                userTerm: '',
                searchResults: [],
                searching: false,
                chosen: [],
                newError: '',
                searchTimer: null,
                searchError: '',
                toasts: [],
                draftSaveTimer: null,
                draftSaving: false,
                draftSaved: false,
                heartbeatTimer: null,
                onlineUsers: [],
                presenceTTL: 60,
                // Phase 11D: Infinite Scroll
                loadingOlder: false,
                hasMoreMessages: true,
                scrollObserver: null,
                // Phase 11C: Search Modal
                showSearch: false,
                searchQuery: '',
                searchMode: 'all', // 'all' or 'current'
                searchInProgress: false,
                globalSearchResults: [],
                currentSearchResults: [],
                init() {
                    const persisted = localStorage.getItem('chatWidgetOpen');
                    if (persisted === 'true') {
                        this.open = true;
                    }
                    if (this.open) {
                        this.loadConversations();
                    }
                    this.setupEchoMonitor();
                    this.startHeartbeat();
                    this.setupScrollObserver();
                    this.setupKeyboardShortcuts();
                },
                setupEchoMonitor() {
                    const tryBind = () => {
                        if (window.Echo && window.__echoReady && !this._echoBound) {
                            this._echoBound = true;
                            this.echoConnected = true; // Assume connected; could add connection callbacks
                            if (this.current) {
                                this.bindConversationChannel(this.current.id);
                            }
                        } else {
                            setTimeout(tryBind, 400);
                        }
                    };
                    tryBind();
                },
                bindConversationChannel(id) {
                    if (!window.Echo || !this.echoConnected) return;
                    if (this._chan) {
                        this._chan.stopListening('.message.created').stopListening('.message.deleted').stopListening(
                            '.conversation.user.typing');
                    }
                    this._chan = window.Echo.private(`conversation.${id}`)
                        .listen('.message.created', (m) => {
                            this.onIncomingMessage(m);
                        })
                        .listen('.message.deleted', (p) => {
                            this.onDeleteEvent(p);
                        })
                        .listen('.conversation.user.typing', (e) => {
                            this.onTyping(e);
                        });
                },
                onIncomingMessage(m) {
                    // Avoid duplicates if polling also fetched it
                    if (this.messages.find(x => x.id === m.id)) return;
                    this.messages.push(m);
                    // Update preview if current
                    if (this.current && this.current.id === m.conversation_id) {
                        this.updatePreview(m);
                    }
                    this.$nextTick(() => {
                        const el = this.$refs.msgList;
                        if (el && (el.scrollHeight - el.scrollTop - el.clientHeight) < 120) {
                            this.scroll();
                        }
                    });
                },
                onDeleteEvent(p) {
                    const idx = this.messages.findIndex(x => x.id === p.id);
                    if (idx > -1) {
                        this.messages[idx].deleted = true;
                        this.messages[idx].body = null;
                        this.messages[idx].attachments = [];
                    }
                },
                onTyping(e) {
                    if (e.user_id === this.userId) return; // ignore self
                    if (!this.typingUsers.find(u => u.user_id === e.user_id)) this.typingUsers.push(e);
                    clearTimeout(this.typingTimers[e.user_id]);
                    this.typingTimers[e.user_id] = setTimeout(() => {
                        this.typingUsers = this.typingUsers.filter(u => u.user_id !== e.user_id);
                    }, 6000);
                },
                toggle() {
                    this.open = !this.open;
                    localStorage.setItem('chatWidgetOpen', this.open);
                    if (this.open && !this.conversations.length) this.loadConversations();
                },
                loadConversations() {
                    fetch('/messages', {
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => r.ok ? r.json() : Promise.reject())
                        .then(list => {
                            this.conversations = Array.isArray(list) ? list : [];
                            if (this.conversations.length && !this.current) {
                                this.selectConversation(this.conversations[0]);
                            }
                            this.schedulePoll();
                        })
                        .catch(() => {});
                },
                filteredConversations() {
                    if (!this.filter.trim()) return this.conversations;
                    const t = this.filter.toLowerCase();
                    return this.conversations.filter(c => c.title.toLowerCase().includes(t));
                },
                selectConversation(c) {
                    this.current = c;
                    this.messages = [];
                    this.draft = ''; // Clear draft field when switching
                    this.attachments = []; // Clear attachments
                    // Reset infinite scroll state
                    this.hasMoreMessages = true;
                    this.loadingOlder = false;
                    this.unbindScrollSentinel();

                    fetch(`/messages/conversations/${c.id}`)
                        .then(r => r.json())
                        .then(d => {
                            this.messages = d.messages;
                            this.scroll();
                            this.markRead();
                            if (this.echoConnected) {
                                this.bindConversationChannel(c.id);
                            }
                            // Load draft after conversation loaded
                            this.loadDraft();
                            // Fetch presence status
                            this.fetchPresence();
                            // Bind scroll observer for infinite scroll
                            this.$nextTick(() => {
                                this.bindScrollSentinel();
                            });
                        });
                },
                refreshCurrent() {
                    if (this.current) this.selectConversation(this.current);
                },
                formatTime(ts) {
                    const d = new Date(ts);
                    return d.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },
                csrf() {
                    return document.querySelector('meta[name="csrf-token"]').content;
                },
                sendMessage() {
                    if (!this.current || (!this.draft.trim() && !this.attachments.length)) return;
                    fetch(`/messages/conversations/${this.current.id}/items`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: JSON.stringify({
                                body: this.draft,
                                tokens: this.attachments.map(a => a.token).filter(Boolean)
                            })
                        })
                        .then(r => r.json()).then(m => {
                            if (m && m.id) {
                                this.messages.push(m);
                                this.draft = '';
                                this.attachments = [];
                                this.updatePreview(m);
                                this.scroll();
                                this.markRead();
                                // Clear draft after successful send
                                this.clearDraft();
                            }
                        });
                },
                updatePreview(m) {
                    const i = this.conversations.findIndex(x => x.id === this.current.id);
                    if (i > -1) {
                        this.conversations[i].last_message = {
                            body: m.body,
                            user_id: m.user_id,
                            at: m.at
                        };
                    }
                },
                markRead() {
                    if (!this.current) return;
                    fetch(`/messages/conversations/${this.current.id}/mark-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrf()
                        }
                    }).then(() => {
                        this.current.unread = 0;
                    });
                },
                scroll() {
                    this.$nextTick(() => {
                        const el = this.$refs.msgList;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                },
                schedulePoll() {
                    if (this.echoConnected) return;
                    if (this.pollTimer) clearTimeout(this.pollTimer);
                    this.pollTimer = setTimeout(() => this.poll(), 5000);
                },
                poll() {
                    if (this.echoConnected) {
                        return;
                    }
                    if (!this.current) {
                        this.schedulePoll();
                        return;
                    }
                    const last = this.messages.length ? this.messages[this.messages.length - 1].id : null;
                    fetch(`/messages/conversations/${this.current.id}/items${ last ? ('?after_id='+last):''}`)
                        .then(r => r.json()).then(d => {
                            if (d.messages && d.messages.length) {
                                this.messages.push(...d.messages);
                                this.scroll();
                                this.updatePreview(d.messages[d.messages.length - 1]);
                            }
                            this.refreshUnread();
                        })
                        .finally(() => this.schedulePoll());
                },
                refreshUnread() {
                    fetch('/messages', {
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => r.ok ? r.json() : Promise.reject())
                        .then(list => {
                            this.conversations = Array.isArray(list) ? list : this.conversations;
                        })
                        .catch(() => {});
                },
                canDelete(m) {
                    if (this.isSuperAdmin) return true;
                    return m.user.id === this.userId;
                },
                deleteMessage(m) {
                    fetch(`/messages/conversations/${this.current.id}/items/${m.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': this.csrf()
                        }
                    }).then(r => r.json()).then(resp => {
                        if (resp && resp.deleted) {
                            const i = this.messages.findIndex(x => x.id === m.id);
                            if (i > -1) {
                                this.messages[i].deleted = true;
                                this.messages[i].body = null;
                                this.messages[i].attachments = [];
                            }
                            this.toast('Message deleted', 'success');
                        }
                    });
                },
                // Typing indicator trigger
                typing() {
                    if (!this.current) return;
                    fetch(`/messages/conversations/${this.current.id}/typing`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrf()
                        }
                    });
                },
                // ============= Phase 11D: Infinite Scroll =============
                setupScrollObserver() {
                    this.$nextTick(() => {
                        const observerCallback = (entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting && this.current && this.hasMoreMessages && !
                                    this.loadingOlder) {
                                    this.loadOlderMessages();
                                }
                            });
                        };

                        this.scrollObserver = new IntersectionObserver(observerCallback, {
                            root: null,
                            rootMargin: '100px', // Load slightly before sentinel visible
                            threshold: 0.1
                        });
                    });
                },
                bindScrollSentinel() {
                    // Called after conversation loads or messages update
                    if (this.scrollObserver && this.$refs.scrollSentinel) {
                        this.scrollObserver.observe(this.$refs.scrollSentinel);
                    }
                },
                unbindScrollSentinel() {
                    if (this.scrollObserver && this.$refs.scrollSentinel) {
                        this.scrollObserver.unobserve(this.$refs.scrollSentinel);
                    }
                },
                async loadOlderMessages() {
                    if (!this.current || this.loadingOlder || !this.hasMoreMessages) return;
                    if (!this.messages.length) return; // Need at least one message

                    const firstMessageId = this.messages[0].id;
                    const msgList = this.$refs.msgList;
                    const previousHeight = msgList ? msgList.scrollHeight : 0;
                    const previousScrollTop = msgList ? msgList.scrollTop : 0;

                    this.loadingOlder = true;

                    try {
                        const response = await fetch(
                            `/messages/conversations/${this.current.id}/items/older?before_id=${firstMessageId}&limit=30`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            }
                        );

                        if (!response.ok) {
                            this.loadingOlder = false;
                            return;
                        }

                        const data = await response.json();
                        const olderMessages = data.messages || [];

                        if (olderMessages.length === 0) {
                            this.hasMoreMessages = false;
                            this.loadingOlder = false;
                            return;
                        }

                        // Prepend older messages
                        this.messages.unshift(...olderMessages);

                        // Restore scroll position after DOM update
                        this.$nextTick(() => {
                            if (msgList) {
                                const newHeight = msgList.scrollHeight;
                                const heightDelta = newHeight - previousHeight;
                                msgList.scrollTop = previousScrollTop + heightDelta;
                            }
                            this.loadingOlder = false;
                        });

                        // If we got fewer than requested, no more messages
                        if (olderMessages.length < 30) {
                            this.hasMoreMessages = false;
                        }
                    } catch (error) {
                        console.warn('Failed to load older messages:', error);
                        this.loadingOlder = false;
                    }
                },
                // ============= Phase 11B: Draft Management =============
                draftKey() {
                    return this.current ? `msg-draft-${this.current.id}` : null;
                },
                async loadDraft() {
                    if (!this.current) return;

                    // 1. Load from localStorage first (instant)
                    const localKey = this.draftKey();
                    const localDraft = localStorage.getItem(localKey);
                    let localData = null;
                    if (localDraft) {
                        try {
                            localData = JSON.parse(localDraft);
                            this.draft = localData.body || '';
                        } catch (e) {
                            console.warn('Invalid local draft', e);
                        }
                    }

                    // 2. Fetch from server (background sync for cross-device)
                    try {
                        const response = await fetch(`/messages/conversations/${this.current.id}/draft`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        if (response.ok) {
                            const serverData = await response.json();
                            const serverBody = serverData.body || '';

                            // If server has content and local is empty, use server
                            if (serverBody && !this.draft) {
                                this.draft = serverBody;
                                // Update local to match server
                                localStorage.setItem(localKey, JSON.stringify({
                                    body: serverBody,
                                    timestamp: Date.now()
                                }));
                            }
                            // If both exist, server wins (cross-device scenario)
                            else if (serverBody && localData) {
                                // You could add timestamp comparison here if needed
                                this.draft = serverBody;
                                localStorage.setItem(localKey, JSON.stringify({
                                    body: serverBody,
                                    timestamp: Date.now()
                                }));
                            }
                        }
                    } catch (error) {
                        console.warn('Could not fetch server draft:', error);
                        // Local draft already loaded, continue without server
                    }
                },
                saveDraftLocal() {
                    if (!this.current) return;
                    const key = this.draftKey();
                    if (!this.draft.trim()) {
                        localStorage.removeItem(key);
                        return;
                    }
                    localStorage.setItem(key, JSON.stringify({
                        body: this.draft,
                        timestamp: Date.now()
                    }));
                },
                saveDraftServer() {
                    if (!this.current || this.draftSaving) return;

                    // Clear any pending save
                    if (this.draftSaveTimer) {
                        clearTimeout(this.draftSaveTimer);
                        this.draftSaveTimer = null;
                    }

                    // Debounce server save (2 seconds)
                    this.draftSaveTimer = setTimeout(async () => {
                        if (!this.current) return;

                        this.draftSaving = true;
                        this.draftSaved = false;

                        try {
                            const response = await fetch(`/messages/conversations/${this.current.id}/draft`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrf(),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    body: this.draft
                                })
                            });

                            if (response.ok) {
                                this.draftSaved = true;
                                // Hide "saved" indicator after 2s
                                setTimeout(() => {
                                    this.draftSaved = false;
                                }, 2000);
                            }
                        } catch (error) {
                            console.warn('Could not save draft to server:', error);
                        } finally {
                            this.draftSaving = false;
                        }
                    }, 2000);
                },
                onDraftInput() {
                    // Save locally immediately (debounced by Alpine's input handler)
                    this.saveDraftLocal();
                    // Queue server save (throttled)
                    this.saveDraftServer();
                },
                async clearDraft() {
                    if (!this.current) return;

                    // Clear local
                    const key = this.draftKey();
                    localStorage.removeItem(key);

                    // Clear timers
                    if (this.draftSaveTimer) {
                        clearTimeout(this.draftSaveTimer);
                        this.draftSaveTimer = null;
                    }

                    // Clear server (fire and forget)
                    try {
                        await fetch(`/messages/conversations/${this.current.id}/draft`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf(),
                                'Accept': 'application/json'
                            }
                        });
                    } catch (error) {
                        console.warn('Could not clear server draft:', error);
                    }
                },
                // ============= End Draft Management =============
                // ============= Phase 11E: Presence Management =============
                startHeartbeat() {
                    // Send initial heartbeat
                    this.sendHeartbeat();

                    // Send heartbeat every 45 seconds
                    this.heartbeatTimer = setInterval(() => {
                        this.sendHeartbeat();
                    }, 45000);
                },
                async sendHeartbeat() {
                    try {
                        const response = await fetch('/messages/presence/heartbeat', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf(),
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.presenceTTL = data.ttl || 60;
                        }
                    } catch (error) {
                        console.warn('Heartbeat failed:', error);
                    }
                },
                async fetchPresence() {
                    if (!this.current) return;

                    try {
                        const response = await fetch(`/messages/conversations/${this.current.id}/presence`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.onlineUsers = data.online_user_ids || [];
                        }
                    } catch (error) {
                        console.warn('Could not fetch presence:', error);
                    }
                },
                isUserOnline(userId) {
                    return this.onlineUsers.includes(userId);
                },
                // ============= End Presence Management =============

                // ============= Phase 11C: Search Overlay =============
                setupKeyboardShortcuts() {
                    document.addEventListener('keydown', (e) => {
                        // Ctrl+K or Cmd+K to open search
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                            e.preventDefault();
                            this.openSearch();
                        }
                    });
                },
                openSearch() {
                    this.showSearch = true;
                    this.$nextTick(() => {
                        this.$refs.searchInput?.focus();
                    });
                },
                closeSearch() {
                    this.showSearch = false;
                    this.searchQuery = '';
                    this.globalSearchResults = [];
                    this.currentSearchResults = [];
                },
                async performSearch() {
                    const query = this.searchQuery.trim();
                    if (!query || query.length < 2) {
                        this.globalSearchResults = [];
                        this.currentSearchResults = [];
                        return;
                    }

                    this.searchInProgress = true;

                    try {
                        if (this.searchMode === 'all') {
                            const response = await fetch(`/messages/search?q=${encodeURIComponent(query)}`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                            if (response.ok) {
                                const data = await response.json();
                                // Map backend results to expected format
                                this.globalSearchResults = (data.results || []).map(r => ({
                                    message_id: r.id,
                                    conversation_id: r.conversation.id,
                                    conversation_title: r.conversation.title,
                                    user_name: r.user.name,
                                    body: r.body,
                                    created_at: r.at
                                }));
                            }
                        } else if (this.searchMode === 'current' && this.current) {
                            const response = await fetch(
                                `/messages/conversations/${this.current.id}/search?q=${encodeURIComponent(query)}`, {
                                    headers: {
                                        'Accept': 'application/json'
                                    }
                                });
                            if (response.ok) {
                                const data = await response.json();
                                // Map backend results to expected format
                                this.currentSearchResults = (data.results || []).map(r => ({
                                    message_id: r.id,
                                    conversation_id: this.current.id,
                                    user_name: r.user.name,
                                    body: r.body,
                                    created_at: r.at
                                }));
                            }
                        }
                    } catch (error) {
                        console.warn('Search failed:', error);
                    } finally {
                        this.searchInProgress = false;
                    }
                },
                highlightMatch(text, query) {
                    if (!text || !query) return text;
                    const regex = new RegExp(`(${query})`, 'gi');
                    return text.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
                },
                async jumpToMessage(result) {
                    // If result is from different conversation, switch to it
                    if (result.conversation_id !== this.current?.id) {
                        const targetConv = this.conversations.find(c => c.id === result.conversation_id);
                        if (targetConv) {
                            await this.selectConversation(targetConv);
                        }
                    }

                    // Check if message is already loaded
                    const messageExists = this.messages.find(m => m.id === result.message_id);

                    if (!messageExists) {
                        // Need to load older messages until we find it
                        await this.loadMessagesUntil(result.message_id);
                    }

                    // Scroll to message and highlight it
                    this.$nextTick(() => {
                        const messageEl = document.querySelector(`[data-message-id="${result.message_id}"]`);
                        if (messageEl) {
                            messageEl.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            messageEl.classList.add('bg-yellow-100');
                            setTimeout(() => {
                                messageEl.classList.remove('bg-yellow-100');
                            }, 2000);
                        }
                    });

                    this.closeSearch();
                },
                async loadMessagesUntil(targetMessageId) {
                    // Load older messages in batches until we find the target
                    let attempts = 0;
                    const maxAttempts = 10; // Prevent infinite loop

                    while (attempts < maxAttempts) {
                        const found = this.messages.find(m => m.id === targetMessageId);
                        if (found) break;

                        if (!this.hasMoreMessages) break;

                        await this.loadOlderMessages();
                        attempts++;

                        // Small delay to let DOM update
                        await new Promise(resolve => setTimeout(resolve, 100));
                    }
                },
                // ============= End Search Overlay =============

                handleFiles(e) {
                    const files = Array.from(e.target.files || []);
                    if (!files.length || !this.current) return;
                    const form = new FormData();
                    files.forEach(f => form.append('files[]', f));
                    fetch(`/messages/conversations/${this.current.id}/attachments`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: form
                        })
                        .then(r => r.json()).then(d => {
                            if (d.tokens) {
                                // Keep simple attachment entries for preview (no thumbnail yet)
                                d.tokens.forEach((t, i) => {
                                    this.attachments.push({
                                        token: t,
                                        name: (d.attachments && d.attachments[i]) ? d.attachments[i]
                                            .name : 'file',
                                        pending: true
                                    });
                                });
                            }
                        });
                },
                // Group creation
                startNewGroup() {
                    this.showNew = true;
                    this.newGroup = {
                        title: ''
                    };
                    this.chosen = [];
                    this.userTerm = '';
                    this.searchResults = [];
                    this.newError = '';
                    this.searchError = '';
                    // Prefetch initial suggestions (empty term now returns default list)
                    this.searching = true;
                    fetch('/messages/user-search')
                        .then(r => r.ok ? r.json() : Promise.reject())
                        .then(d => {
                            this.searchResults = d.users || [];
                        })
                        .catch(() => {
                            this.searchResults = [];
                        })
                        .finally(() => this.searching = false);
                },
                cancelNew() {
                    this.showNew = false;
                },
                searchUsers() {
                    this.searchError = '';
                    // Allow empty term to show default list
                    if (this.searchTimer) clearTimeout(this.searchTimer);
                    if (this.searchTimer) clearTimeout(this.searchTimer);
                    this.searchTimer = setTimeout(() => {
                        this.searching = true;
                        const url = this.userTerm.trim() ?
                            `/messages/user-search?q=${encodeURIComponent(this.userTerm.trim())}` :
                            '/messages/user-search';
                        fetch(url)
                            .then(r => {
                                if (r.status === 429) return r.json().then(j => Promise.reject({
                                    message: j.message || 'Too many searches, slow down'
                                }));
                                return r.ok ? r.json() : r.json().then(j => Promise.reject(j));
                            })
                            .then(d => {
                                this.searchResults = d.users || [];
                            })
                            .catch(e => {
                                this.searchResults = [];
                                this.searchError = e.message || 'Search failed';
                            })
                            .finally(() => this.searching = false);
                    }, 350);
                },
                toggleSelect(u) {
                    const idx = this.chosen.findIndex(x => x.id === u.id);
                    if (idx > -1) {
                        this.chosen.splice(idx, 1);
                    } else {
                        this.chosen.push(u);
                    }
                },
                get canCreateGroup() {
                    return this.newGroup.title.trim() && this.chosen.length >= 2;
                },
                createGroup() {
                    if (!this.canCreateGroup) return;
                    const ids = this.chosen.map(c => c.id);
                    let newId = null;
                    fetch('/messages/group', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: JSON.stringify({
                                title: this.newGroup.title,
                                participants: ids
                            })
                        })
                        .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
                        .then(d => {
                            newId = d.id;
                            this.showNew = false;
                            this.toast('Group created', 'success');
                            return fetch('/messages', {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                        })
                        .then(r => r.ok ? r.json() : Promise.reject())
                        .then(list => {
                            this.conversations = Array.isArray(list) ? list : [];
                            const created = this.conversations.find(c => c.id === newId);
                            if (created) {
                                this.selectConversation(created);
                            }
                        })
                        .catch(e => {
                            this.newError = (e && e.message) ? e.message : 'Failed';
                        });
                },
                toast(msg, type = 'info') {
                    const id = Date.now() + Math.random();
                    this.toasts.push({
                        id,
                        msg,
                        type
                    });
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 4000);
                },
                get totalUnread() {
                    return this.conversations.reduce((s, c) => s + (c.unread || 0), 0);
                }
            }
        }
    </script>
@endauth
