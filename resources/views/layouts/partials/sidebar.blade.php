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

        @php
            // Build flat items and deduplicate by route.
            // We also support a role switching view (staffView flag) where
            // we only show items belonging to either staff (base) or admin sections
            // without repeating overlaps across the two modes. Assumptions:
            // - Base staff items are those coming from config('navigation.sections')
            // - Admin items come from config('navigation.admin_sections') and may overlap by route name
            // - If an item exists in both, we treat the 'admin' version as admin-only and hide it from staff view to avoid duplication.

            $staffSections = config('navigation.sections', []);
            $adminSections = config('navigation.admin_sections', []);

            // Collect routes present in both to avoid duplication across modes.
            $overlapRoutes = [];
            foreach ($adminSections as $sec => $items) {
                foreach ($items as $it) {
                    $r = $it['route'] ?? null;
                    if (!$r) continue;
                    // mark admin route
                    foreach ($staffSections as $ssec => $sitems) {
                        foreach ($sitems as $sit) {
                            if (($sit['route'] ?? null) === $r) { $overlapRoutes[$r] = true; break 2; }
                        }
                    }
                }
            }

            // Determine active mode based on Alpine staffView variable (we replicate logic server-side as fallback)
            $requestView = request()->query('view');
            $isStaffView = ($requestView === 'staff') || (!$requestView && (session('staffView') === true));

            $flatItems = [];
            if ($isStaffView) {
                // Staff view: include only staff routes; if overlap exists, show only staff version.
                foreach ($staffSections as $sectionName => $items) {
                    foreach ($items as $it) {
                        $route = $it['route'] ?? null;
                        if (!$route) continue;
                        $flatItems[$route] = $it; // staff precedence in staff mode
                    }
                }
            } else {
                // Admin view: start with staff items then merge in admin-only or overridden admin variants.
                foreach ($staffSections as $sectionName => $items) {
                    foreach ($items as $it) {
                        $route = $it['route'] ?? null; if(!$route) continue; $flatItems[$route] = $it; }
                }
                foreach ($adminSections as $sectionName => $items) {
                    foreach ($items as $it) {
                        $route = $it['route'] ?? null; if(!$route) continue; $flatItems[$route] = $it; // admin overrides staff if same route
                    }
                }
            }

            // Convert to list
            $flatItems = array_values($flatItems);

            // Optional: sort alphabetically by label for consistency
            usort($flatItems, function ($a, $b) {
                return strcasecmp($a['label'] ?? '', $b['label'] ?? '');
            });
        @endphp
        <div class="space-y-1">
            @foreach($flatItems as $item)
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
    </nav>
</aside>