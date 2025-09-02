<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true' || false,
    sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
    init() {
        this.$watch('darkMode', val => localStorage.setItem('darkMode', val));
        this.$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val));
    }
}" x-bind:class="{ 'dark': darkMode }">

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

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Heroicons -->
    <script src="https://unpkg.com/heroicons@2.0.18/24/outline/index.js" type="module"></script>

    <!-- Custom Styles for Navigation and Layout -->
    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        }

        .nav-item {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: translateX(4px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.2) 0%, transparent 100%);
            border-right: 3px solid #3b82f6;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: #3b82f6;
            border-radius: 0 4px 4px 0;
        }

        .content-area {
            min-height: calc(100vh - 5rem);
        }

        .sidebar-brand {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
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

        .dark ::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.5);
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.8);
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 antialiased overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside x-bind:class="sidebarOpen ? 'w-72' : 'w-20'"
            class="sidebar-gradient flex-shrink-0 transition-all duration-300 ease-in-out shadow-2xl relative z-30">

            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-700/50">
                <div class="flex items-center" x-show="sidebarOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0v-4a2 2 0 012-2h2a2 2 0 012 2v4" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">NIMR Intranet</h1>
                        <p class="text-sm text-blue-300">National Institute for Medical Research</p>
                    </div>
                </div>
                <div x-show="!sidebarOpen" x-transition class="flex justify-center">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0v-4a2 2 0 012-2h2a2 2 0 012 2v4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                @php
                    $menuItems = [
                        [
                            'route' => 'dashboard',
                            'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v2z',
                            'label' => 'Dashboard',
                            'badge' => null,
                        ],
                        [
                            'route' => 'announcements.index',
                            'icon' =>
                                'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
                            'label' => 'Announcements',
                            'badge' => 3,
                        ],
                        [
                            'route' => 'documents.index',
                            'icon' =>
                                'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            'label' => 'Documents',
                            'badge' => null,
                        ],
                        [
                            'route' => 'events.index',
                            'icon' =>
                                'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                            'label' => 'Events & Calendar',
                            'badge' => null,
                        ],
                        [
                            'route' => 'password-vault.index',
                            'icon' =>
                                'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                            'label' => 'Password Vault',
                            'badge' => null,
                        ],
                        [
                            'route' => 'todos.index',
                            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'label' => 'To-Do Lists',
                            'badge' => null,
                        ],
                        [
                            'route' => 'training-videos.index',
                            'icon' =>
                                'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
                            'label' => 'Training Videos',
                            'badge' => null,
                        ],
                        [
                            'route' => 'system-links.index',
                            'icon' =>
                                'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1',
                            'label' => 'Systems Directory',
                            'badge' => null,
                        ],
                        [
                            'route' => 'feedback.index',
                            'icon' =>
                                'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z',
                            'label' => 'Feedback & Suggestions',
                            'badge' => null,
                        ],
                        [
                            'route' => '#',
                            'icon' =>
                                'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                            'label' => 'Team Directory',
                            'badge' => null,
                        ],
                        [
                            'route' => '#',
                            'icon' =>
                                'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0v-4a2 2 0 012-2h2a2 2 0 012 2v4',
                            'label' => 'Departments',
                            'badge' => null,
                        ],
                        [
                            'route' => '#',
                            'icon' =>
                                'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                            'label' => 'Analytics',
                            'badge' => null,
                        ],
                        [
                            'route' => '#',
                            'icon' =>
                                'M8 7V3a4 4 0 118 0v4m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z',
                            'label' => 'Research Hub',
                            'badge' => 'NEW',
                        ],
                        [
                            'route' => '#',
                            'icon' =>
                                'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2',
                            'label' => 'Documents',
                            'badge' => null,
                        ],
                        [
                            'route' => '#',
                            'icon' =>
                                'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                            'label' => 'Communications',
                            'badge' => null,
                        ],
                    ];

                    if (auth()->user()->isSuperAdmin()) {
                        $menuItems[] = [
                            'route' => 'admin.users.index',
                            'icon' =>
                                'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                            'label' => 'Administration',
                            'badge' => null,
                        ];
                    }
                @endphp

                @foreach ($menuItems as $item)
                    <a href="{{ $item['route'] !== '#' ? route($item['route']) : '#' }}"
                        class="nav-item group flex items-center px-4 py-3 text-gray-300 rounded-xl hover:text-white transition-all duration-200 {{ request()->routeIs($item['route']) ? 'active text-white' : '' }}">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="{{ $item['icon'] }}" />
                        </svg>

                        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200 delay-100"
                            x-transition:enter-start="opacity-0 transform translate-x-2"
                            x-transition:enter-end="opacity-100 transform translate-x-0"
                            class="ml-4 flex-1 flex items-center justify-between">
                            <span class="font-medium">{{ $item['label'] }}</span>
                            @if ($item['badge'])
                                <span
                                    class="ml-2 px-2 py-1 text-xs font-semibold rounded-full {{ $item['badge'] === 'NEW' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </nav>

            <!-- User Profile Section -->
            <div class="p-4 border-t border-gray-700/50">
                <div class="flex items-center p-3 rounded-xl bg-white/10 backdrop-blur-sm">
                    <div class="relative">
                        <img class="w-12 h-12 rounded-xl object-cover ring-2 ring-white/20"
                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=667eea&color=fff&size=48"
                            alt="{{ auth()->user()->name }}">
                        <div
                            class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-gray-800">
                        </div>
                    </div>

                    <div x-show="sidebarOpen" x-transition class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate capitalize">
                            {{ str_replace('_', ' ', auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header
                class="glass-effect h-20 flex items-center justify-between px-8 border-b border-gray-200/50 dark:border-gray-700/50 relative z-20">
                <!-- Left Section -->
                <div class="flex items-center space-x-6">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @yield('page-subtitle', 'Welcome to NIMR Intranet')
                        </p>
                    </div>
                </div>

                <!-- Center Section - Search -->
                <div class="flex-1 max-w-2xl mx-8">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" placeholder="Search across NIMR Intranet..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-800/50 border-0 rounded-2xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-800 transition-all">
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <!-- Quick Actions -->
                    <div class="hidden md:flex items-center space-x-2">
                        @if (auth()->user()->canCreateAnnouncements())
                            <a href="{{ route('announcements.create') }}"
                                class="btn-primary flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>New Post</span>
                            </a>
                        @endif
                    </div>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="relative p-3 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-5 5-5-5h5v-12" />
                            </svg>
                            <div class="status-indicator"></div>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="absolute right-0 mt-2 w-96 card-modern rounded-2xl shadow-2xl z-50 max-h-96 overflow-hidden">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                            </div>
                            <div class="overflow-y-auto max-h-80">
                                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="font-medium">All caught up!</p>
                                    <p class="text-sm">No new notifications right now.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode"
                        class="p-3 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <svg x-show="!darkMode" class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="darkMode" class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <img class="w-10 h-10 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-700"
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=667eea&color=fff&size=40"
                                alt="{{ auth()->user()->name }}">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Profile Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="absolute right-0 mt-2 w-72 card-modern rounded-2xl shadow-2xl z-50 overflow-hidden">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-3">
                                    <img class="w-12 h-12 rounded-xl object-cover"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=667eea&color=fff&size=48"
                                        alt="{{ auth()->user()->name }}">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ auth()->user()->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ auth()->user()->email }}</p>
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 capitalize mt-1">
                                            {{ str_replace('_', ' ', auth()->user()->role) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main
                class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-100/40 dark:from-gray-900 dark:via-gray-900 dark:to-slate-900 content-area">
                <div class="p-8">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div
                            class="mb-6 p-4 rounded-2xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 flex items-center space-x-3">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 flex items-center space-x-3">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
