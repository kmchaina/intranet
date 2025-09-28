@extends('layouts.dashboard')

@section('content')
<div x-data="messagingApp()" x-init="init()" class="flex h-full gap-4">
    <!-- Conversations List -->
    <div class="w-72 flex flex-col border rounded bg-white shadow-sm overflow-hidden">
        <div class="p-3 border-b flex items-center justify-between">
            <h2 class="font-semibold text-sm">Conversations</h2>
            <button @click="showNewGroup = true" class="text-xs px-2 py-1 bg-indigo-500 text-white rounded">New</button>
        </div>
        <div class="p-2">
            <input x-model="search" placeholder="Search" class="w-full text-sm border rounded px-2 py-1" />
        </div>
        <div class="flex-1 overflow-y-auto divide-y" x-ref="conversationList">
            <template x-for="c in filteredConversations" :key="c.id">
                <div @click="selectConversation(c)" class="p-3 text-sm cursor-pointer hover:bg-indigo-50 flex flex-col" :class="{'bg-indigo-100': current && current.id===c.id}">
                    <div class="flex justify-between items-center">
                        <span x-text="c.title" class="font-medium truncate"></span>
                        <span x-show="c.unread" x-text="c.unread" class="ml-2 text-xs bg-red-500 text-white rounded-full px-2 py-0.5"></span>
                    </div>
                    <div class="text-xs text-gray-500 truncate" x-text="c.last_message ? (c.last_message.body || '[Attachment]') : 'No messages yet'"></div>
                </div>
            </template>
            <div x-show="filteredConversations.length===0" class="p-4 text-xs text-gray-500">No conversations</div>
        </div>
    </div>

    <!-- Conversation Pane -->
    <div class="flex-1 flex flex-col border rounded bg-white shadow-sm">
        <template x-if="current">
            <div class="flex flex-col h-full">
                <div class="p-3 border-b flex items-center justify-between gap-2">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2" x-show="!renaming">
                            <h2 class="font-semibold text-sm" x-text="current.title"></h2>
                            <button x-show="current && current.type==='group' && canRename" @click="startRename()" class="text-[10px] text-indigo-600 underline">Rename</button>
                        </div>
                        <div class="flex items-center gap-2" x-show="renaming">
                            <input x-model="renameTitle" class="text-sm border rounded px-2 py-0.5 w-48" />
                            <button @click="submitRename()" class="text-[10px] px-2 py-1 bg-indigo-600 text-white rounded" :disabled="renameSaving">Save</button>
                            <button @click="cancelRename()" class="text-[10px] px-2 py-1 border rounded">Cancel</button>
                        </div>
                        <div class="text-xs text-gray-500 flex items-center gap-2">
                            <span x-text="participantNames(current)"></span>
                            <button type="button" @click="openParticipants()" class="underline text-indigo-600" x-show="current && current.type==='group'">Manage</button>
                        </div>
                        <div class="text-[10px] text-red-600" x-text="renameError"></div>
                    </div>
                    <div class="flex gap-2">
                        <button @click="refreshCurrent()" class="text-xs px-2 py-1 border rounded">Refresh</button>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto p-3 space-y-3" x-ref="messageList">
                    <template x-for="m in messages" :key="m.id">
                        <div class="text-sm group relative" :class="{'text-right': m.user.id === userId}">
                            <div class="inline-block max-w-xs px-3 py-2 rounded-lg relative" :class="m.user.id===userId ? 'bg-indigo-500 text-white' : 'bg-gray-100'">
                                <div class="text-[10px] opacity-70 mb-0.5" x-text="m.user.name"></div>
                                <div x-text="m.body"></div>
                                <template x-if="m.attachments && m.attachments.length">
                                    <ul class="mt-2 space-y-1">
                                        <template x-for="a in m.attachments" :key="a.url">
                                            <li><a :href="a.url" target="_blank" class="underline text-xs" x-text="a.name"></a></li>
                                        </template>
                                    </ul>
                                </template>
                                <div class="text-[10px] opacity-50 mt-1" x-text="formatTime(m.at)"></div>
                                <!-- Delete button (visible if own & within window) -->
                                <button x-show="canDelete(m)" @click="deleteMessage(m)" class="hidden group-hover:inline-block absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 text-[10px] leading-5 text-center">×</button>
                            </div>
                        </div>
                    </template>
                </div>
                <form @submit.prevent="sendMessage" class="border-t p-3 flex flex-col gap-2">
                    <div class="flex gap-2 items-start">
                        <textarea x-model="draft" rows="2" class="flex-1 text-sm border rounded px-2 py-1" placeholder="Type a message..."></textarea>
                        <div class="flex flex-col gap-2 w-32">
                            <button type="button" @click="$refs.fileInput.click()" class="text-xs border rounded px-2 py-1 bg-white hover:bg-gray-50">Attach</button>
                            <button type="submit" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded disabled:opacity-40" :disabled="sending || (!draft.trim() && attachments.length===0)">
                                <span x-show="!sending">Send</span>
                                <span x-show="sending">Sending...</span>
                            </button>
                        </div>
                    </div>
                    <input type="file" multiple class="hidden" x-ref="fileInput" @change="handleFiles($event)" />
                    <template x-if="uploading.length">
                        <div class="space-y-1">
                            <template x-for="u in uploading" :key="u.id">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="truncate max-w-[140px]" x-text="u.name"></span>
                                    <span x-text="u.progress + '%'" class="text-gray-500"></span>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="attachments.length">
                        <ul class="flex flex-wrap gap-2 text-xs">
                            <template x-for="(a,i) in attachments" :key="a.url">
                                <li class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded">
                                    <span x-text="a.name" class="truncate max-w-[120px]"></span>
                                    <button type="button" @click="removeAttachment(i)" class="text-red-500">&times;</button>
                                </li>
                            </template>
                        </ul>
                    </template>
                    <div x-text="uploadError" class="text-xs text-red-600"></div>
                </form>
            </div>
        </template>
        <div x-show="!current" class="flex-1 flex items-center justify-center text-sm text-gray-500">
            Select a conversation
        </div>
    </div>

    <!-- New Group Modal -->
    <div x-show="showNewGroup" class="fixed inset-0 bg-black/30 flex items-center justify-center" style="display:none;">
        <div class="bg-white w-full max-w-md rounded shadow p-4 flex flex-col gap-3">
            <h3 class="font-semibold text-sm">New Group Conversation</h3>
            <label class="text-xs flex flex-col gap-1">
                <span>Title</span>
                <input x-model="newGroup.title" class="border rounded px-2 py-1 text-sm" />
            </label>
            <label class="text-xs flex flex-col gap-1">
                <span>Participant IDs (comma separated)</span>
                <input x-model="newGroup.participants" placeholder="e.g. 2,3,5" class="border rounded px-2 py-1 text-sm" />
            </label>
            <div class="flex justify-end gap-2 mt-2">
                <button @click="showNewGroup=false" class="text-xs px-3 py-1 border rounded">Cancel</button>
                <button @click="createGroup" class="text-xs px-3 py-1 bg-indigo-600 text-white rounded" :disabled="creatingGroup">Create</button>
            </div>
            <div x-text="groupError" class="text-xs text-red-600"></div>
        </div>
    </div>
</div>

<!-- Participants Modal -->
<div x-show="showParticipants" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" style="display:none;">
    <div class="bg-white w-full max-w-lg rounded shadow-lg flex flex-col max-h-[80vh]">
        <div class="px-4 py-2 border-b flex justify-between items-center">
            <h3 class="text-sm font-semibold">Participants</h3>
            <button class="text-xs" @click="showParticipants=false">Close</button>
        </div>
        <div class="p-4 space-y-3 overflow-y-auto">
            <div x-text="partError" class="text-xs text-red-600"></div>
            <template x-if="partLoading"><div class="text-xs text-gray-500">Loading...</div></template>
            <template x-if="!partLoading">
                <ul class="divide-y text-sm">
                    <template x-for="p in participants" :key="p.user_id">
                        <li class="py-2 flex justify-between items-center">
                            <div class="flex flex-col">
                                <span x-text="p.name + (p.user_id===userId ? ' (You)' : '')"></span>
                                <span class="text-[10px] text-gray-500" x-text="p.role || ''"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button x-show="current && current.type==='group' && p.user_id!==userId && p.user_id!==current.created_by" @click="removeParticipant(p.user_id)" class="text-xs text-red-600 hover:underline">Remove</button>
                            </div>
                        </li>
                    </template>
                </ul>
            </template>
            <template x-if="current && current.type==='group'">
                <div class="space-y-2">
                    <div class="text-xs font-semibold">Add Participants</div>
                    <div class="space-y-2">
                        <input x-model="searchTerm" @input="searchUsers" placeholder="Search name or email" class="w-full border rounded px-2 py-1 text-xs" />
                        <div class="border rounded max-h-40 overflow-y-auto" x-show="searchResults.length">
                            <template x-for="u in searchResults" :key="u.id">
                                <div @click="toggleSelectUser(u)" class="px-2 py-1 text-xs cursor-pointer hover:bg-indigo-50 flex justify-between" :class="{'bg-indigo-100': selectedAdd.find(x=>x.id===u.id)}">
                                    <span x-text="u.name + ' (' + u.email + ')'" class="truncate"></span>
                                    <span x-show="selectedAdd.find(x=>x.id===u.id)" class="text-indigo-600">✔</span>
                                </div>
                            </template>
                            <div x-show="searchLoading" class="px-2 py-1 text-[10px] text-gray-500">Searching...</div>
                        </div>
                        <div class="flex flex-wrap gap-1" x-show="selectedAdd.length">
                            <template x-for="u in selectedAdd" :key="u.id">
                                <span class="bg-indigo-600 text-white text-[10px] px-2 py-0.5 rounded"> <span x-text="u.name"></span> <button @click.stop="toggleSelectUser(u)" class="ml-1">×</button></span>
                            </template>
                        </div>
                        <div class="flex justify-end">
                            <button @click="addSelectedUsers" class="text-xs px-3 py-1 bg-indigo-600 text-white rounded" :disabled="!selectedAdd.length">Add Selected</button>
                        </div>
                    </div>
                    <button @click="leaveConversation" class="text-xs text-red-600 underline" x-show="current && current.type==='group'">Leave Conversation</button>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function messagingApp() {
    return {
        userId: {{ auth()->id() }},
        conversations: @json($initialConversations ?? []),
        messages: [],
        current: null,
        draft: '',
        sending: false,
        pollTimer: null,
        search: '',
        showNewGroup: false,
            showParticipants:false,
            participants: [],
            partLoading:false,
            addPartIds:'',
            partError:'',
        renaming:false,
        renameTitle:'',
        renameSaving:false,
        renameError:'',
        newGroup: { title: '', participants: ''},
        creatingGroup: false,
        groupError: '',
        attachments: [],
    uploading: [],
    uploadError: '',
        init() {
            // Auto-select first conversation if exists
            if (this.conversations.length) this.selectConversation(this.conversations[0]);
            this.schedulePoll();
        },
        // Toasts
        toasts: [],
        pushToast(msg, type='info'){
            const id = Date.now()+Math.random();
            this.toasts.push({id,msg,type});
            setTimeout(()=>{ this.toasts = this.toasts.filter(t=>t.id!==id); }, 4000);
        },
        schedulePoll() {
            if (this.pollTimer) clearTimeout(this.pollTimer);
            this.pollTimer = setTimeout(() => this.poll(), 4000);
        },
        poll() {
            if (!this.current) { this.schedulePoll(); return; }
            const lastId = this.messages.length ? this.messages[this.messages.length-1].id : null;
            const params = lastId ? ('?after_id=' + lastId) : '';
            fetch(`/messages/conversations/${this.current.id}/items${params}`)
                .then(r => r.json())
                .then(d => {
                    if (d.messages && d.messages.length) {
                        this.messages.push(...d.messages);
                        this.scrollToBottom();
                        this.updateConversationPreview(d.messages[d.messages.length-1]);
                    }
                    this.refreshConversationUnreadCounts();
                })
                .catch(()=>{})
                .finally(()=> this.schedulePoll());
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
            fetch(`/messages/conversations/${this.current.id}/mark-read`, {method:'POST', headers: {'X-CSRF-TOKEN': this.csrf()}})
                .then(()=>{
                    this.current.unread = 0;
                });
        },
        sendMessage() {
            if (!this.current || (!this.draft.trim() && !this.attachments.length)) return;
            this.sending = true;
            fetch(`/messages/conversations/${this.current.id}/items`, {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': this.csrf() },
                body: JSON.stringify({ body: this.draft, attachments: this.attachments })
            })
            .then(r => r.json())
            .then(m => {
                if (m && m.id) {
                    this.messages.push({ id: m.id, body: m.body, attachments: m.attachments, user: {id: this.userId, name: 'You'}, at: m.at });
                    this.draft='';
                    this.scrollToBottom();
                    this.updateConversationPreview(m);
                    this.markRead();
                }
            })
            .finally(()=> this.sending = false);
        },
        handleFiles(e) {
            const files = Array.from(e.target.files || []);
            if (!files.length || !this.current) return;
            this.uploadError='';
            const form = new FormData();
            files.forEach(f=> form.append('files[]', f));
            const id = Date.now();
            files.forEach(f=> this.uploading.push({id: id+f.name, name: f.name, progress: 0}));
            fetch(`/messages/conversations/${this.current.id}/attachments`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': this.csrf() },
                body: form
            }).then(r => r.ok ? r.json() : r.json().then(j=>Promise.reject(j)))
            .then(data => {
                (data.attachments || []).forEach(a => this.attachments.push(a));
            })
            .catch(err => { this.uploadError = err.message || 'Upload failed'; })
            .finally(() => {
                this.uploading = [];
                e.target.value='';
            });
        },
        removeAttachment(i) { this.attachments.splice(i,1); },
        updateConversationPreview(m) {
            const idx = this.conversations.findIndex(x=>x.id===this.current.id);
            if (idx>-1) {
                this.conversations[idx].last_message = { body: m.body, user_id: m.user_id, at: m.at };
            }
        },
        refreshConversationUnreadCounts() {
            fetch('/messages')
                .then(r=>r.json())
                .then(list => { this.conversations = list; });
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
            return d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        },
        csrf() { return document.querySelector('meta[name="csrf-token"]').getAttribute('content'); },
        createGroup() {
            if (!this.newGroup.title.trim()) return;
            this.creatingGroup = true;
            const participants = this.newGroup.participants.split(',').map(s=>s.trim()).filter(Boolean);
            fetch('/messages/group', {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': this.csrf() },
                body: JSON.stringify({ title: this.newGroup.title, participants })
            })
            .then(r=> r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
            .then(d => {
                this.showNewGroup = false;
                this.newGroup = { title:'', participants:''};
                return fetch('/messages').then(r=>r.json());
            })
            .then(list => { this.conversations = list; if (list.length) this.selectConversation(list[0]); })
            .catch(e => { this.groupError = e.message || 'Failed'; })
            .finally(()=> this.creatingGroup=false);
        }
        ,openParticipants(){
            if(!this.current) return;
            this.showParticipants=true; this.loadParticipants();
        }
        ,loadParticipants(){
            this.partLoading=true; this.partError='';
            fetch(`/messages/conversations/${this.current.id}/participants`)
              .then(r=> r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
              .then(d=> { this.participants = d.participants || []; })
              .catch(e=> this.partError = e.message || 'Failed to load participants')
              .finally(()=> this.partLoading=false);
        }
        ,addParticipants(){
            if(!this.addPartIds.trim()) return;
            const ids = this.addPartIds.split(',').map(s=>s.trim()).filter(Boolean).map(Number).filter(n=>!isNaN(n));
            if(!ids.length) return;
            fetch(`/messages/conversations/${this.current.id}/participants`, {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN': this.csrf()},
                body: JSON.stringify({user_ids: ids})
            }).then(r=> r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
            .then(d=> { this.participants = d.participants; this.addPartIds=''; this.refreshConversationUnreadCounts(); })
            .catch(e=> this.partError = e.message || 'Add failed');
        }
        ,removeParticipant(uid){
            fetch(`/messages/conversations/${this.current.id}/participants/${uid}`, {
                method:'DELETE', headers:{'X-CSRF-TOKEN': this.csrf()}
            }).then(r=> r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
            .then(d=> { this.participants = d.participants; this.refreshConversationUnreadCounts(); })
            .catch(e=> this.partError = e.message || 'Remove failed');
        }
        ,leaveConversation(){
            fetch(`/messages/conversations/${this.current.id}/leave`, {
                method:'POST', headers:{'X-CSRF-TOKEN': this.csrf()}
            }).then(r=> r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
            .then(()=> { this.showParticipants=false; this.current=null; this.messages=[]; this.refreshConversationUnreadCounts(); })
            .catch(e=> this.partError = e.message || 'Leave failed');
        }
        ,get canRename(){
            // Server doesn't expose creator id directly in conversation list now; rely on participants map containing current user.
            // Minimal approach: allow rename button for all group convos; server will enforce policy.
            return this.current && this.current.type==='group';
        }
        ,startRename(){
            this.renameError=''; this.renameTitle=this.current.title; this.renaming=true; setTimeout(()=>{
                // no-op placeholder for focus if desired
            },10);
        }
        ,cancelRename(){ this.renaming=false; this.renameSaving=false; this.renameError=''; }
        ,submitRename(){
            if(!this.renameTitle.trim()) { this.renameError='Title required'; return; }
            this.renameSaving=true; this.renameError='';
            fetch(`/messages/conversations/${this.current.id}/title`, {
                method:'PATCH',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN': this.csrf()},
                body: JSON.stringify({title: this.renameTitle.trim()})
            }).then(r=> r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
            .then(d=> {
                this.current.title = d.title;
                // update list item
                const idx = this.conversations.findIndex(x=>x.id===this.current.id);
                if(idx>-1) this.conversations[idx].title = d.title;
                this.cancelRename();
            })
            .catch(e=> this.renameError = e.message || 'Rename failed')
            .finally(()=> this.renameSaving=false);
        }
        ,canDelete(m){
            if(m.user.id !== this.userId) return false;
            // 5 minute window check
            const five = 5*60*1000;
            return (Date.now() - new Date(m.at).getTime()) < five;
        }
        ,deleteMessage(m){
            fetch(`/messages/conversations/${this.current.id}/items/${m.id}`, {
                method:'DELETE', headers:{'X-CSRF-TOKEN': this.csrf()}
            }).then(r=> {
                if(r.ok){
                    const idx = this.messages.findIndex(x=>x.id===m.id);
                    if(idx>-1) this.messages.splice(idx,1);
                    this.pushToast('Message deleted','success');
                } else {
                    this.pushToast('Delete failed','error');
                }
            });
        }
        // User search for adding participants
        ,searchTerm:'', searchResults:[], selectedAdd:[], searchLoading:false, searchTimer:null
        ,searchUsers(){
            if(this.searchTimer) clearTimeout(this.searchTimer);
            if(!this.searchTerm.trim()){ this.searchResults=[]; return; }
            this.searchTimer=setTimeout(()=>{
                this.searchLoading=true;
                fetch(`/messages/conversations/${this.current.id}/user-search?q=${encodeURIComponent(this.searchTerm.trim())}`)
                  .then(r=> r.ok? r.json(): r.json().then(e=>Promise.reject(e)))
                  .then(d=> { this.searchResults = d.users || []; })
                  .catch(()=> this.searchResults=[])
                  .finally(()=> this.searchLoading=false);
            }, 300);
        }
        ,toggleSelectUser(u){
            if(this.selectedAdd.find(x=>x.id===u.id)){
                this.selectedAdd = this.selectedAdd.filter(x=>x.id!==u.id);
            } else {
                this.selectedAdd.push(u);
            }
        }
        ,addSelectedUsers(){
            if(!this.selectedAdd.length) return;
            const ids = this.selectedAdd.map(u=>u.id);
            fetch(`/messages/conversations/${this.current.id}/participants`, {
                method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': this.csrf()},
                body: JSON.stringify({user_ids: ids})
            }).then(r=> r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
            .then(d=> { this.participants = d.participants; this.selectedAdd=[]; this.searchResults=[]; this.searchTerm=''; this.pushToast('Participants added','success'); this.refreshConversationUnreadCounts(); })
            .catch(e=> this.partError = e.message || 'Add failed');
        }
    }
}
</script>

<!-- Toast Container -->
<div class="fixed top-4 right-4 space-y-2 pointer-events-none" x-cloak>
    <template x-for="t in toasts" :key="t.id">
        <div class="px-3 py-2 rounded shadow text-xs text-white pointer-events-auto"
             :class="{'bg-indigo-600': t.type==='info', 'bg-green-600': t.type==='success', 'bg-red-600': t.type==='error'}" x-text="t.msg"></div>
    </template>
</div>
@endsection
