@auth
<div x-data="chatWidget()" x-init="init()" class="">
    <!-- Floating Button -->
    <button @click="toggle()" class="fixed z-40 bottom-5 right-5 w-14 h-14 rounded-full shadow-lg flex items-center justify-center text-white font-medium transition-all"
        :class="open ? 'bg-indigo-600' : 'bg-indigo-500 hover:bg-indigo-600'">
        <template x-if="!open">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8-.99 0-1.945-.124-2.843-.355-.548.31-1.78 1.018-3.657 1.602-.376.118-.752-.19-.671-.575.155-.73.389-1.802.453-2.022C3.907 17.64 3 15.393 3 13c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/></svg>
        </template>
        <template x-if="open">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </template>
        <span x-show="totalUnread" x-transition class="absolute -top-1 -right-1 bg-red-600 text-[10px] px-1.5 py-0.5 rounded-full" x-text="totalUnread"></span>
    </button>

    <!-- Panel -->
    <div x-show="open" x-transition class="fixed bottom-24 right-5 w-96 max-h-[70vh] bg-white rounded-xl shadow-2xl border flex flex-col z-40 overflow-hidden">
        <!-- Header -->
        <div class="px-4 py-3 border-b flex items-center justify-between bg-gradient-to-r from-indigo-600 to-indigo-500 text-white">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6c0-1.1.9-2 2-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                <h3 class="font-semibold text-sm">Messages</h3>
            </div>
            <div class="flex items-center gap-2">
                <button @click="startNewGroup()" class="text-[11px] bg-white/10 hover:bg-white/20 px-2 py-1 rounded">New</button>
                <button @click="toggle()" class="text-white/80 hover:text-white">×</button>
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
                        <div @click="selectConversation(c)" class="px-2 py-2 cursor-pointer text-[11px] flex flex-col gap-0.5 hover:bg-indigo-50" :class="{'bg-indigo-100': current && current.id===c.id}">
                            <div class="flex justify-between gap-1 items-center">
                                <span class="font-medium truncate" x-text="c.title"></span>
                                <span x-show="c.unread" class="bg-red-600 text-white rounded-full text-[9px] px-1" x-text="c.unread"></span>
                            </div>
                            <div class="text-[10px] text-gray-500 truncate" x-text="c.last_message ? (c.last_message.body || '[Attachment]') : 'No messages' "></div>
                        </div>
                    </template>
                    <div x-show="!filteredConversations().length" class="p-3 text-[10px] text-gray-500">No chats</div>
                </div>
            </div>
            <!-- Conversation Pane -->
            <div class="flex-1 flex flex-col">
                <template x-if="current">
                    <div class="flex flex-col h-full">
                        <div class="px-3 py-2 border-b flex items-center justify-between">
                            <h4 class="font-medium text-sm truncate" x-text="current.title"></h4>
                            <button @click="refreshCurrent()" class="text-[10px] px-2 py-0.5 border rounded">Refresh</button>
                        </div>
                        <div class="flex-1 overflow-y-auto p-3 space-y-2" x-ref="msgList">
                            <template x-for="m in messages" :key="m.id">
                                <div class="text-xs group" :class="{'text-right': m.user.id===userId}">
                                    <div class="inline-block max-w-[70%] px-2 py-1 rounded relative" :class="m.user.id===userId ? 'bg-indigo-600 text-white' : 'bg-gray-100'">
                                        <div class="text-[9px] opacity-70 mb-0.5" x-text="m.user.name"></div>
                                        <div x-text="m.body" class="whitespace-pre-wrap break-words"></div>
                                        <template x-if="m.attachments && m.attachments.length">
                                            <ul class="mt-1 space-y-0.5">
                                                <template x-for="a in m.attachments" :key="a.url">
                                                    <li><a :href="a.url" target="_blank" class="underline text-[10px]" x-text="a.name"></a></li>
                                                </template>
                                            </ul>
                                        </template>
                                        <div class="text-[9px] opacity-50 mt-0.5" x-text="formatTime(m.at)"></div>
                                        <button x-show="canDelete(m)" @click="deleteMessage(m)" class="hidden group-hover:inline-block absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-4 h-4 text-[9px] leading-4">×</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <form @submit.prevent="sendMessage" class="p-2 border-t flex gap-1 items-start">
                            <textarea x-model="draft" rows="2" class="flex-1 text-[11px] border rounded px-2 py-1" placeholder="Type..."></textarea>
                            <div class="flex flex-col gap-1 w-16">
                                <button type="button" @click="$refs.file.click()" class="text-[10px] border rounded px-2 py-1 bg-white">File</button>
                                <button type="submit" class="text-[11px] bg-indigo-600 text-white rounded px-2 py-1 disabled:opacity-40" :disabled="!draft.trim() && !attachments.length">Send</button>
                            </div>
                            <input type="file" multiple class="hidden" x-ref="file" @change="handleFiles($event)" />
                        </form>
                    </div>
                </template>
                <div x-show="!current" class="flex-1 flex items-center justify-center text-[11px] text-gray-500">Select or create a chat</div>
            </div>
        </div>
    </div>

    <!-- New Group Modal -->
    <div x-show="showNew" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
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
                    <input x-model="userTerm" @input="searchUsers" placeholder="Type name or email" class="border rounded px-2 py-1 text-[12px]" />
                    <div class="border rounded max-h-40 overflow-y-auto" x-show="searchResults.length || searching || searchError || (userTerm.trim().length && !searching)">
                        <template x-for="u in searchResults" :key="u.id">
                            <div @click="toggleSelect(u)" class="px-2 py-1 text-[11px] cursor-pointer flex justify-between hover:bg-indigo-50" :class="{'bg-indigo-100': chosen.find(x=>x.id===u.id)}">
                                <span x-text="u.name + ' (' + u.email + ')'" class="truncate"></span>
                                <span x-show="chosen.find(x=>x.id===u.id)" class="text-indigo-600">✔</span>
                            </div>
                        </template>
                        <div x-show="searching" class="px-2 py-1 text-[10px] text-gray-500">Searching...</div>
                        <div x-show="!searching && !searchError && !searchResults.length && userTerm.trim().length" class="px-2 py-1 text-[10px] text-gray-500">No matches</div>
                        <div x-show="searchError" class="px-2 py-1 text-[10px] text-red-600" x-text="searchError"></div>
                    </div>
                    <div class="flex flex-wrap gap-1" x-show="chosen.length">
                        <template x-for="c in chosen" :key="c.id">
                            <span class="bg-indigo-600 text-white text-[10px] px-2 py-0.5 rounded flex items-center gap-1">
                                <span x-text="c.name"></span>
                                <button @click.prevent="toggleSelect(c)" class="leading-none">×</button>
                            </span>
                        </template>
                    </div>
                </div>
                <div class="text-[10px] text-red-600" x-text="newError"></div>
                <div class="flex justify-end gap-2 pt-1">
                    <button @click="cancelNew()" class="text-[11px] px-3 py-1 border rounded">Cancel</button>
                    <button @click="createGroup()" class="text-[11px] px-3 py-1 bg-indigo-600 text-white rounded disabled:opacity-40" :disabled="!canCreateGroup">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Toasts -->
    <div class="fixed bottom-5 right-24 space-y-2" x-cloak>
        <template x-for="t in toasts" :key="t.id">
            <div class="px-3 py-2 rounded shadow text-white text-[11px]" :class="{'bg-indigo-600': t.type==='info','bg-green-600': t.type==='success','bg-red-600': t.type==='error'}" x-text="t.msg"></div>
        </template>
    </div>
</div>
<script>
function chatWidget(){
    return {
        open: false,
        userId: {{ auth()->id() }},
        isSuperAdmin: {{ auth()->user()->isSuperAdmin() ? 'true':'false' }},
        conversations: [],
        current: null,
        messages: [],
        draft: '',
        attachments: [],
        filter: '',
        pollTimer: null,
        showNew: false,
        newGroup: { title: '' },
        userTerm: '',
        searchResults: [],
        searching: false,
        chosen: [],
    newError: '',
    searchTimer: null,
    searchError: '',
        toasts: [],
        init(){
            const persisted = localStorage.getItem('chatWidgetOpen');
            if(persisted === 'true') { this.open = true; }
            if(this.open){ this.loadConversations(); }
        },
        toggle(){ this.open = !this.open; localStorage.setItem('chatWidgetOpen', this.open); if(this.open && !this.conversations.length) this.loadConversations(); },
                loadConversations(){
                        fetch('/messages', { headers: { 'Accept':'application/json' }})
                            .then(r=> r.ok ? r.json() : Promise.reject())
                            .then(list=>{ this.conversations = Array.isArray(list)? list: []; if(this.conversations.length && !this.current){ this.selectConversation(this.conversations[0]); } this.schedulePoll(); })
                            .catch(()=>{});
        },
        filteredConversations(){
            if(!this.filter.trim()) return this.conversations;
            const t=this.filter.toLowerCase();
            return this.conversations.filter(c=>c.title.toLowerCase().includes(t));
        },
        selectConversation(c){ this.current=c; this.messages=[]; fetch(`/messages/conversations/${c.id}`)
                .then(r=>r.json())
                .then(d=>{ this.messages=d.messages; this.scroll(); this.markRead(); }); },
        refreshCurrent(){ if(this.current) this.selectConversation(this.current); },
        formatTime(ts){ const d=new Date(ts); return d.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}); },
        csrf(){ return document.querySelector('meta[name="csrf-token"]').content; },
        sendMessage(){ if(!this.current || (!this.draft.trim() && !this.attachments.length)) return; fetch(`/messages/conversations/${this.current.id}/items`, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': this.csrf()}, body: JSON.stringify({ body:this.draft, attachments:this.attachments }) })
            .then(r=>r.json()).then(m=>{ if(m && m.id){ this.messages.push({ id:m.id, body:m.body, attachments:m.attachments, user:{id:this.userId,name:'You'}, at:m.at }); this.draft=''; this.updatePreview(m); this.scroll(); this.markRead(); }});
        },
        updatePreview(m){ const i=this.conversations.findIndex(x=>x.id===this.current.id); if(i>-1){ this.conversations[i].last_message={ body:m.body, user_id:m.user_id, at:m.at }; } },
        markRead(){ if(!this.current) return; fetch(`/messages/conversations/${this.current.id}/mark-read`, { method:'POST', headers:{'X-CSRF-TOKEN': this.csrf()} }).then(()=>{ this.current.unread=0; }); },
        scroll(){ this.$nextTick(()=>{ const el=this.$refs.msgList; if(el) el.scrollTop=el.scrollHeight; }); },
        schedulePoll(){ if(this.pollTimer) clearTimeout(this.pollTimer); this.pollTimer=setTimeout(()=> this.poll(), 5000); },
        poll(){ if(!this.current){ this.schedulePoll(); return; } const last=this.messages.length ? this.messages[this.messages.length-1].id : null; fetch(`/messages/conversations/${this.current.id}/items${ last ? ('?after_id='+last):''}`)
            .then(r=>r.json()).then(d=>{ if(d.messages && d.messages.length){ this.messages.push(...d.messages); this.scroll(); this.updatePreview(d.messages[d.messages.length-1]); } this.refreshUnread(); })
            .finally(()=> this.schedulePoll()); },
        refreshUnread(){ fetch('/messages', { headers:{'Accept':'application/json'}})
            .then(r=> r.ok ? r.json() : Promise.reject())
            .then(list=>{ this.conversations = Array.isArray(list)? list: this.conversations; })
            .catch(()=>{}); },
        canDelete(m){ if(this.isSuperAdmin) return true; if(m.user.id!==this.userId) return false; return (Date.now()- new Date(m.at).getTime()) < (5*60*1000); },
        deleteMessage(m){ fetch(`/messages/conversations/${this.current.id}/items/${m.id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN': this.csrf()} }).then(r=>{ if(r.ok){ const i=this.messages.findIndex(x=>x.id===m.id); if(i>-1) this.messages.splice(i,1); this.toast('Message deleted','success'); }}); },
        // Group creation
                startNewGroup(){
                        this.showNew=true; this.newGroup={title:''}; this.chosen=[]; this.userTerm=''; this.searchResults=[]; this.newError=''; this.searchError='';
                        // Prefetch initial suggestions (empty term now returns default list)
                        this.searching=true;
                        fetch('/messages/user-search')
                            .then(r=> r.ok ? r.json() : Promise.reject())
                            .then(d=> { this.searchResults = d.users || []; })
                            .catch(()=> { this.searchResults=[]; })
                            .finally(()=> this.searching=false);
                },
        cancelNew(){ this.showNew=false; },
        searchUsers(){
            this.searchError='';
            // Allow empty term to show default list
            if(this.searchTimer) clearTimeout(this.searchTimer);
            if(this.searchTimer) clearTimeout(this.searchTimer);
            this.searchTimer=setTimeout(()=>{
                this.searching=true;
                const url = this.userTerm.trim() ? `/messages/user-search?q=${encodeURIComponent(this.userTerm.trim())}` : '/messages/user-search';
                fetch(url)
                  .then(r=> {
                      if(r.status===429) return r.json().then(j=>Promise.reject({ message: j.message || 'Too many searches, slow down'}));
                      return r.ok ? r.json() : r.json().then(j=>Promise.reject(j));
                  })
                  .then(d=> { this.searchResults = d.users || []; })
                  .catch(e=> { this.searchResults=[]; this.searchError = e.message || 'Search failed'; })
                  .finally(()=> this.searching=false);
            }, 350);
        },
        toggleSelect(u){ const idx=this.chosen.findIndex(x=>x.id===u.id); if(idx>-1){ this.chosen.splice(idx,1);} else { this.chosen.push(u);} },
        get canCreateGroup(){ return this.newGroup.title.trim() && this.chosen.length>=2; },
        createGroup(){
            if(!this.canCreateGroup) return;
            const ids=this.chosen.map(c=>c.id);
            let newId=null;
            fetch('/messages/group', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': this.csrf()}, body: JSON.stringify({ title:this.newGroup.title, participants: ids }) })
                .then(r=> r.ok? r.json(): r.json().then(e=>Promise.reject(e)))
                .then(d=>{ newId=d.id; this.showNew=false; this.toast('Group created','success'); return fetch('/messages', { headers:{'Accept':'application/json'}}); })
                .then(r=> r.ok ? r.json() : Promise.reject())
                .then(list=>{ this.conversations = Array.isArray(list)? list: []; const created = this.conversations.find(c=>c.id===newId); if(created){ this.selectConversation(created); } })
                .catch(e=>{ this.newError = (e && e.message) ? e.message : 'Failed'; });
        },
        toast(msg,type='info'){ const id=Date.now()+Math.random(); this.toasts.push({id,msg,type}); setTimeout(()=>{ this.toasts=this.toasts.filter(t=>t.id!==id); },4000); },
    get totalUnread(){ return this.conversations.reduce((s,c)=> s + (c.unread||0), 0); }
    }
}
</script>
@endauth
