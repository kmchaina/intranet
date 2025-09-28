<aside x-bind:class="(sidebarOpen ? 'w-72' : 'w-20') + ' ' + (window.innerWidth < 1024 ? (sidebarOpen ? 'translate-x-0' : '-translate-x-full') : '')" class="sidebar-gradient flex flex-col overflow-hidden flex-shrink-0 transition-all duration-300 shadow-2xl z-40 fixed inset-y-0 left-0 lg:relative">
    <div class="p-6">
        <div class="flex items-center" x-show="sidebarOpen" x-transition>
            <div class="w-12 h-12 mr-3"><img src="{{ asset('images/logos/NIMR.png') }}" class="w-full h-full object-contain" alt="Logo"></div>
            <div>
                <h1 class="text-xl font-bold sidebar-brand">NIMR Intranet</h1>
                <p class="text-sm text-blue-600 font-medium">National Institute for Medical Research</p>
            </div>
        </div>
        <div x-show="!sidebarOpen" x-transition class="flex justify-center"><div class="w-12 h-12"><img src="{{ asset('images/logos/NIMR.png') }}" class="w-full h-full object-contain" alt="Logo"></div></div>
    </div>
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto" x-data="{ 
        staffView: (new URLSearchParams(window.location.search).get('view') === 'staff') || (localStorage.getItem('staffView') === 'true') || false,
        switchToAdminView(){ this.staffView=false; localStorage.setItem('staffView', false); if(window.location.pathname==='/dashboard'){ window.location='?view=admin'; }},
        switchToStaffView(){ this.staffView=true; localStorage.setItem('staffView', true); if(window.location.pathname==='/dashboard'){ window.location='?view=staff'; }}
    }" style="scrollbar-width:thin; scrollbar-color:#CBD5E0 transparent;">
        @if(auth()->check() && auth()->user()->isAdmin())
            <div class="mb-5" x-show="sidebarOpen" x-transition>
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-lg p-3">
                    <div class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2 flex items-center">View Mode</div>
                    <div class="flex bg-white rounded-md p-1 shadow-sm">
                        <button @click="switchToAdminView()" :class="!staffView ? 'bg-blue-500 text-white shadow-sm' : 'text-gray-600 hover:text-gray-800'" class="flex-1 px-2 py-2 text-xs font-medium rounded transition-all flex items-center justify-center">Admin</button>
                        <button @click="switchToStaffView()" :class="staffView ? 'bg-green-500 text-white shadow-sm' : 'text-gray-600 hover:text-gray-800'" class="flex-1 px-2 py-2 text-xs font-medium rounded transition-all flex items-center justify-center">Staff</button>
                    </div>
                </div>
            </div>
        @endif

        @foreach($menuSections as $sectionName => $items)
            @php $isAdminSection = in_array($sectionName,['Content Creation','System Administration']); $slug = \Illuminate\Support\Str::slug($sectionName); @endphp
            <div x-data="{ open: JSON.parse(localStorage.getItem('section-{{ $slug }}') ?? 'true'), isAdminSection: {{ $isAdminSection ? 'true':'false' }} }"
                 @if(auth()->check() && auth()->user()->isSuperAdmin())
                    x-show="(isAdminSection && !staffView) || (!isAdminSection && staffView)"
                 @else
                    x-show="isAdminSection ? !staffView : true"
                 @endif
                 x-transition>
                <div class="mb-2">
                    <button x-show="sidebarOpen" @click="open=!open; localStorage.setItem('section-{{ $slug }}', open)" class="w-full flex items-center justify-between px-3 py-2 text-[0.7rem] font-bold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                        <span>{{ $sectionName }}</span>
                        <svg class="w-3 h-3 text-gray-400 transform transition-transform" :class="open ? 'rotate-0' : '-rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="!sidebarOpen" class="flex justify-center py-1"><div class="w-2 h-2 bg-gray-300 rounded-full"></div></div>
                </div>
                <div class="space-y-1 mb-4" x-show="open" x-transition>
                    @foreach($items as $item)
                        @php $iconPath = config('icons.' . ($item['icon'] ?? '')); @endphp
                        <a href="{{ route($item['route']) }}" class="nav-link {{ (request()->routeIs($item['route']) || request()->routeIs($item['route'].'*')) ? 'nav-link-active' : '' }}" x-bind:title="!sidebarOpen ? '{{ $item['label'] }}' : null">
                            <div class="flex items-center w-full">
                                <div class="nav-link-icon">
                                    @if($iconPath)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" /></svg>
                                    @endif
                                </div>
                                <span x-show="sidebarOpen" x-transition class="ml-3 text-sm font-medium leading-5 flex-1">{{ $item['label'] }}</span>
                                @if(!empty($item['badge']))
                                    <span x-show="sidebarOpen" x-transition class="ml-2 min-w-[1.25rem] h-5 inline-flex items-center justify-center bg-red-500 text-white text-[10px] font-bold px-1.5 rounded-full">{{ $item['badge'] }}</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>
</aside>