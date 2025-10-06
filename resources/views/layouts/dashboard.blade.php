<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false', init() { this.$watch('sidebarOpen', v => localStorage.setItem('sidebarOpen', v)); } }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - NIMR Intranet</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 antialiased overflow-hidden">
    <div class="flex h-screen">
        @include('layouts.partials.sidebar')

        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 lg:hidden z-30"
            @click="sidebarOpen=false"></div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header
                class="bg-white/95 backdrop-blur border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 relative z-20 shadow-sm">
                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                    <button @click="sidebarOpen=!sidebarOpen" aria-label="Toggle sidebar"
                        class="p-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <div class="flex items-baseline gap-3">
                            <span
                                class="text-lg text-indigo-600 font-bold">{{ \Carbon\Carbon::now()->format('l') }}</span>
                            <span
                                class="text-sm text-gray-600 font-medium">{{ \Carbon\Carbon::now()->format('F j, Y') }}</span>
                            <span
                                class="text-base text-amber-600 font-semibold tracking-wide">{{ \Carbon\Carbon::now()->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 ml-auto">
                    <div class="hidden md:block w-64 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg
                                class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg></div>
                        <input type="search" placeholder="Search…"
                            class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all">
                    </div>
                    <div class="hidden md:flex items-center gap-2"></div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open=!open"
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-3-3.5A5.98 5.98 0 0019 8.5a6 6 0 10-12 0c0 1.677.69 3.2 1.8 4.3L6 17h5m4 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute -top-1 -right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                        </button>
                        <div x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                            </div>
                            <div class="overflow-y-auto max-h-80">
                                <div class="p-6 text-center text-gray-500">
                                    <p class="font-medium text-sm">All caught up!</p>
                                    <p class="text-xs">No new notifications right now.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open=!open"
                                class="flex items-center space-x-2 p-2 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                                <img class="w-8 h-8 rounded-full object-cover border border-gray-200"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=ffffff"
                                    alt="{{ auth()->user()->name }}">
                            </button>
                            <div x-show="open" @click.away="open=false" x-transition
                                class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-200 flex items-center space-x-3">
                                    <img class="w-10 h-10 rounded-full object-cover border border-gray-200"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=ffffff"
                                        alt="{{ auth()->user()->name }}">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <a href="#"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">Profile
                                        Settings</a>
                                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"
                                            class="w-full flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">Sign
                                            Out</button></form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </header>
            <main
                class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-100/40 content-area">
                <div class="p-8">
                    @if (session('success'))
                        <div
                            class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 flex items-center space-x-3">
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 flex items-center space-x-3">
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script src="{{ asset('js/announcement-carousel.js') }}"></script>
    <script>
        (function() {
            const debounce = (fn, wait = 350) => {
                let t;
                return (...a) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(null, a), wait);
                }
            };
            const init = () => {
                document.querySelectorAll('form[data-auto-submit]').forEach(form => {
                    form.addEventListener('change', e => {
                        const t = e.target;
                        if (t && (t.matches('select') || t.matches('input[type="checkbox"]') || t
                                .matches('input[type="radio"]'))) {
                            form.requestSubmit ? form.requestSubmit() : form.submit();
                        }
                    });
                    form.querySelectorAll(
                        'input[type="search"], input[type="text"][name*="search"], input[name="search"]'
                    ).forEach(inp => {
                        inp.addEventListener('input', debounce(() => {
                            form.requestSubmit ? form.requestSubmit() : form.submit();
                        }, 400));
                    });
                });
            };
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
    @include('messaging.widget')
</body>

</html>
