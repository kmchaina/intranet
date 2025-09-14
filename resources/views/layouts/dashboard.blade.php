<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
    init() {
        this.$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val));
    }
}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - NIMR Intranet</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
            border-right: 1px solid #e2e8f0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .nav-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 8px;
            margin: 2px 12px;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, 
                rgba(59, 130, 246, 0.1) 0%, 
                rgba(59, 130, 246, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 8px;
        }

        .nav-item:hover::before {
            opacity: 1;
        }

        .nav-item:hover {
            transform: translateX(4px);
            background: rgba(59, 130, 246, 0.05);
        }

        .nav-item.active::before {
            opacity: 1;
            background: linear-gradient(90deg, 
                rgba(59, 130, 246, 0.15) 0%, 
                rgba(59, 130, 246, 0.08) 100%);
        }

        .nav-item.active {
            background: rgba(59, 130, 246, 0.08);
            border-left: 3px solid #3b82f6;
        }

        .nav-item > * {
            position: relative;
            z-index: 2;
        }

        .content-area {
            min-height: calc(100vh - 5rem);
        }

        .badge-professional {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
        }

        .badge-professional.new {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .sidebar-brand {
            background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.8);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #374151;
            text-decoration: none;
            border-radius: 0.5rem;
            margin: 0.125rem 0.75rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: rgba(59, 130, 246, 0.05);
            color: #3b82f6;
            transform: translateX(4px);
        }

        .nav-link-active {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-left: 3px solid #3b82f6;
        }

        .nav-link-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 1.25rem;
            height: 1.25rem;
        }
    </style>
</head>

<body class="bg-gray-50 antialiased overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside x-bind:class="sidebarOpen ? 'w-72' : 'w-20'"
            class="sidebar-gradient flex-shrink-0 transition-all duration-300 ease-in-out shadow-2xl relative z-30">

            <!-- Logo Section -->
            <div class="logo-section p-6">
                <div class="flex items-center" x-show="sidebarOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="w-12 h-12 mr-3 relative">
                        <img src="{{ asset('images/logos/NIMR.png') }}" alt="NIMR Logo"
                            class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold sidebar-brand">NIMR Intranet</h1>
                        <p class="text-sm text-blue-600 font-medium">National Institute for Medical Research</p>
                    </div>
                </div>
                <div x-show="!sidebarOpen" x-transition class="flex justify-center">
                    <div class="w-12 h-12 relative">
                        <img src="{{ asset('images/logos/NIMR.png') }}" alt="NIMR Logo"
                            class="w-full h-full object-contain">
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto max-h-screen" x-data="{ 
                staffView: (new URLSearchParams(window.location.search).get('view') === 'staff') || 
                          (localStorage.getItem('staffView') === 'true') || false,
                switchToAdminView() {
                    this.staffView = false;
                    localStorage.setItem('staffView', false);
                    if (window.location.pathname === '/dashboard') {
                        window.location.href = '/dashboard?view=admin';
                    }
                },
                switchToStaffView() {
                    this.staffView = true;
                    localStorage.setItem('staffView', true);
                    if (window.location.pathname === '/dashboard') {
                        window.location.href = '/dashboard?view=staff';
                    }
                }
            }"
            style="scrollbar-width: thin; scrollbar-color: #CBD5E0 transparent;">
                @php
                    // Calculate unread announcements for current user
                    $unreadAnnouncementsCount = 0;
                    if (auth()->check()) {
                        $unreadAnnouncementsCount = \App\Models\Announcement::visibleTo(auth()->user())
                            ->whereDoesntHave('readBy', function ($query) {
                                $query->where('user_id', auth()->id());
                            })
                            ->count();
                    }

                    // Calculate active polls count
                    $activePolls = 0;
                    if (auth()->check()) {
                        $activePolls = \App\Models\Poll::active()->count();
                    }

                    // Calculate upcoming events
                    $upcomingEvents = 0;
                    if (auth()->check()) {
                        $upcomingEvents = \App\Models\Event::published()
                            ->inDateRange(now(), now()->addDays(7))
                            ->forUser(auth()->user())
                            ->count();
                    }

                    // Define simplified flat menu structure
                    $menuSections = [
                        'Dashboard' => [
                            ['route' => 'dashboard', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z', 'label' => 'Dashboard', 'badge' => null],
                        ],
                        'Communication' => [
                            ['route' => 'announcements.index', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'label' => 'Announcements', 'badge' => $unreadAnnouncementsCount > 0 ? $unreadAnnouncementsCount : null],
                            ['route' => 'news.index', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', 'label' => 'News & Updates', 'badge' => null],
                            ['route' => 'polls.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'label' => 'Polls & Surveys', 'badge' => $activePolls > 0 ? $activePolls : null],
                            ['route' => 'feedback.index', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'label' => 'Feedback', 'badge' => null],
                        ],
                        'Resources' => [
                            ['route' => 'documents.index', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'label' => 'Document Library', 'badge' => null],
                            ['route' => 'training-videos.index', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'label' => 'Training Videos', 'badge' => null],
                            ['route' => 'system-links.index', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1', 'label' => 'Systems Directory', 'badge' => null],
                        ],
                        'People & Events' => [
                            ['route' => 'events.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Events & Calendar', 'badge' => $upcomingEvents > 0 ? $upcomingEvents : null],
                            ['route' => 'staff.index', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Staff Directory', 'badge' => null],
                            ['route' => 'birthdays.index', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Birthdays', 'badge' => null],
                        ],
                        'Personal Tools' => [
                            ['route' => 'todos.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'To-Do Lists', 'badge' => null],
                            ['route' => 'password-vault.index', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'label' => 'Password Vault', 'badge' => null],
                            ['route' => 'profile.show', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'My Profile', 'badge' => null],
                        ],
                    ];

                    // Add appropriate admin sections based on user role
                    if (auth()->check() && auth()->user()->isAdmin()) {
                        // Content Creation for all admin types (centre, station, HQ, super)
                        $menuSections['Content Creation'] = [
                            ['route' => 'announcements.create', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6', 'label' => 'Create Announcement', 'badge' => null],
                            ['route' => 'news.create', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6', 'label' => 'Create News', 'badge' => null],
                            ['route' => 'events.create', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6', 'label' => 'Create Event', 'badge' => null],
                            ['route' => 'documents.create', 'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12', 'label' => 'Upload Document', 'badge' => null],
                        ];
                        
                        // System Administration only for super_admin
                        if (auth()->user()->isSuperAdmin()) {
                            $menuSections['System Administration'] = [
                                ['route' => 'admin.users.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z', 'label' => 'User Management', 'badge' => null],
                                ['route' => 'admin.content.index', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'label' => 'Content Management', 'badge' => null],
                                ['route' => 'admin.settings.index', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'label' => 'System Settings', 'badge' => null],
                            ];
                        }
                    }
                @endphp

                <!-- Role Switching Toggle (Admin Only) -->
                @if (auth()->check() && auth()->user()->isAdmin())
                    <div class="mb-6 px-2" x-show="sidebarOpen" x-transition>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-100">
                            <div class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Mode
                            </div>
                            <div class="flex bg-white rounded-md p-1 shadow-sm">
                                <button 
                                    @click="switchToAdminView()"
                                    :class="!staffView ? 'bg-blue-500 text-white shadow-sm' : 'text-gray-600 hover:text-gray-800'"
                                    class="flex-1 px-2 py-2 text-xs font-medium rounded transition-all duration-200 flex items-center justify-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Admin
                                </button>
                                <button 
                                    @click="switchToStaffView()"
                                    :class="staffView ? 'bg-green-500 text-white shadow-sm' : 'text-gray-600 hover:text-gray-800'"
                                    class="flex-1 px-2 py-2 text-xs font-medium rounded transition-all duration-200 flex items-center justify-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Staff
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Flat Navigation Menu -->
                @foreach($menuSections as $sectionName => $items)
                    @if($sectionName === 'System Administration')
                        <!-- Hide system admin section in staff view and for non-super admins -->
                        <div x-show="!staffView" x-transition>
                    @elseif($sectionName === 'Content Creation')
                        <!-- Show content creation section only for admins -->
                        <div x-show="!staffView" x-transition>
                    @else
                        <!-- Show regular sections: in admin view (always) or in staff view (always) -->
                        <div>
                    @endif
                    
                        <!-- Section Header -->
                        <div class="mb-3">
                            <h3 x-show="sidebarOpen" x-transition
                                class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                {{ $sectionName }}
                            </h3>
                            
                            <!-- Collapsed section indicator -->
                            <div x-show="!sidebarOpen" class="flex justify-center py-1">
                                <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                            </div>
                        </div>

                        <!-- Section Items -->
                        <div class="space-y-1 mb-6">
                            @foreach($items as $item)
                                <a href="{{ route($item['route']) }}"
                                    class="nav-link {{ request()->routeIs($item['route']) ? 'nav-link-active' : '' }}">
                                    <div class="flex items-center w-full">
                                        <div class="nav-link-icon">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                                            </svg>
                                        </div>
                                        <span x-show="sidebarOpen" x-transition class="ml-4 font-semibold flex-1">
                                            {{ $item['label'] }}
                                        </span>
                                        @if ($item['badge'])
                                            <span x-show="sidebarOpen" x-transition class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                {{ $item['badge'] }}
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 relative z-20 shadow-sm">
                <!-- Left Section -->
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-lg font-semibold text-gray-900 tracking-tight leading-tight">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 font-bold">{{ Auth::user()->first_name ?? Auth::user()->name }}</span>
                        </h1>
                        <p class="text-xs font-medium text-gray-500 mt-1 flex items-center">
                            <span class="text-gray-500">Have a productive day</span>
                            <span class="text-gray-400 mx-2">•</span> 
                            <span class="text-xs text-gray-400 font-medium tracking-wide">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Search Bar - Smaller and Right-aligned -->
                <div class="flex-1 flex justify-end">
                    <div class="w-64 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" placeholder="Search..."
                            class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all">
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-3">
                    <!-- Quick Actions -->
                    <div class="hidden md:flex items-center space-x-2">
                        @if (auth()->check() && auth()->user()->canCreateAnnouncements())
                            <a href="{{ route('announcements.create') }}"
                                class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Create</span>
                            </a>
                        @endif
                    </div>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-3-3.5A5.98 5.98 0 0019 8.5a6 6 0 10-12 0c0 1.677.69 3.2 1.8 4.3L6 17h5m4 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <!-- Notification badge -->
                            <span class="absolute -top-1 -right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                            </div>
                            <div class="overflow-y-auto max-h-80">
                                <div class="p-6 text-center text-gray-500">
                                    <svg class="w-8 h-8 mx-auto mb-3 text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="font-medium text-sm">All caught up!</p>
                                    <p class="text-xs">No new notifications right now.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center space-x-2 p-2 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <img class="w-8 h-8 rounded-full object-cover border border-gray-200"
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=ffffff"
                                alt="{{ auth()->user()->name }}">
                        </button>

                        <!-- Profile Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <img class="w-10 h-10 rounded-full object-cover border border-gray-200"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=ffffff"
                                        alt="{{ auth()->user()->name }}">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endauth
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-100/40 content-area">
                <div class="p-8">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 flex items-center space-x-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 flex items-center space-x-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>