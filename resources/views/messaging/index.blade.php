@extends('layouts.dashboard')

@section('content')
    <div x-data="messagingApp()" x-init="init();
    console.log('Alpine.js initialized, messagingApp:', messagingApp);
    console.log('Alpine object:', window.Alpine);
    console.log('Current user:', {{ auth()->id() }})" class="flex h-full gap-6 max-w-7xl mx-auto"
        style="position: relative; z-index: 1;">
        <!-- Test Button -->
        <div class="fixed top-4 left-4 z-50 space-y-2">
            <button @click="console.log('Test button clicked'); alert('Alpine.js is working!')"
                class="bg-red-500 text-white px-4 py-2 rounded block">
                Test Alpine.js
            </button>
            <button @click="testAuth()" class="bg-blue-500 text-white px-4 py-2 rounded block">
                Test Auth
            </button>
            <button @click="console.log('Simple test clicked'); alert('Simple test works!')"
                class="bg-green-500 text-white px-4 py-2 rounded block">
                Simple Test
            </button>
        </div>

        <!-- Conversations List -->
        <div class="w-80 flex flex-col bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Messages</h2>
                            <p class="text-sm text-gray-600">Stay connected with your team</p>
                        </div>
                    </div>
                    <button @click="showNewGroup = true"
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Chat
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
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white shadow-sm" />
                </div>
            </div>

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto" x-ref="conversationList">
                <template x-for="c in filteredConversations" :key="c.id">
                    <div @click="selectConversation(c)"
                        class="p-4 cursor-pointer hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-200 border-b border-gray-50"
                        :class="{
                            'bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-l-indigo-500': current &&
                                current.id === c.id
                        }">
                        <div class="flex items-start gap-3">
                            <!-- Avatar -->
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                <span x-text="c.title.charAt(0).toUpperCase()"></span>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="font-semibold text-gray-900 truncate" x-text="c.title"></h3>
                                    <span x-show="c.unread" x-text="c.unread"
                                        class="bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full min-w-[20px] text-center"></span>
                                </div>
                                <p class="text-sm text-gray-600 truncate mb-1"
                                    x-text="c.last_message ? (c.last_message.body || '[Attachment]') : 'No messages yet'">
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
                <div x-show="filteredConversations.length===0" class="p-8 text-center">
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
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <!-- Avatar -->
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
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
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <span x-text="participantNames(current)"></span>
                                        <button type="button" class="text-indigo-600 hover:text-indigo-800 underline"
                                            x-show="current && current.type==='group'"
                                            @click="console.log('Manage button clicked'); openParticipants()"
                                            style="position: relative; z-index: 1000; pointer-events: auto;">Manage</button>
                                    </div>
                                    <div class="text-xs text-red-600 mt-1" x-text="renameError"></div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                <button @click="refreshCurrent()"
                                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-white rounded-lg transition-all duration-200"
                                    title="Refresh">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                                <!-- Debug button -->
                                <button @click="console.log('Debug button clicked'); showParticipants = !showParticipants"
                                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200"
                                    title="Debug: Toggle Participants Modal"
                                    style="position: relative; z-index: 1000; pointer-events: auto;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Messages Area -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50" x-ref="messageList">
                        <template x-for="m in messages" :key="m.id">
                            <div class="flex group"
                                :class="{ 'justify-end': m.user.id === userId, 'justify-start': m.user.id !== userId }">
                                <div class="flex items-end gap-3 max-w-[70%]"
                                    :class="{ 'flex-row-reverse': m.user.id === userId }">
                                    <!-- Avatar -->
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                        <span x-text="m.user.name.charAt(0).toUpperCase()"></span>
                                    </div>

                                    <!-- Message Bubble -->
                                    <div class="relative">
                                        <div class="px-4 py-3 rounded-2xl shadow-sm"
                                            :class="m.user.id === userId ?
                                                'bg-gradient-to-r from-indigo-500 to-purple-600 text-white' :
                                                'bg-white border border-gray-200'">
                                            <!-- Sender name (only for others) -->
                                            <div x-show="m.user.id !== userId"
                                                class="text-xs font-medium text-gray-600 mb-1" x-text="m.user.name"></div>

                                            <!-- Message content -->
                                            <div class="text-sm leading-relaxed" x-text="m.body"></div>

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

                                        <!-- Delete button -->
                                        <button x-show="canDelete(m)" @click="deleteMessage(m)"
                                            class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 text-xs leading-6 text-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">×</button>
                                    </div>
                                </div>
                            </div>
                        </template>

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
                                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="sending || (!draft.trim() && attachments.length === 0)">
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
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Welcome to Messages</h3>
                    <p class="text-gray-600 mb-6 max-w-sm">Select a conversation from the sidebar to start chatting, or
                        create a new group conversation.</p>
                    <button @click="showNewGroup = true"
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Start New Conversation
                    </button>
                </div>
            </div>
        </div>

        <!-- New Group Modal -->
        <div x-show="showNewGroup" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
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
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
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
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50"
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
    </div>

    <!-- Participants Modal -->
    <div x-show="showParticipants" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div
            class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-gray-100 flex flex-col max-h-[80vh] overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            <span x-text="p.name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900"
                                                x-text="p.name + (p.user_id===userId ? ' (You)' : '')"></p>
                                            <p class="text-xs text-gray-500" x-text="p.role || 'Member'"></p>
                                        </div>
                                    </div>
                                    <button
                                        x-show="current && current.type==='group' && p.user_id!==userId && p.user_id!==current.created_by"
                                        @click="removeParticipant(p.user_id)"
                                        class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-lg transition-colors"
                                        title="Remove participant">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
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
                            <div x-show="searchLoading" class="px-4 py-3 text-sm text-gray-500 text-center">Searching...
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
                                class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl"
                                :disabled="!selectedAdd.length">
                                Add Selected Members
                            </button>
                        </div>

                        <!-- Leave Conversation -->
                        <div class="pt-4 border-t border-gray-200">
                            <button @click="leaveConversation"
                                class="text-red-600 hover:text-red-800 hover:bg-red-50 px-4 py-2 rounded-xl text-sm font-medium transition-colors">
                                Leave Conversation
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

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
                showNewGroup: false,
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
                uploading: [],
                uploadError: '',
                init() {
                    console.log('messagingApp init() called');
                    console.log('conversations:', this.conversations);
                    console.log('userId:', this.userId);

                    // Auto-select first conversation if exists
                    if (this.conversations.length) this.selectConversation(this.conversations[0]);
                    this.schedulePoll();
                },
                // Toasts
                toasts: [],
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
                schedulePoll() {
                    if (this.pollTimer) clearTimeout(this.pollTimer);
                    this.pollTimer = setTimeout(() => this.poll(), 4000);
                },
                poll() {
                    if (!this.current) {
                        this.schedulePoll();
                        return;
                    }
                    const lastId = this.messages.length ? this.messages[this.messages.length - 1].id : null;
                    const params = lastId ? ('?after_id=' + lastId) : '';
                    fetch(`/messages/conversations/${this.current.id}/items${params}`)
                        .then(r => r.json())
                        .then(d => {
                            if (d.messages && d.messages.length) {
                                this.messages.push(...d.messages);
                                this.scrollToBottom();
                                this.updateConversationPreview(d.messages[d.messages.length - 1]);
                            }
                            this.refreshConversationUnreadCounts();
                        })
                        .catch(() => {})
                        .finally(() => this.schedulePoll());
                },
                selectConversation(c) {
                    this.current = c;
                    this.cancelRename();
                    this.messages = [];
                    fetch(`/messages/conversations/${c.id}`)
                        .then(r => r.json())
                        .then(d => {
                            this.messages = d.messages;
                            this.scrollToBottom();
                            this.markRead();
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
                    if (!this.current || (!this.draft.trim() && !this.attachments.length)) return;
                    this.sending = true;
                    fetch(`/messages/conversations/${this.current.id}/items`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: JSON.stringify({
                                body: this.draft,
                                attachments: this.attachments
                            })
                        })
                        .then(r => r.json())
                        .then(m => {
                            if (m && m.id) {
                                this.messages.push({
                                    id: m.id,
                                    body: m.body,
                                    attachments: m.attachments,
                                    user: {
                                        id: this.userId,
                                        name: 'You'
                                    },
                                    at: m.at
                                });
                                this.draft = '';
                                this.scrollToBottom();
                                this.updateConversationPreview(m);
                                this.markRead();
                            }
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
                    fetch(`/messages/conversations/${this.current.id}/attachments`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf()
                            },
                            body: form
                        }).then(r => r.ok ? r.json() : r.json().then(j => Promise.reject(j)))
                        .then(data => {
                            (data.attachments || []).forEach(a => this.attachments.push(a));
                        })
                        .catch(err => {
                            this.uploadError = err.message || 'Upload failed';
                        })
                        .finally(() => {
                            this.uploading = [];
                            e.target.value = '';
                        });
                },
                removeAttachment(i) {
                    this.attachments.splice(i, 1);
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
                    fetch('/messages')
                        .then(r => r.json())
                        .then(list => {
                            this.conversations = list;
                        });
                },
                participantNames(c) {
                    if (!c.participants) return '';
                    return Object.values(c.participants).join(', ');
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
                            return fetch('/messages').then(r => r.json()).then(list => {
                                // Find and select the newly created conversation
                                const newConversation = list.find(c => c.title === createdGroupTitle);
                                if (newConversation) {
                                    this.selectConversation(newConversation);
                                } else if (list.length > 0) {
                                    this.selectConversation(list[0]);
                                }
                                return list;
                            });
                        })
                        .then(list => {
                            console.log('Updated conversations list:', list);
                            this.conversations = list;
                            this.pushToast('Group created successfully!', 'success');
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
                        }).then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)))
                        .then(d => {
                            this.current.title = d.title;
                            // update list item
                            const idx = this.conversations.findIndex(x => x.id === this.current.id);
                            if (idx > -1) this.conversations[idx].title = d.title;
                            this.cancelRename();
                        })
                        .catch(e => this.renameError = e.message || 'Rename failed')
                        .finally(() => this.renameSaving = false);
                },
                canDelete(m) {
                    if (this.isSuperAdmin) return true; // UI override; server policy still authoritative
                    if (m.user.id !== this.userId) return false;
                    const five = 5 * 60 * 1000; // 5 minute window
                    return (Date.now() - new Date(m.at).getTime()) < five;
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
                }
                // User search for adding participants
                ,
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
                }
            }
        }
    </script>

    <!-- Toast Container -->
    <div class="fixed top-4 right-4 space-y-3 pointer-events-none z-50" x-cloak>
        <template x-for="t in toasts" :key="t.id">
            <div class="px-4 py-3 rounded-xl shadow-lg text-sm text-white pointer-events-auto border border-white/20 backdrop-blur-sm transition-all duration-300 transform"
                :class="{
                    'bg-gradient-to-r from-indigo-600 to-purple-600': t.type==='info',
                    'bg-gradient-to-r from-green-600 to-emerald-600': t.type==='success',
                    'bg-gradient-to-r from-red-600 to-pink-600': t.type==='error'
                }"
                x-text="t.msg">
            </div>
        </template>
    </div>
@endsection
