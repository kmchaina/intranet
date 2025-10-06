@php
    $staffSections = config('navigation.sections', []);
    $adminSections = [];
    $user = auth()->user();
    if ($user) {
        if ($user->isSuperAdmin()) {
            $adminSections = config('navigation.super_admin', []);
        } elseif ($user->isHqAdmin()) {
            $adminSections = config('navigation.hq_admin', []);
        } elseif ($user->isCentreAdmin()) {
            $adminSections = config('navigation.centre_admin', []);
        } elseif ($user->isStationAdmin()) {
            $adminSections = config('navigation.station_admin', []);
        }
    }

    $requestView = request()->query('view');
    if ($requestView === 'staff') {
        session(['staffView' => true]);
        $isStaffView = true;
    } elseif ($requestView === 'admin') {
        session(['staffView' => false]);
        $isStaffView = false;
    } else {
        $isStaffView = session('staffView', false);
    }

    // For real staff users, always show staff sections
    // For admin users, show staff sections when in staff view, admin sections when in admin view
    if ($user && $user->role === 'staff') {
        $displaySections = $staffSections;
    } else {
        $displaySections = $isStaffView ? $staffSections : $adminSections;
    }

    // Role-based color themes with hex values
    $roleColors = [
        'staff' => [
            'primary' => 'indigo',
            'accent' => 'purple',
            'theme' => 'Innovation',
            'primary_from' => '#eef2ff',
            'primary_to' => '#ede9fe',
            'primary_border' => '#e0e7ff',
            'primary_600' => '#4f46e5',
            'accent_600' => '#9333ea',
        ],
        'station_admin' => [
            'primary' => 'cyan',
            'accent' => 'blue',
            'theme' => 'Precision',
            'primary_from' => '#ecfeff',
            'primary_to' => '#dbeafe',
            'primary_border' => '#cffafe',
            'primary_600' => '#0891b2',
            'accent_600' => '#3b82f6',
        ],
        'centre_admin' => [
            'primary' => 'violet',
            'accent' => 'fuchsia',
            'theme' => 'Insight',
            'primary_from' => '#f5f3ff',
            'primary_to' => '#fae8ff',
            'primary_border' => '#ede9fe',
            'primary_600' => '#8b5cf6',
            'accent_600' => '#d946ef',
        ],
        'hq_admin' => [
            'primary' => 'slate',
            'accent' => 'gray',
            'theme' => 'Governance',
            'primary_from' => '#f8fafc',
            'primary_to' => '#f9fafb',
            'primary_border' => '#f1f5f9',
            'primary_600' => '#475569',
            'accent_600' => '#374151',
        ],
        'super_admin' => [
            'primary' => 'amber',
            'accent' => 'yellow',
            'theme' => 'Dominion',
            'primary_from' => '#fffbeb',
            'primary_to' => '#fefce8',
            'primary_border' => '#fef3c7',
            'primary_600' => '#f59e0b',
            'accent_600' => '#eab308',
        ],
    ];

    // Determine effective role for theming
    $effectiveRole = $isStaffView ? 'staff' : $user->role;
    $colors = $roleColors[$effectiveRole] ?? $roleColors['staff'];

    $primary = $colors['primary'];
    $accent = $colors['accent'];

    // Unified professional sidebar styling
    $isLightSidebar = true; // All dashboards use light sidebar now

    // Dynamic sidebar colors based on role (using CSS classes that exist)
    $sidebarBgClasses = "bg-gradient-to-b from-{$primary}-50 to-{$accent}-50 border-r border-{$primary}-100";
    $sidebarText = 'text-slate-800';
    $sidebarTextMuted = "text-{$primary}-600";
    $sidebarBorder = "border-{$primary}-100";
    $navLinkText = "text-slate-700 hover:text-{$primary}-700";
    $brandTitle = "text-transparent bg-clip-text bg-gradient-to-r from-{$primary}-600 to-{$accent}-600 font-extrabold";
    $brandSub = "text-{$primary}-600";
    $toggleBg = "bg-{$primary}-100/50";
    $activeNavBg = "bg-{$primary}-100";
    $hoverNavBg = "hover:bg-{$primary}-50";

    // Inline styles for dynamic colors (fallback for non-compiled Tailwind classes)
    $brandTitleStyle = "background: linear-gradient(to right, {$colors['primary_600']}, {$colors['accent_600']}); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;";
    $brandSubStyle = "color: {$colors['primary_600']};";
@endphp

<aside
    x-bind:class="(sidebarOpen ? 'w-72' : 'w-20') + ' ' + (window.innerWidth < 1024 ? (sidebarOpen ? 'translate-x-0' :
        '-translate-x-full') : '')"
    class="{{ $sidebarBgClasses }} flex flex-col overflow-hidden flex-shrink-0 transition-all duration-300 shadow-2xl z-40 fixed inset-y-0 left-0 lg:relative">
    <div class="p-6">
        <div class="flex items-center" x-show="sidebarOpen" x-transition>
            <div class="w-12 h-12 mr-3"><img src="{{ asset('images/logos/NIMR.png') }}"
                    class="w-full h-full object-contain" alt="Logo"></div>
            <div>
                <h1 class="text-xl font-extrabold" style="{{ $brandTitleStyle }}">NIMR Intranet</h1>
                <p class="text-sm font-medium" style="{{ $brandSubStyle }}">National Institute for Medical Research</p>
            </div>
        </div>
        <div x-show="!sidebarOpen" x-transition class="flex justify-center">
            <div class="w-12 h-12"><img src="{{ asset('images/logos/NIMR.png') }}" class="w-full h-full object-contain"
                    alt="Logo"></div>
        </div>
    </div>
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto {{ $sidebarText }}" x-data="{
        staffView: {{ $isStaffView ? 'true' : 'false' }},
        switchToAdminView() {
            this.staffView = false;
            localStorage.setItem('staffView', 'false');
            window.location.href = '{{ route('dashboard') }}?view=admin';
        },
        switchToStaffView() {
            this.staffView = true;
            localStorage.setItem('staffView', 'true');
            window.location.href = '{{ route('dashboard') }}?view=staff';
        },
        expandedSection: localStorage.getItem('expandedSection') || null,
        toggleSection(section) {
            if (this.expandedSection === section) {
                this.expandedSection = null;
                localStorage.removeItem('expandedSection');
            } else {
                this.expandedSection = section;
                localStorage.setItem('expandedSection', section);
            }
        },
        isExpanded(section) {
            return this.expandedSection === section;
        }
    }"
        style="scrollbar-width:thin; scrollbar-color:#CBD5E0 transparent;">
        @if (auth()->check() && auth()->user()->isAdmin())
            <div class="mb-5 px-2" x-show="sidebarOpen" x-transition>
                <div class="view-mode-container">
                    <div
                        class="text-xs font-bold uppercase tracking-wider mb-3 flex items-center gap-2 {{ $sidebarTextMuted }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Mode
                    </div>
                    <div class="flex {{ $toggleBg }} rounded-lg p-1 shadow-sm gap-1">
                        <button @click="switchToAdminView()"
                            :class="!staffView ? 'view-mode-btn view-mode-btn-active' : 'view-mode-btn'"
                            class="flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Admin
                        </button>
                        <button @click="switchToStaffView()"
                            :class="staffView ? 'view-mode-btn view-mode-btn-active' : 'view-mode-btn'"
                            class="flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Staff
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-2">
            <a href="{{ route('dashboard') }}?view={{ $isStaffView ? 'staff' : 'admin' }}"
                class="nav-section-toggle {{ request()->routeIs('dashboard') || request()->routeIs('dashboard*') ? 'nav-section-expanded' : '' }}"
                :class="!sidebarOpen ? '' :
                    '{{ $sidebarTextMuted }} hover:{{ $isLightSidebar ? 'text-slate-900' : 'text-white' }}'"
                x-bind:class="sidebarOpen ? '' : 'justify-center'" x-show="sidebarOpen" x-transition
                x-bind:title="!sidebarOpen ? 'Dashboard' : null">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </span>
            </a>
        </div>

        <div class="space-y-2">
            @foreach ($displaySections as $sectionName => $items)
                <div class="space-y-1">
                    @if (trim($sectionName) !== '')
                        <button @click="toggleSection('{{ $sectionName }}')" x-show="sidebarOpen" x-transition
                            class="nav-section-toggle"
                            :class="isExpanded('{{ $sectionName }}') ? 'nav-section-expanded' :
                                '{{ $sidebarTextMuted }} hover:{{ $isLightSidebar ? 'text-slate-900' : 'text-white' }}'">
                            <span class="flex items-center gap-2">
                                @php
                                    $sectionIcons = [
                                        'Communication' =>
                                            'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                                        'Resources' =>
                                            'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                                        'People & Events' =>
                                            'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                                        'Personal Tools' =>
                                            'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                                        'User Management' =>
                                            'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                                        'Organization' =>
                                            'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                                        'Reports & Analytics' =>
                                            'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                                        'System Administration' =>
                                            'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                                        'System Tools' =>
                                            'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z',
                                    ];
                                    $iconPath =
                                        $sectionIcons[$sectionName] ??
                                        'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10';
                                @endphp
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $iconPath }}" />
                                </svg>
                                {{ $sectionName }}
                            </span>
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="isExpanded('{{ $sectionName }}') ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="isExpanded('{{ $sectionName }}')" x-transition
                            class="ml-2 space-y-1 border-l {{ $sidebarBorder }} pl-3">
                            @foreach ($items as $item)
                                @php
                                    $iconPath = config('icons.' . ($item['icon'] ?? ''));
                                    $isActive =
                                        request()->routeIs($item['route']) || request()->routeIs($item['route'] . '*');
                                @endphp
                                <a href="{{ route($item['route']) }}"
                                    class="nav-link {{ $isActive ? 'nav-link-active' : '' }} {{ $navLinkText }}"
                                    x-bind:title="!sidebarOpen ? '{{ $item['label'] }}' : null">
                                    <div class="nav-link-icon">
                                        @if ($iconPath)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ $iconPath }}" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <span x-show="sidebarOpen" x-transition
                                        class="ml-3 text-sm">{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        @foreach ($items as $item)
                            @php
                                $iconPath = config('icons.' . ($item['icon'] ?? ''));
                                $isActive =
                                    request()->routeIs($item['route']) || request()->routeIs($item['route'] . '*');
                            @endphp
                            <a href="{{ route($item['route']) }}"
                                class="nav-link {{ $isActive ? 'nav-link-active' : '' }} {{ $navLinkText }}"
                                x-bind:title="!sidebarOpen ? '{{ $item['label'] }}' : null">
                                <div class="nav-link-icon">
                                    @if ($iconPath)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $iconPath }}" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <span x-show="sidebarOpen" x-transition
                                    class="ml-3 text-sm">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>
    </nav>
</aside>
