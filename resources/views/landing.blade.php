<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NIMR Intranet') }} - Welcome</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased">

    {{-- Single Background with Gradient --}}
    <div
        class="min-h-screen relative overflow-hidden bg-gradient-to-br from-nimr-primary-500 via-nimr-primary-600 to-indigo-700">
        {{-- Decorative Elements --}}
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
        </div>
        <div
            class="absolute top-1/2 left-1/2 w-96 h-96 bg-white/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2">
        </div>

        <div class="relative z-10 min-h-screen flex items-center">
            <div class="w-full max-w-7xl mx-auto px-6 lg:px-12 py-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                    {{-- Left Side - Content & Logo --}}
                    <div class="order-2 lg:order-1">
                        {{-- Logo & Title - Logo Centered in Left Column --}}
                        <div class="mb-8 text-center lg:text-center">
                            <div class="flex justify-center mb-6">
                                <a href="/" class="inline-block">
                                    <x-application-logo class="w-32 h-32 drop-shadow-2xl" />
                                </a>
                            </div>
                            <h1
                                class="text-4xl xl:text-5xl font-extrabold text-white leading-tight drop-shadow-lg mb-4">
                                Welcome to<br />NIMR Intranet
                            </h1>
                            <p class="text-white/90 text-lg xl:text-xl max-w-lg mx-auto">
                                Your centralized hub for announcements, resources, and collaboration.
                            </p>
                        </div>

                        {{-- Features List --}}
                        <div class="space-y-4 max-w-lg mx-auto">
                            <div
                                class="flex items-start gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-colors">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold">Organization-wide Announcements</h3>
                                    <p class="text-white/80 text-sm mt-1">Stay updated with the latest institute news
                                        and updates</p>
                                </div>
                            </div>

                            <div
                                class="flex items-start gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-colors">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold">Document Library</h3>
                                    <p class="text-white/80 text-sm mt-1">Access important documents, policies, and
                                        resources</p>
                                </div>
                            </div>

                            <div
                                class="flex items-start gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-colors">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold">Events Calendar</h3>
                                    <p class="text-white/80 text-sm mt-1">Never miss important events, meetings, and
                                        deadlines</p>
                                </div>
                            </div>

                            <div
                                class="flex items-start gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-colors">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold">Staff Directory</h3>
                                    <p class="text-white/80 text-sm mt-1">Connect with colleagues across all centres and
                                        stations</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side - Login Form --}}
                    <div class="order-1 lg:order-2 flex justify-center lg:justify-end">
                        <div class="w-full max-w-md">
                            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                                <div class="p-8">
                                    {{-- Header --}}
                                    <div class="mb-6 text-center">
                                        <h2 class="text-2xl font-bold text-nimr-neutral-900">Welcome Back</h2>
                                        <p class="text-sm text-nimr-neutral-600 mt-1">Sign in to access your dashboard
                                        </p>
                                    </div>

                                    <x-auth-session-status class="mb-4" :status="session('status')" />

                                    {{-- Login Form --}}
                                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                                        @csrf

                                        {{-- Email --}}
                                        <div>
                                            <label for="email"
                                                class="block text-sm font-medium text-nimr-neutral-700 mb-2">Email
                                                Address</label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-nimr-neutral-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                                    </svg>
                                                </div>
                                                <input id="email" type="email" name="email"
                                                    value="{{ old('email') }}" required autofocus
                                                    autocomplete="username" class="input pl-10"
                                                    placeholder="your.email@nimr.or.tz" />
                                            </div>
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        {{-- Password --}}
                                        <div>
                                            <label for="password"
                                                class="block text-sm font-medium text-nimr-neutral-700 mb-2">Password</label>
                                            <div class="relative" x-data="{ show: false }">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-nimr-neutral-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                </div>
                                                <input id="password" :type="show ? 'text' : 'password'"
                                                    name="password" required autocomplete="current-password"
                                                    class="input pl-10 pr-10" placeholder="Enter your password" />
                                                <button type="button" @click="show = !show"
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-nimr-neutral-400 hover:text-nimr-neutral-600 transition-colors">
                                                    <svg x-show="!show" class="h-5 w-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg x-show="show" class="h-5 w-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        {{-- Remember & Forgot Password --}}
                                        <div class="flex items-center justify-between">
                                            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                                <input id="remember_me" name="remember" type="checkbox"
                                                    class="checkbox">
                                                <span class="ml-2 text-sm text-nimr-neutral-700">Remember me</span>
                                            </label>

                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}"
                                                    class="text-sm font-medium text-nimr-primary-600 hover:text-nimr-primary-700 hover:underline transition-colors">
                                                    Forgot password?
                                                </a>
                                            @endif
                                        </div>

                                        {{-- Submit Button --}}
                                        <button type="submit" class="btn btn-primary w-full">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                            Sign In
                                        </button>

                                        {{-- Register Link --}}
                                        @if (Route::has('register'))
                                            <div class="text-center pt-4 border-t border-nimr-neutral-200">
                                                <p class="text-sm text-nimr-neutral-600">
                                                    Don't have an account?
                                                    <a href="{{ route('register') }}"
                                                        class="font-medium text-nimr-primary-600 hover:text-nimr-primary-700 hover:underline transition-colors">
                                                        Create one here
                                                    </a>
                                                </p>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="absolute bottom-0 left-0 right-0 py-4 text-center text-white/80 text-sm">
            &copy; {{ date('Y') }} National Institute for Medical Research (NIMR). All rights reserved.
        </footer>
    </div>

</body>

</html>
