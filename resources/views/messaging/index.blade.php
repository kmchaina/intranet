@extends('layouts.dashboard')

@section('content')
    <div x-data="messagingApp()" x-init="init()" class="flex gap-6 max-w-7xl mx-auto h-[calc(100vh-12rem)]"
        style="position: relative; z-index: 1;">
        <!-- Conversations List -->
        <div class="w-80 flex flex-col bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200 bg-white">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Messages</h2>
                    </div>
                    <button @click="showNewChatChoice = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                        + New Chat
                    </button>
                </div>

                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input x-model="search" placeholder="Search conversations..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" />
                </div>
            </div>

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto" x-ref="conversationList">
                <template x-for="c in filteredConversations()" :key="c.id">
                    <div @click="selectConversation(c)"
                        class="p-4 cursor-pointer hover:bg-blue-50 transition-colors duration-200 border-b border-gray-100"
                        :class="{
                            'bg-blue-50 border-l-4 border-l-blue-600': current &&
                                current.id === c.id
                        }">
                        <div class="flex items-start gap-3">
                            <!-- Avatar -->
                            <div
                                class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                <span x-text="c.title.charAt(0).toUpperCase()"></span>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2 flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 truncate" x-text="c.title"></h3>
                                    </div>
                                    <span x-show="c.unread" x-text="c.unread"
                                        class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full min-w-[20px] text-center"></span>
                                </div>
                                <p class="text-sm text-gray-600 truncate mb-1"
                                    x-text="c.last_message ? (c.last_message.deleted ? 'Message deleted' : (c.last_message.body || '[Attachment]')) : 'No messages yet'">
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500"
                                        x-text="c.last_message ? formatTime(c.last_message.at) : ''"></span>
                                    <span class="text-xs text-gray-400"
                                        x-text="c.type === 'direct' ? 'Direct' : 'Group'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="filteredConversations().length===0" class="p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">No conversations found</p>
                    <p class="text-gray-400 text-xs mt-1">Start a new conversation to get started</p>
                </div>
            </div>
        </div>

        <!-- Conversation Pane -->
        <div class="flex-1 flex flex-col bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <template x-if="current">
                <div class="flex flex-col h-full">
                    <!-- Chat Header -->
                    <div class="p-6 border-b border-gray-100 bg-indigo-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <!-- Avatar -->
                                <div
                                    class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    <span x-text="current.title.charAt(0).toUpperCase()"></span>
                                </div>

                                <!-- Chat Info -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1">
                                        <h2 class="text-lg font-bold text-gray-900" x-text="current.title"></h2>
                                        <button x-show="current && current.type==='group' && canRename"
                                            @click="startRename()"
                                            class="text-xs text-indigo-600 hover:text-indigo-800 underline">Rename</button>
                                    </div>
                                    <div class="flex items-center gap-2" x-show="renaming">
                                        <input x-model="renameTitle"
                                            class="text-sm border border-gray-300 rounded-lg px-3 py-1 w-48 focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                                        <button @click="submitRename()"
                                            class="text-xs px-3 py-1 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                                            :disabled="renameSaving">Save</button>
                                        <button @click="cancelRename()"
                                            class="text-xs px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600"
                                        x-show="current && current.type === 'group'">
                                        <span x-text="participantNames(current)" class="text-gray-500"></span>
                                        <button type="button"
                                            class="text-indigo-600 hover:text-indigo-800 text-xs font-medium"
                                            @click="openParticipants()">
                                            • Manage
                                        </button>
                                    </div>
                                    <div class="text-xs text-red-600 mt-1" x-text="renameError"></div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2" x-data="{ showMenu: false }">
                                <!-- Menu Button -->
                                <div class="relative">
                                    <button @click="showMenu = !showMenu"
                                        class="p-2 hover:bg-white/50 rounded-lg transition-colors">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div x-show="showMenu" @click.away="showMenu = false" x-cloak
                                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">

                                        <!-- For Group Conversations -->
                                        <template x-if="current.type === 'group'">
                                            <div>
                                                <button @click="showMenu = false; leaveConversation()"
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                    </svg>
                                                    Leave Conversation
                                                </button>

                                                <button x-show="isConversationAdmin(current)"
                                                    @click="showMenu = false; deleteConversation()"
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2 border-t border-gray-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete Group (Admin)
                                                </button>
                                            </div>
                                        </template>

                                        <!-- For Direct Conversations -->
                                        <template x-if="current.type === 'direct'">
                                            <button @click="showMenu = false; deleteConversation()"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete Conversation
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Messages Area -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50" x-ref="messageList">
                        <!-- Phase 11D: Scroll sentinel for infinite scroll -->
                        <div x-ref="scrollSentinel" class="h-1"></div>

                        <!-- Phase 11D: Loading indicator -->
                        <div x-show="loadingOlder" x-transition class="text-center py-2">
                            <svg class="inline-block animate-spin w-4 h-4 text-indigo-600" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="ml-2 text-xs text-gray-500">Loading older messages...</span>
                        </div>

                        <!-- Phase 11D: "No more messages" indicator -->
                        <div x-show="!hasMoreMessages && messages.length > 0" class="text-center py-2">
                            <span class="text-xs text-gray-400">• Beginning of conversation •</span>
                        </div>

                        <template x-for="m in messages" :key="m.id">
                            <div class="flex group" :data-message-id="m.id"
                                @contextmenu.prevent="showMessageMenu($event, m)"
                                :class="{ 'justify-end': m.user.id === userId, 'justify-start': m.user.id !== userId }">
                                <div class="flex items-end gap-3 max-w-[70%]"
                                    :class="{ 'flex-row-reverse': m.user.id === userId }">
                                    <!-- Avatar -->
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0"
                                        :style="'background: ' + getUserColor(m.user.id)">
                                        <span x-text="m.user.name.charAt(0).toUpperCase()"></span>
                                    </div>

                                    <!-- Message Bubble -->
                                    <div class="relative">
                                        <div class="px-4 py-3 rounded-2xl shadow-sm cursor-pointer"
                                            :class="m.user.id === userId ?
                                                'bg-blue-600 text-white' :
                                                'bg-white border border-gray-200'">
                                            <!-- Sender name (only for others) -->
                                            <div x-show="m.user.id !== userId"
                                                class="text-xs font-medium text-gray-600 mb-1 flex items-center gap-1.5">
                                                <span x-text="m.user.name"></span>
                                                <!-- Online presence indicator -->
                                                <span x-show="isUserOnline(m.user.id)"
                                                    class="inline-block w-2 h-2 rounded-full bg-green-500"
                                                    title="Online"></span>
                                            </div>

                                            <!-- Message content -->
                                            <div class="text-sm leading-relaxed"
                                                :class="m.deleted ? (m.user.id === userId ? 'italic text-white/80' :
                                                    'italic text-gray-600') : ''"
                                                x-text="m.deleted ? 'Message deleted' : m.body"></div>

                                            <!-- Attachments -->
                                            <template x-if="m.attachments && m.attachments.length">
                                                <div class="mt-3 space-y-2">
                                                    <template x-for="a in m.attachments" :key="a.url">
                                                        <div class="flex items-center gap-2 p-2 rounded-lg"
                                                            :class="m.user.id === userId ? 'bg-white/20' : 'bg-gray-100'">
                                                            <svg class="w-4 h-4 flex-shrink-0"
                                                                :class="m.user.id === userId ? 'text-white' : 'text-gray-500'"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                            </svg>
                                                            <a :href="a.url" target="_blank"
                                                                class="text-xs truncate hover:underline"
                                                                :class="m.user.id === userId ? 'text-white' : 'text-gray-700'"
                                                                x-text="a.name"></a>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>

                                            <!-- Timestamp -->
                                            <div class="text-xs mt-2"
                                                :class="m.user.id === userId ? 'text-white/70' : 'text-gray-500'"
                                                x-text="formatTime(m.at)"></div>
                                        </div>

                                        <!-- Reactions Display -->
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            <template x-if="m.reactions && m.reactions.length > 0">
                                                <div class="flex flex-wrap gap-1">
                                                    <template x-for="reaction in m.reactions" :key="reaction.emoji">
                                                        <button @click="toggleReaction(m, reaction.emoji)"
                                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs transition-all hover:scale-110"
                                                            :class="reaction.user_reacted ?
                                                                'bg-indigo-100 border-2 border-indigo-500 text-indigo-700' :
                                                                'bg-gray-100 border border-gray-300 text-gray-600 hover:bg-gray-200'"
                                                            :title="reaction.users.join(', ')">
                                                            <span x-text="reaction.emoji"></span>
                                                            <span x-text="reaction.count" class="font-medium"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Context Menu for Messages -->
                        <div x-show="contextMenu.show" x-cloak @click.away="contextMenu.show = false"
                            class="fixed bg-white rounded-lg shadow-xl border border-gray-200 py-1 z-50 min-w-[180px]"
                            :style="`top: ${contextMenu.y}px; left: ${contextMenu.x}px;`">

                            <!-- Delete Option -->
                            <button x-show="contextMenu.message?.can_delete"
                                @click="confirmDeleteMessage(contextMenu.message)"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-red-50 flex items-center gap-3 text-red-600 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Message
                            </button>

                            <!-- React Option -->
                            <button @click="showReactionPicker(contextMenu.message)"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-3 text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Add Reaction
                            </button>

                            <!-- Reply Option (Future) -->
                            <button @click="replyToMessage(contextMenu.message)"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-3 text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                </svg>
                                Reply
                                <span class="ml-auto text-xs text-gray-400">(Soon)</span>
                            </button>

                            <!-- Copy Text -->
                            <button x-show="contextMenu.message?.body" @click="copyMessageText(contextMenu.message)"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-3 text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copy Text
                            </button>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div x-show="deleteConfirmation.show" x-cloak
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                            @click.self="deleteConfirmation.show = false">
                            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6" @click.stop>
                                <!-- Header -->
                                <div class="flex items-center gap-3 mb-4">
                                    <div
                                        class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">Delete Message</h3>
                                        <p class="text-sm text-gray-500">This action cannot be undone</p>
                                    </div>
                                </div>

                                <!-- Message Preview -->
                                <div x-show="deleteConfirmation.message?.body"
                                    class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-700 line-clamp-3"
                                        x-text="deleteConfirmation.message?.body"></p>
                                </div>

                                <!-- Warning Text -->
                                <p class="text-sm text-gray-600 mb-6">
                                    Are you sure you want to delete this message? This action is permanent and cannot be
                                    reversed.
                                </p>

                                <!-- Actions -->
                                <div class="flex gap-3 justify-end">
                                    <button @click="deleteConfirmation.show = false"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                        Cancel
                                    </button>
                                    <button
                                        @click="deleteMessage(deleteConfirmation.message); deleteConfirmation.show = false;"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                        Delete Message
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Reaction Picker Modal -->
                        <div x-show="reactionPicker.show" x-cloak
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                            @click.self="reactionPicker.show = false">
                            <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4" @click.stop>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Reaction</h3>

                                <!-- Quick Reactions -->
                                <div class="grid grid-cols-5 gap-3">
                                    <template x-for="emoji in ['👍', '❤️', '😂', '😮', '😢', '🎉', '🔥', '👏', '✅', '💯']">
                                        <button
                                            @click="toggleReaction(reactionPicker.message, emoji); reactionPicker.show = false;"
                                            class="aspect-square flex items-center justify-center text-4xl hover:bg-gray-100 rounded-lg transition-all hover:scale-110 active:scale-95">
                                            <span x-text="emoji"></span>
                                        </button>
                                    </template>
                                </div>

                                <button @click="reactionPicker.show = false"
                                    class="w-full mt-4 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                    Cancel
                                </button>
                            </div>
                        </div>

                        <!-- Empty state -->
                        <div x-show="messages.length === 0" class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">No messages yet</p>
                                <p class="text-gray-400 text-xs mt-1">Start the conversation below</p>
                            </div>
                        </div>
                    </div>
                    <!-- Message Composer -->
                    <div class="p-6 bg-white border-t border-gray-100">
                        <form @submit.prevent="sendMessage" class="space-y-4">
                            <!-- Upload Progress -->
                            <template x-if="uploading.length">
                                <div class="space-y-2">
                                    <template x-for="u in uploading" :key="u.id">
                                        <div
                                            class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate" x-text="u.name"></p>
                                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                                        :style="`width: ${u.progress}%`"></div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-500" x-text="u.progress + '%'"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Attachments Preview -->
                            <template x-if="attachments.length">
                                <div class="space-y-2">
                                    <p class="text-sm font-medium text-gray-700">Attachments:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="(a,i) in attachments" :key="a.url">
                                            <div
                                                class="flex items-center gap-2 bg-gray-100 px-3 py-2 rounded-lg border border-gray-200">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                <span class="text-sm text-gray-700 truncate max-w-[120px]"
                                                    x-text="a.name"></span>
                                                <button type="button" @click="removeAttachment(i)"
                                                    class="text-red-500 hover:text-red-700 p-1 hover:bg-red-50 rounded transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Error Message -->
                            <div x-show="uploadError" class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-600" x-text="uploadError"></p>
                            </div>

                            <!-- Composer -->
                            <div class="flex items-end gap-3">
                                <div class="flex-1">
                                    <textarea x-model="draft" rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                                        placeholder="Type your message..."></textarea>
                                </div>
                                <div class="flex items-center gap-2">
                                    <!-- Attach Button -->
                                    <button type="button" @click="$refs.fileInput.click()"
                                        class="p-3 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200"
                                        title="Attach files">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    </button>

                                    <!-- Send Button -->
                                    <button type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="sending || (!draft.trim() && attachmentTokens.length === 0)">
                                        <span x-show="!sending" class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Send
                                        </span>
                                        <span x-show="sending" class="flex items-center gap-2">
                                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Sending...
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <input type="file" multiple class="hidden" x-ref="fileInput"
                                @change="handleFiles($event)" />
                        </form>
                    </div>
                </div>
            </template>
            <!-- Empty State -->
            <div x-show="!current" class="flex-1 flex items-center justify-center bg-gray-50">
                <div class="text-center">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Welcome to Messages</h3>
                    <p class="text-gray-600 mb-6 max-w-sm">Select a conversation from the sidebar to start chatting, or
                        create a new group conversation.</p>
                    <button @click="showNewGroup = true"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Start New Conversation
                    </button>
                </div>
            </div>
        </div>

        <!-- New Chat Choice Modal -->
        <div x-show="showNewChatChoice" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 bg-indigo-50">
                    <h3 class="text-lg font-bold text-gray-900">Start a New Conversation</h3>
                    <p class="text-sm text-gray-600 mt-1">Choose how you want to chat</p>
                </div>

                <!-- Options -->
                <div class="p-6 space-y-3">
                    <!-- Direct Message -->
                    <button @click="showNewChatChoice = false; showDirectMessage = true"
                        class="w-full p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-1">Direct Message</h4>
                                <p class="text-sm text-gray-600">Start a one-on-one conversation</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </button>

                    <!-- Group Chat -->
                    <button @click="showNewChatChoice = false; showNewGroup = true"
                        class="w-full p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all text-left group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-1">Group Chat</h4>
                                <p class="text-sm text-gray-600">Create a group with multiple people</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </button>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-gray-100 bg-gray-50">
                    <button @click="showNewChatChoice = false"
                        class="w-full px-6 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Direct Message Modal -->
        <div x-show="showDirectMessage" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 bg-blue-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">New Direct Message</h3>
                            <p class="text-sm text-gray-600">Start a one-on-one conversation</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-6 space-y-6">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select a Person</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input x-model="directMessageSearchTerm" @input="searchDirectMessageUsers"
                                placeholder="Search by name or email..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div x-show="directMessageSearchResults.length"
                        class="border border-gray-200 rounded-xl max-h-64 overflow-y-auto">
                        <template x-for="u in directMessageSearchResults" :key="u.id">
                            <div @click="startDirectMessage(u.id)"
                                class="px-4 py-3 cursor-pointer hover:bg-blue-50 flex items-center gap-3 border-b border-gray-100 last:border-b-0 transition-colors">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                    :style="'background: ' + getUserColor(u.id)">
                                    <span x-text="u.name.charAt(0).toUpperCase()"></span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900" x-text="u.name"></p>
                                    <p class="text-xs text-gray-500" x-text="u.email"></p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </template>
                        <div x-show="directMessageSearchLoading" class="px-4 py-3 text-sm text-gray-500 text-center">
                            Searching...
                        </div>
                    </div>

                    <p class="text-xs text-gray-500">Search and select a person to start chatting</p>

                    <!-- Error Message -->
                    <div x-show="directMessageError" class="p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600" x-text="directMessageError"></p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-gray-100 bg-gray-50">
                    <button
                        @click="showDirectMessage = false; directMessageSearchTerm = ''; directMessageSearchResults = []; directMessageError = ''"
                        class="w-full px-6 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- New Group Modal -->
        <div x-show="showNewGroup" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 bg-indigo-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">New Group Conversation</h3>
                            <p class="text-sm text-gray-600">Create a group chat with your colleagues</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Group Name</label>
                        <input x-model="newGroup.title" placeholder="Enter group name..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Add Participants</label>

                        <!-- Search -->
                        <div class="relative mb-4">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input x-model="newGroupSearchTerm" @input="searchNewGroupUsers"
                                placeholder="Search by name or email..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                        </div>

                        <!-- Search Results -->
                        <div x-show="newGroupSearchResults.length"
                            class="border border-gray-200 rounded-xl max-h-48 overflow-y-auto mb-4">
                            <template x-for="u in newGroupSearchResults" :key="u.id">
                                <div @click="toggleNewGroupUser(u)"
                                    class="px-4 py-3 cursor-pointer hover:bg-indigo-50 flex items-center justify-between border-b border-gray-100 last:border-b-0"
                                    :class="{ 'bg-indigo-50': newGroupSelectedUsers.find(x => x.id === u.id) }">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs"
                                            :style="'background: ' + getUserColor(u.id)">
                                            <span x-text="u.name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900" x-text="u.name"></p>
                                            <p class="text-xs text-gray-500" x-text="u.email"></p>
                                        </div>
                                    </div>
                                    <div x-show="newGroupSelectedUsers.find(x=>x.id===u.id)"
                                        class="w-5 h-5 bg-indigo-600 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                            </template>
                            <div x-show="newGroupSearchLoading" class="px-4 py-3 text-sm text-gray-500 text-center">
                                Searching...</div>
                        </div>

                        <!-- Selected Users -->
                        <div x-show="newGroupSelectedUsers.length" class="space-y-3">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-700">Selected Participants:</p>
                                <span class="text-xs text-gray-500"
                                    x-text="`${newGroupSelectedUsers.length} selected`"></span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="u in newGroupSelectedUsers" :key="u.id">
                                    <div
                                        class="flex items-center gap-2 bg-indigo-100 text-indigo-800 px-3 py-2 rounded-lg text-sm">
                                        <span x-text="u.name"></span>
                                        <button @click.stop="toggleNewGroupUser(u)"
                                            class="text-indigo-600 hover:text-indigo-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">Search and select users to add to your group conversation</p>
                    </div>

                    <!-- Error Message -->
                    <div x-show="groupError" class="p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600" x-text="groupError"></p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-gray-100 bg-gray-50">
                    <!-- Requirements Info -->
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-xs text-blue-700">
                                <p class="font-medium mb-1">Group Requirements:</p>
                                <ul class="space-y-1">
                                    <li>• Group title is required</li>
                                    <li>• At least 2 participants must be selected</li>
                                    <li>• You will be automatically added as a member</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button @click="showNewGroup=false"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</button>
                        <button @click="createGroup"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50"
                            :disabled="creatingGroup || newGroupSelectedUsers.length < 2 || !newGroup.title.trim()">
                            <span x-show="!creatingGroup">Create Group</span>
                            <span x-show="creatingGroup" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Creating...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participants Modal (MOVED INSIDE x-data scope) -->
        <div x-show="showParticipants" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div
                class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-gray-100 flex flex-col max-h-[80vh] overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 bg-indigo-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Participants</h3>
                                <p class="text-sm text-gray-600">Manage group members</p>
                            </div>
                        </div>
                        <button @click="showParticipants=false"
                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <!-- Error Message -->
                    <div x-show="partError" class="p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600" x-text="partError"></p>
                    </div>

                    <!-- Loading State -->
                    <template x-if="partLoading">
                        <div class="flex items-center justify-center py-8">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 animate-spin text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span class="text-sm text-gray-600">Loading participants...</span>
                            </div>
                        </div>
                    </template>

                    <!-- Participants List -->
                    <template x-if="!partLoading">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-4">Current Members</h4>
                            <div class="space-y-3">
                                <template x-for="p in participants" :key="p.user_id">
                                    <div class="flex items-center justify-between p-4 rounded-xl border transition-all duration-200"
                                        :class="current && p.user_id === current.created_by ?
                                            'bg-amber-50 border-amber-200' :
                                            'bg-gray-50 border-gray-200'">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                                :style="'background: ' + getUserColor(p.user_id)">
                                                <span x-text="p.name.charAt(0).toUpperCase()"></span>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <p class="font-medium text-gray-900"
                                                        x-text="p.name + (p.user_id===userId ? ' (You)' : '')"></p>
                                                    <span x-show="current && p.user_id === current.created_by"
                                                        class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-amber-200 text-amber-900 font-semibold">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                        Admin
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500" x-text="p.role || 'Member'"></p>
                                            </div>
                                        </div>
                                        <button
                                            x-show="current && current.type==='group' && isConversationAdmin(current) && p.user_id!==userId && p.user_id!==current.created_by"
                                            @click="removeParticipant(p.user_id)"
                                            class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-lg transition-colors"
                                            title="Remove participant">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Add Participants Section -->
                    <template x-if="current && current.type==='group'">
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900">Add New Members</h4>

                            <!-- Search -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input x-model="searchTerm" @input="searchUsers" placeholder="Search by name or email..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            </div>

                            <!-- Search Results -->
                            <div x-show="searchResults.length"
                                class="border border-gray-200 rounded-xl max-h-48 overflow-y-auto">
                                <template x-for="u in searchResults" :key="u.id">
                                    <div @click="toggleSelectUser(u)"
                                        class="px-4 py-3 cursor-pointer hover:bg-indigo-50 flex items-center justify-between border-b border-gray-100 last:border-b-0"
                                        :class="{ 'bg-indigo-50': selectedAdd.find(x => x.id === u.id) }">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs"
                                                :style="'background: ' + getUserColor(u.id)">
                                                <span x-text="u.name.charAt(0).toUpperCase()"></span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900" x-text="u.name"></p>
                                                <p class="text-xs text-gray-500" x-text="u.email"></p>
                                            </div>
                                        </div>
                                        <div x-show="selectedAdd.find(x=>x.id===u.id)"
                                            class="w-5 h-5 bg-indigo-600 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="searchLoading" class="px-4 py-3 text-sm text-gray-500 text-center">
                                    Searching...
                                </div>
                            </div>

                            <!-- Selected Users -->
                            <div x-show="selectedAdd.length" class="space-y-3">
                                <p class="text-sm font-medium text-gray-700">Selected:</p>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="u in selectedAdd" :key="u.id">
                                        <div
                                            class="flex items-center gap-2 bg-indigo-100 text-indigo-800 px-3 py-2 rounded-lg text-sm">
                                            <span x-text="u.name"></span>
                                            <button @click.stop="toggleSelectUser(u)"
                                                class="text-indigo-600 hover:text-indigo-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <button @click="addSelectedUsers"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl"
                                    :disabled="!selectedAdd.length">
                                    Add Selected Members
                                </button>
                            </div>

                            <!-- Leave/Delete Conversation -->
                            <div class="pt-4 border-t border-gray-200 space-y-2">
                                <!-- Leave Group Conversation -->
                                <button x-show="current.type === 'group'" @click="leaveConversation"
                                    class="text-red-600 hover:text-red-800 hover:bg-red-50 px-4 py-2 rounded-xl text-sm font-medium transition-colors w-full text-left">
                                    Leave Conversation
                                </button>

                                <!-- Delete Group Conversation (Admin Only) -->
                                <button x-show="current.type === 'group' && isConversationAdmin(current)"
                                    @click="deleteConversation"
                                    class="text-red-600 hover:text-red-800 hover:bg-red-50 px-4 py-2 rounded-xl text-sm font-medium transition-colors w-full text-left flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Conversation (Admin)
                                </button>

                                <!-- Delete Direct Conversation (Anyone) -->
                                <button x-show="current.type === 'direct'" @click="deleteConversation"
                                    class="text-red-600 hover:text-red-800 hover:bg-red-50 px-4 py-2 rounded-xl text-sm font-medium transition-colors w-full text-left flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Conversation
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <!-- End Participants Modal -->

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

        <!-- Toast Container (MOVED INSIDE x-data scope) -->
        <div class="fixed top-20 right-4 space-y-3 pointer-events-none z-50" x-cloak>
            <template x-for="t in toasts" :key="t.id">
                <div class="px-4 py-3 rounded-xl shadow-lg text-sm text-white pointer-events-auto border border-white/20 backdrop-blur-sm transition-all duration-300 transform"
                    :class="{
                        'bg-indigo-600': t.type==='info',
                        'bg-green-600': t.type==='success',
                        'bg-red-600': t.type==='error'
                    }"
                    x-text="t.msg">
                </div>
            </template>
        </div>

        <!-- Confirmation Modal -->
        <div x-show="showConfirmModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="p-6 bg-red-50 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900" x-text="confirmModal.title"></h3>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <p class="text-gray-700" x-text="confirmModal.message"></p>
                </div>

                <!-- Footer -->
                <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <button @click="showConfirmModal = false; confirmModal.onCancel && confirmModal.onCancel()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                    <button @click="showConfirmModal = false; confirmModal.onConfirm && confirmModal.onConfirm()"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                        <span x-text="confirmModal.confirmText || 'Confirm'"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>
    <!-- End main x-data container -->

    <script>
        function messagingApp() {
            return {
                userId: {{ auth()->id() }},
                isSuperAdmin: {{ auth()->user()->isSuperAdmin() ? 'true' : 'false' }},
                conversations: @json($initialConversations ?? []),
                messages: [],
                current: null,
                draft: '',
                sending: false,
                pollTimer: null,
                search: '',
                showNewChatChoice: false,
                showNewGroup: false,
                showDirectMessage: false,
                directMessageSearchTerm: '',
                directMessageSearchResults: [],
                directMessageSearchLoading: false,
                directMessageError: '',
                showParticipants: false,
                participants: [],
                partLoading: false,
                addPartIds: '',
                partError: '',
                renaming: false,
                renameTitle: '',
                renameSaving: false,
                renameError: '',
                newGroup: {
                    title: '',
                    participants: ''
                },
                creatingGroup: false,
                groupError: '',
                // New group user search
                newGroupSearchTerm: '',
                newGroupSearchResults: [],
                newGroupSelectedUsers: [],
                newGroupSearchLoading: false,
                newGroupSearchTimer: null,
                attachments: [],
                attachmentTokens: [], // Store tokens for sending
                uploading: [],
                uploadError: '',
                // Phase 11E: Presence
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
                // Phase 11A: Real-Time Echo
                echoConnected: false,
                _echoBound: false,
                _chan: null,
                _currentChannelId: null,
                typingUsers: [],
                typingTimers: {},
                // Context Menu for Messages
                contextMenu: {
                    show: false,
                    x: 0,
                    y: 0,
                    message: null
                },
                // Delete Confirmation Modal
                deleteConfirmation: {
                    show: false,
                    message: null
                },
                // Reaction Picker Modal
                reactionPicker: {
                    show: false,
                    message: null
                },
                init() {
                    console.log('messagingApp init() called');
                    console.log('conversations:', this.conversations);
                    console.log('userId:', this.userId);

                    // Auto-select first conversation if exists
                    if (this.conversations.length) this.selectConversation(this.conversations[0]);
                    this.schedulePoll();
                    this.startHeartbeat();
                    this.setupScrollObserver(); // Phase 11D
                    this.setupKeyboardShortcuts(); // Phase 11C
                    this.setupEchoMonitor(); // Phase 11A
                },
                // Generate consistent color for each user based on their ID
                getUserColor(userId) {
                    // Professional solid color palette (no gradients)
                    const colors = [
                        '#6366f1', // Indigo
                        '#8b5cf6', // Purple  
                        '#ec4899', // Pink
                        '#f59e0b', // Amber
                        '#10b981', // Emerald
                        '#3b82f6', // Blue
                        '#06b6d4', // Cyan
                        '#14b8a6', // Teal
                        '#84cc16', // Lime
                        '#f97316', // Orange
                        '#a855f7', // Violet
                        '#0ea5e9', // Sky
                        '#22c55e', // Green
                        '#eab308', // Yellow
                        '#ef4444', // Red
                    ];

                    // Use user ID to pick a consistent color
                    const index = userId % colors.length;
                    return colors[index];
                },
                // Toasts
                toasts: [],
                // Confirmation Modal
                showConfirmModal: false,
                confirmModal: {
                    title: '',
                    message: '',
                    confirmText: 'Confirm',
                    onConfirm: null,
                    onCancel: null
                },
                pushToast(msg, type = 'info') {
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
                showConfirm(title, message, onConfirm, confirmText = 'Confirm') {
                    this.confirmModal = {
                        title: title,
                        message: message,
                        confirmText: confirmText,
                        onConfirm: onConfirm,
                        onCancel: null
                    };
                    this.showConfirmModal = true;
                },
                schedulePoll() {
                    if (this.echoConnected) return; // Phase 11A: Don't poll if Echo connected
                    if (this.pollTimer) clearTimeout(this.pollTimer);
                    this.pollTimer = setTimeout(() => this.poll(), 4000);
                },
                poll() {
                    if (this.echoConnected) return; // Phase 11A: Don't poll if Echo connected
                    if (!this.current) {
                        this.schedulePoll();
                        return;
                    }
                    const lastId = this.messages.length ? this.messages[this.messages.length - 1].id : null;
                    const params = lastId ? ('?after_id=' + lastId) : '';
                    fetch(`/messages/conversations/${this.current.id}/items${params}`)
                        .then(r => {
                            if (!r.ok) {
                                console.log('🔄 Poll response not ok:', r.status, '[FIXED VERSION 12:40]');
                                throw new Error('Poll failed: ' + r.status);
                            }
                            return r.json();
                        })
                        .then(d => {
                            if (d && d.messages && d.messages.length) {
                                this.messages.push(...d.messages);
                                this.scrollToBottom();
                                this.updateConversationPreview(d.messages[d.messages.length - 1]);
                            }
                            this.refreshConversationUnreadCounts();
                        })
                        .catch((err) => {
                            console.warn('⚠️ Poll error (will retry) [FIXED VERSION 12:40]:', err.message);
                        })
                        .finally(() => this.schedulePoll());
                },
                selectConversation(c) {
                    this.current = c;
                    this.cancelRename();
                    this.messages = [];
                    this.draft = '';
                    this.hasMoreMessages = true; // Phase 11D: Reset for new conversation
                    this.loadingOlder = false;
                    fetch(`/messages/conversations/${c.id}/items`)
                        .then(r => r.json())
                        .then(d => {
                            this.messages = d.messages;
                            this.scrollToBottom();
                            this.markRead();
                            this.fetchPresence();
                            this.bindScrollSentinel(); // Phase 11D: Setup observer
                            // Phase 11A: Echo disabled until properly configured
                            // if (this.echoConnected && window.Echo) {
                            //     this.bindConversationChannel(c.id);
                            // }
                        });
                },
                refreshCurrent() {
                    if (this.current) this.selectConversation(this.current);
                },
                markRead() {
                    if (!this.current) return;
                    fetch(`/messages/conversations/${this.current.id}/mark-read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf()
                            }
                        })
                        .then(() => {
                            this.current.unread = 0;
                        });
                },
                sendMessage() {
                    if (!this.current || (!this.draft.trim() && !this.attachmentTokens.length)) return;
                    this.sending = true;

                    const payload = {
                        body: this.draft || null
                    };

                    // Include tokens if there are attachments
                    if (this.attachmentTokens.length > 0) {
                        payload.tokens = this.attachmentTokens;
                    }

                    console.log('📤 Sending message...', payload);

                    fetch(`/messages/conversations/${this.current.id}/items`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(r => {
                            console.log('📥 Response status:', r.status);
                            if (!r.ok) {
                                return r.text().then(text => {
                                    console.error('❌ Server error response:', text);
                                    // Try to extract error message from HTML
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(text, 'text/html');
                                    const errorMsg = doc.querySelector('.exception_message, h1')?.textContent ||
                                        'Server error';
                                    throw new Error('Failed to send message: ' + r.status + ' - ' + errorMsg);
                                });
                            }
                            return r.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.error('❌ Invalid JSON response:', text.substring(0, 200));
                                    throw new Error('Server returned invalid response');
                                }
                            });
                        })
                        .then(m => {
                            console.log('✅ Message received:', m);
                            if (m && m.id) {
                                const newMessage = {
                                    id: m.id,
                                    body: m.body,
                                    attachments: m.attachments || [],
                                    can_delete: m.can_delete || false,
                                    user: {
                                        id: m.user.id,
                                        name: m.user.name
                                    },
                                    at: m.at
                                };
                                console.log('➕ Adding message to list:', newMessage);
                                this.messages.push(newMessage);
                                console.log('📊 Total messages:', this.messages.length);
                                this.draft = '';
                                this.attachments = [];
                                this.attachmentTokens = [];
                                this.$nextTick(() => {
                                    this.scrollToBottom();
                                });
                                this.updateConversationPreview(m);
                                this.markRead();
                            } else {
                                console.error('❌ Invalid message response:', m);
                            }
                        })
                        .catch(err => {
                            console.error('❌ Send message error:', err);
                            this.pushToast('Failed to send message', 'error');
                        })
                        .finally(() => this.sending = false);
                },
                handleFiles(e) {
                    const files = Array.from(e.target.files || []);
                    if (!files.length || !this.current) return;
                    this.uploadError = '';
                    const form = new FormData();
                    files.forEach(f => form.append('files[]', f));
                    const id = Date.now();
                    files.forEach(f => this.uploading.push({
                        id: id + f.name,
                        name: f.name,
                        progress: 0
                    }));

                    console.log('📎 Uploading files...', files.map(f => f.name));

                    fetch(`/messages/conversations/${this.current.id}/attachments`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: form
                        })
                        .then(r => {
                            console.log('📎 Upload response status:', r.status);
                            return r.ok ? r.json() : r.json().then(j => Promise.reject(j));
                        })
                        .then(data => {
                            console.log('📎 Upload successful:', data);
                            // Store tokens for sending, and attachments for preview
                            (data.tokens || []).forEach(token => this.attachmentTokens.push(token));
                            (data.attachments || []).forEach(a => this.attachments.push(a));
                            console.log('📎 Tokens stored:', this.attachmentTokens);
                            console.log('📎 Attachments preview:', this.attachments);
                        })
                        .catch(err => {
                            console.error('❌ Upload error:', err);
                            this.uploadError = err.message || 'Upload failed';
                        })
                        .finally(() => {
                            this.uploading = [];
                            e.target.value = '';
                        });
                },
                removeAttachment(i) {
                    this.attachments.splice(i, 1);
                    this.attachmentTokens.splice(i, 1);
                },
                updateConversationPreview(m) {
                    const idx = this.conversations.findIndex(x => x.id === this.current.id);
                    if (idx > -1) {
                        this.conversations[idx].last_message = {
                            body: m.body,
                            user_id: m.user_id,
                            at: m.at
                        };
                    }
                },
                refreshConversationUnreadCounts() {
                    return fetch('/messages')
                        .then(r => r.json())
                        .then(list => {
                            this.conversations = list;
                            return list;
                        });
                },
                participantNames(c) {
                    if (!c.participants) return '';
                    const names = Object.values(c.participants);

                    // Extract first names only
                    const firstNames = names.map(name => name.split(' ')[0]);

                    // Show first 3, then "+ X more"
                    if (firstNames.length <= 3) {
                        return firstNames.join(', ');
                    }

                    const visible = firstNames.slice(0, 3);
                    const remaining = firstNames.length - 3;
                    return `${visible.join(', ')} +${remaining} more`;
                },
                isConversationAdmin(c) {
                    // User is admin if they created the group or are super admin
                    return c.created_by === this.userId || this.isSuperAdmin;
                },
                getAdminName(c) {
                    // Get admin name from participants
                    if (!c.participants || !c.created_by) return 'Admin';
                    return c.participants[c.created_by] || 'Admin';
                },
                filteredConversations() {
                    if (!this.search.trim()) return this.conversations;
                    const term = this.search.toLowerCase();
                    return this.conversations.filter(c => c.title.toLowerCase().includes(term));
                },
                formatTime(ts) {
                    const d = new Date(ts);
                    return d.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },
                csrf() {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    console.log('CSRF token:', token);
                    return token;
                },
                testAuth() {
                    console.log('Testing authentication...');
                    fetch('/messages', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf()
                            }
                        })
                        .then(r => {
                            console.log('Auth test response status:', r.status);
                            console.log('Auth test response headers:', Object.fromEntries(r.headers.entries()));
                            if (r.ok) {
                                return r.json();
                            } else {
                                return r.text().then(text => {
                                    console.error('Auth test error response:', text);
                                    throw new Error('Auth test failed: ' + r.status);
                                });
                            }
                        })
                        .then(data => {
                            console.log('Auth test successful:', data);
                            alert('Authentication is working! Found ' + data.length + ' conversations.');
                        })
                        .catch(e => {
                            console.error('Auth test error:', e);
                            alert('Authentication failed: ' + e.message);
                        });
                },
                createGroup() {
                    if (!this.newGroup.title.trim()) {
                        this.groupError = 'Group title is required';
                        return;
                    }

                    const participants = this.newGroupSelectedUsers.map(u => u.id);

                    if (participants.length < 2) {
                        this.groupError = 'Please select at least 2 participants for the group';
                        return;
                    }

                    this.creatingGroup = true;
                    this.groupError = '';

                    console.log('Creating group with:', {
                        title: this.newGroup.title,
                        participants: participants
                    });

                    fetch('/messages/group', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                title: this.newGroup.title,
                                participants
                            })
                        })
                        .then(r => {
                            console.log('Group creation response status:', r.status);
                            console.log('Response headers:', Object.fromEntries(r.headers.entries()));

                            if (!r.ok) {
                                // Log the response text for debugging
                                return r.text().then(text => {
                                    console.error('Error response text:', text);
                                    try {
                                        const json = JSON.parse(text);
                                        return Promise.reject(json);
                                    } catch (e) {
                                        return Promise.reject({
                                            message: 'Server returned HTML instead of JSON. Status: ' +
                                                r.status
                                        });
                                    }
                                });
                            }

                            return r.json();
                        })
                        .then(d => {
                            console.log('Group created successfully:', d);

                            // Store the group title before clearing the form
                            const createdGroupTitle = this.newGroup.title;

                            this.showNewGroup = false;
                            this.newGroup = {
                                title: '',
                                participants: ''
                            };
                            this.newGroupSelectedUsers = [];
                            this.newGroupSearchResults = [];
                            this.newGroupSearchTerm = '';

                            // Refresh conversations list
                            return fetch('/messages', {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            }).then(r => r.json()).then(list => {
                                console.log('Updated conversations list:', list);
                                this.conversations = list; // Update list FIRST

                                // Find and select the newly created conversation
                                const newConversation = list.find(c => c.title === createdGroupTitle);
                                if (newConversation) {
                                    this.selectConversation(newConversation);
                                } else if (list.length > 0) {
                                    this.selectConversation(list[0]);
                                }

                                this.pushToast('Group created successfully!', 'success');
                            });
                        })
                        .catch(e => {
                            console.error('Group creation error:', e);
                            let errorMessage = 'Failed to create group';

                            if (e.message) {
                                if (e.message.includes('participants')) {
                                    errorMessage = 'Please select at least 2 participants for the group';
                                } else if (e.message.includes('title')) {
                                    errorMessage = 'Group title is required';
                                } else {
                                    errorMessage = e.message;
                                }
                            }

                            this.groupError = errorMessage;
                            this.pushToast(errorMessage, 'error');
                        })
                        .finally(() => this.creatingGroup = false);
                },
                // New group user search functions
                searchNewGroupUsers() {
                    if (this.newGroupSearchTimer) clearTimeout(this.newGroupSearchTimer);
                    if (!this.newGroupSearchTerm.trim()) {
                        this.newGroupSearchResults = [];
                        return;
                    }
                    this.newGroupSearchTimer = setTimeout(() => {
                        this.newGroupSearchLoading = true;
                        fetch(`/messages/user-search?q=${encodeURIComponent(this.newGroupSearchTerm.trim())}`)
                            .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
                            .then(d => {
                                this.newGroupSearchResults = d.users || [];
                            })
                            .catch(() => this.newGroupSearchResults = [])
                            .finally(() => this.newGroupSearchLoading = false);
                    }, 300);
                },
                toggleNewGroupUser(u) {
                    if (this.newGroupSelectedUsers.find(x => x.id === u.id)) {
                        this.newGroupSelectedUsers = this.newGroupSelectedUsers.filter(x => x.id !== u.id);
                    } else {
                        this.newGroupSelectedUsers.push(u);
                    }
                },
                searchDirectMessageUsers() {
                    if (this.directMessageSearchTimer) clearTimeout(this.directMessageSearchTimer);
                    if (!this.directMessageSearchTerm.trim()) {
                        this.directMessageSearchResults = [];
                        return;
                    }
                    this.directMessageSearchTimer = setTimeout(() => {
                        this.directMessageSearchLoading = true;
                        fetch(`/messages/user-search?q=${encodeURIComponent(this.directMessageSearchTerm.trim())}`)
                            .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
                            .then(d => {
                                this.directMessageSearchResults = d.users || [];
                            })
                            .catch(() => this.directMessageSearchResults = [])
                            .finally(() => this.directMessageSearchLoading = false);
                    }, 300);
                },
                startDirectMessage(userId) {
                    this.directMessageError = '';
                    fetch('/messages/direct', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: JSON.stringify({
                                user_id: userId
                            })
                        })
                        .then(r => r.ok ? r.json() : Promise.reject('Failed to create conversation'))
                        .then(d => {
                            console.log('Direct conversation created/found:', d);

                            this.showDirectMessage = false;
                            this.directMessageSearchTerm = '';
                            this.directMessageSearchResults = [];

                            // Reload conversations list to get the updated data
                            return fetch('/messages', {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            }).then(r => r.json()).then(list => {
                                console.log('Updated conversations list:', list);
                                this.conversations = list;

                                // Find and select the conversation
                                const conversation = list.find(c => c.id === d.id);
                                if (conversation) {
                                    this.selectConversation(conversation);
                                    this.pushToast('Chat opened successfully!', 'success');
                                } else if (list.length > 0) {
                                    this.selectConversation(list[0]);
                                }
                            });
                        })
                        .catch(e => {
                            this.directMessageError = e.message || 'Failed to start conversation';
                            this.pushToast('Failed to start conversation', 'error');
                        });
                },
                openParticipants() {
                    console.log('openParticipants called, current:', this.current);
                    console.log('showParticipants before:', this.showParticipants);

                    if (!this.current) {
                        console.log('No current conversation, returning');
                        return;
                    }

                    console.log('Setting showParticipants to true');
                    this.showParticipants = true;
                    console.log('showParticipants after:', this.showParticipants);

                    // Force a small delay to ensure the modal shows
                    setTimeout(() => {
                        console.log('showParticipants after timeout:', this.showParticipants);
                        this.loadParticipants();
                    }, 100);
                },
                loadParticipants() {
                    this.partLoading = true;
                    this.partError = '';
                    fetch(`/messages/conversations/${this.current.id}/participants`)
                        .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
                        .then(d => {
                            this.participants = d.participants || [];
                        })
                        .catch(e => this.partError = e.message || 'Failed to load participants')
                        .finally(() => this.partLoading = false);
                },
                addParticipants() {
                    if (!this.addPartIds.trim()) return;
                    const ids = this.addPartIds.split(',').map(s => s.trim()).filter(Boolean).map(Number).filter(n => !
                        isNaN(n));
                    if (!ids.length) return;
                    fetch(`/messages/conversations/${this.current.id}/participants`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: JSON.stringify({
                                user_ids: ids
                            })
                        }).then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
                        .then(d => {
                            this.participants = d.participants;
                            this.addPartIds = '';
                            this.refreshConversationUnreadCounts();
                        })
                        .catch(e => this.partError = e.message || 'Add failed');
                },
                removeParticipant(uid) {
                    fetch(`/messages/conversations/${this.current.id}/participants/${uid}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf()
                            }
                        }).then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
                        .then(d => {
                            this.participants = d.participants;
                            this.refreshConversationUnreadCounts();
                        })
                        .catch(e => this.partError = e.message || 'Remove failed');
                },
                leaveConversation() {
                    fetch(`/messages/conversations/${this.current.id}/leave`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf()
                            }
                        }).then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
                        .then(() => {
                            this.showParticipants = false;
                            this.current = null;
                            this.messages = [];
                            this.refreshConversationUnreadCounts();
                        })
                        .catch(e => this.partError = e.message || 'Leave failed');
                },
                deleteConversation() {
                    const conversationType = this.current.type === 'group' ? 'group conversation' : 'conversation';
                    const conversationTitle = this.current.title;

                    this.showConfirm(
                        'Delete Conversation',
                        `Are you sure you want to delete "${conversationTitle}"? This action cannot be undone.`,
                        () => {
                            fetch(`/messages/conversations/${this.current.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': this.csrf(),
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(r => {
                                    if (r.status === 403) {
                                        throw new Error(
                                            'Only the group creator or admin can delete this conversation');
                                    }
                                    if (!r.ok) {
                                        throw new Error('Delete failed: ' + r.status);
                                    }
                                    return r.json();
                                })
                                .then(() => {
                                    // Remove from conversations list
                                    this.conversations = this.conversations.filter(c => c.id !== this.current.id);

                                    // Close modal and clear current
                                    this.showParticipants = false;
                                    this.current = null;
                                    this.messages = [];

                                    // Select first conversation if available
                                    if (this.conversations.length > 0) {
                                        this.selectConversation(this.conversations[0]);
                                    }

                                    this.pushToast('Conversation deleted successfully', 'success');
                                })
                                .catch(e => {
                                    this.partError = e.message || 'Delete failed';
                                    this.pushToast(e.message || 'Delete failed', 'error');
                                });
                        },
                        'Delete'
                    );
                },
                get canRename() {
                    // Server doesn't expose creator id directly in conversation list now; rely on participants map containing current user.
                    // Minimal approach: allow rename button for all group convos; server will enforce policy.
                    return this.current && this.current.type === 'group';
                },
                startRename() {
                    this.renameError = '';
                    this.renameTitle = this.current.title;
                    this.renaming = true;
                    setTimeout(() => {
                        // no-op placeholder for focus if desired
                    }, 10);
                },
                cancelRename() {
                    this.renaming = false;
                    this.renameSaving = false;
                    this.renameError = '';
                },
                submitRename() {
                    if (!this.renameTitle.trim()) {
                        this.renameError = 'Title required';
                        return;
                    }
                    this.renameSaving = true;
                    this.renameError = '';
                    fetch(`/messages/conversations/${this.current.id}/title`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: JSON.stringify({
                                title: this.renameTitle.trim()
                            })
                        })
                        .then(r => {
                            if (r.status === 403) {
                                throw new Error('Only the group creator or admin can rename this group');
                            }
                            if (!r.ok) {
                                throw new Error('Rename failed: ' + r.status);
                            }
                            return r.json();
                        })
                        .then(d => {
                            this.current.title = d.title;
                            // update list item
                            const idx = this.conversations.findIndex(x => x.id === this.current.id);
                            if (idx > -1) this.conversations[idx].title = d.title;
                            this.cancelRename();
                            this.pushToast('Group renamed successfully', 'success');
                        })
                        .catch(e => this.renameError = e.message || 'Rename failed')
                        .finally(() => this.renameSaving = false);
                },
                deleteMessage(m) {
                    fetch(`/messages/conversations/${this.current.id}/items/${m.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': this.csrf()
                        }
                    }).then(r => {
                        if (r.ok) {
                            const idx = this.messages.findIndex(x => x.id === m.id);
                            if (idx > -1) this.messages.splice(idx, 1);
                            this.pushToast('Message deleted', 'success');
                        } else {
                            this.pushToast('Delete failed', 'error');
                        }
                    });
                },
                // Context Menu Methods
                showMessageMenu(event, message) {
                    console.log('📋 Context menu for message:', message);
                    console.log('📋 can_delete:', message.can_delete);
                    this.contextMenu.message = message;
                    this.contextMenu.x = event.clientX;
                    this.contextMenu.y = event.clientY;
                    this.contextMenu.show = true;
                },
                confirmDeleteMessage(message) {
                    this.deleteConfirmation.message = message;
                    this.deleteConfirmation.show = true;
                    this.contextMenu.show = false;
                },
                replyToMessage(message) {
                    // Future implementation
                    this.pushToast('Reply feature coming soon!', 'info');
                    this.contextMenu.show = false;
                },
                showReactionPicker(message) {
                    this.reactionPicker.message = message;
                    this.reactionPicker.show = true;
                    this.contextMenu.show = false;
                },
                async toggleReaction(message, emoji) {
                    const url = `/messages/${message.id}/reactions`;
                    try {
                        const resp = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                emoji
                            })
                        });

                        if (resp.ok) {
                            const data = await resp.json();
                            // Reload current conversation messages
                            if (this.current) {
                                await this.selectConversation(this.current);
                            }
                            this.pushToast(data.added ? 'Reaction added' : 'Reaction removed', 'success');
                        } else {
                            const errorData = await resp.json().catch(() => ({
                                message: 'Unknown error'
                            }));
                            console.error('Reaction failed:', resp.status, errorData);
                            this.pushToast(errorData.message || 'Failed to update reaction', 'error');
                        }
                    } catch (err) {
                        console.error('Toggle reaction error:', err);
                        this.pushToast('Failed to update reaction: ' + err.message, 'error');
                    }
                },
                copyMessageText(message) {
                    if (message.body) {
                        navigator.clipboard.writeText(message.body).then(() => {
                            this.pushToast('Message copied to clipboard', 'success');
                        }).catch(() => {
                            this.pushToast('Failed to copy message', 'error');
                        });
                    }
                    this.contextMenu.show = false;
                },
                // User search for adding participants
                searchTerm: '',
                searchResults: [],
                selectedAdd: [],
                searchLoading: false,
                searchTimer: null,
                searchUsers() {
                    if (this.searchTimer) clearTimeout(this.searchTimer);
                    if (!this.searchTerm.trim()) {
                        this.searchResults = [];
                        return;
                    }
                    this.searchTimer = setTimeout(() => {
                        this.searchLoading = true;
                        fetch(
                                `/messages/conversations/${this.current.id}/user-search?q=${encodeURIComponent(this.searchTerm.trim())}`
                            )
                            .then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
                            .then(d => {
                                this.searchResults = d.users || [];
                            })
                            .catch(() => this.searchResults = [])
                            .finally(() => this.searchLoading = false);
                    }, 300);
                },
                toggleSelectUser(u) {
                    if (this.selectedAdd.find(x => x.id === u.id)) {
                        this.selectedAdd = this.selectedAdd.filter(x => x.id !== u.id);
                    } else {
                        this.selectedAdd.push(u);
                    }
                },
                addSelectedUsers() {
                    if (!this.selectedAdd.length) return;
                    const ids = this.selectedAdd.map(u => u.id);
                    fetch(`/messages/conversations/${this.current.id}/participants`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: JSON.stringify({
                                user_ids: ids
                            })
                        }).then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
                        .then(d => {
                            this.participants = d.participants;
                            this.selectedAdd = [];
                            this.searchResults = [];
                            this.searchTerm = '';
                            this.pushToast('Participants added', 'success');
                            this.refreshConversationUnreadCounts();
                        })
                        .catch(e => this.partError = e.message || 'Add failed');
                },
                // ============= Phase 11E: Presence =============
                startHeartbeat() {
                    console.log('✅ Starting heartbeat...');
                    this.sendHeartbeat();
                    this.heartbeatTimer = setInterval(() => {
                        this.sendHeartbeat();
                    }, 45000);
                },
                async sendHeartbeat() {
                    console.log('💓 Sending heartbeat... [BUILD: 12:15]');
                    console.log('CSRF:', this.csrf());
                    try {
                        console.log('About to fetch...');
                        const response = await fetch('/messages/presence/heartbeat', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf(),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        console.log('Fetch complete. Response:', response);
                        console.log('Response status:', response.status, 'OK?', response.ok);
                        if (response.ok) {
                            const data = await response.json();
                            console.log('✅ Heartbeat success:', data);
                            this.presenceTTL = data.ttl || 60;
                        } else {
                            console.error('❌ Heartbeat bad response:', response.status);
                        }
                    } catch (error) {
                        console.error('❌ Heartbeat exception:', error);
                    }
                },
                async fetchPresence() {
                    if (!this.current) return;
                    console.log('🔍 Fetching presence for conversation:', this.current.id);
                    try {
                        const response = await fetch(`/messages/conversations/${this.current.id}/presence`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            console.log('✅ Presence data:', data);
                            this.onlineUsers = data.online_user_ids || [];
                            console.log('👥 Online users:', this.onlineUsers);
                        }
                    } catch (error) {
                        console.error('❌ Fetch presence failed:', error);
                    }
                },
                isUserOnline(userId) {
                    const online = this.onlineUsers.includes(userId);
                    console.log(`🟢 User ${userId} online?`, online, 'Online list:', this.onlineUsers);
                    return online;
                },
                // Phase 11D: Infinite Scroll
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
                    const msgList = this.$refs.messageList;
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
                // Phase 11C: Search Modal
                setupKeyboardShortcuts() {
                    document.addEventListener('keydown', (e) => {
                        // Ctrl+K or Cmd+K to open search
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                            e.preventDefault();
                            this.openSearch();
                        }
                        // Escape to close search
                        if (e.key === 'Escape' && this.showSearch) {
                            this.closeSearch();
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
                // Phase 11A: Real-Time Echo/Pusher
                setupEchoMonitor() {
                    // Echo/Pusher disabled until properly configured
                    console.log('ℹ️ Echo/Pusher not configured - using polling instead');
                    return;

                    /* Uncomment when Echo is configured:
                    const tryBind = () => {
                        if (window.Echo && window.__echoReady && !this._echoBound) {
                            this._echoBound = true;
                            this.echoConnected = true;
                            console.log('✅ Echo connected');
                            if (this.current) {
                                this.bindConversationChannel(this.current.id);
                            }
                        } else {
                            setTimeout(tryBind, 400);
                        }
                    };
                    tryBind();
                    */
                },
                bindConversationChannel(id) {
                    if (!window.Echo || !this.echoConnected) return;

                    // Leave previous channel if it exists
                    if (this._chan && this._currentChannelId) {
                        console.log(`📡 Leaving conversation.${this._currentChannelId}`);
                        // Echo channels are automatically cleaned up, just null the reference
                        this._chan = null;
                    }

                    // Join new channel
                    this._currentChannelId = id;
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
                    console.log(`📡 Listening to conversation.${id}`);
                },
                onIncomingMessage(m) {
                    // Avoid duplicates
                    if (this.messages.find(x => x.id === m.id)) return;
                    this.messages.push(m);
                    // Update preview if current
                    if (this.current && this.current.id === m.conversation_id) {
                        this.updateConversationPreview(m);
                    }
                    this.$nextTick(() => {
                        const el = this.$refs.messageList;
                        if (el && (el.scrollHeight - el.scrollTop - el.clientHeight) < 120) {
                            this.scrollToBottom();
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
                scrollToBottom() {
                    this.$nextTick(() => {
                        const el = this.$refs.messageList;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                }
            }
        }
    </script>
@endsection
