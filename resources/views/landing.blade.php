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

    {{-- Premium Background (Same as Auth Pages) --}}
    <div class="min-h-screen relative overflow-hidden">
        {{-- Animated Background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-nimr-primary-50 via-white to-indigo-50"></div>
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(0,172,237,0.15),transparent_50%),radial-gradient(circle_at_70%_80%,rgba(99,102,241,0.12),transparent_60%)]">
        </div>

        {{-- Floating Elements --}}
        <div
            class="absolute top-10 left-10 w-72 h-72 bg-nimr-primary-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob">
        </div>
        <div
            class="absolute top-20 right-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000">
        </div>

        <div class="relative z-10 min-h-screen flex items-center">
            <div class="w-full max-w-7xl mx-auto px-6 lg:px-12 py-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    {{-- Left Side - Logo & Title --}}
                    <div class="order-2 lg:order-1 text-center flex flex-col items-center justify-center">
                        {{-- Logo --}}
                        <div class="mb-8">
                            <a href="/" class="inline-block group">
                                <div class="relative">
                                    {{-- Premium glow effect --}}
                                    <div class="absolute inset-0 blur-3xl opacity-40 group-hover:opacity-60 transition-opacity duration-500"
                                        style="background: radial-gradient(circle, rgba(255, 255, 255, 0.8) 0%, transparent 70%);">
                                    </div>
                                    <x-application-logo
                                        class="relative w-40 h-40 drop-shadow-2xl transform group-hover:scale-110 transition-transform duration-500" />
                                </div>
                            </a>
                        </div>

                        {{-- Title & Subtitle --}}
                        <h1 class="text-5xl xl:text-6xl font-black leading-tight mb-6"
                            style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: drop-shadow(0 2px 8px rgba(37, 99, 235, 0.15));">
                            Welcome to<br />
                            NIMR Intranet
                        </h1>
                        <p class="text-gray-700 text-xl xl:text-2xl font-medium leading-relaxed max-w-xl">
                            Your centralized hub for announcements, resources, and collaboration
                        </p>

                        {{-- Decorative divider --}}
                        <div class="mt-8 flex items-center justify-center gap-3">
                            <div class="h-px w-16 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                            <div class="flex gap-1">
                                <div class="w-2 h-2 rounded-full" style="background: #2563eb;"></div>
                                <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                                <div class="w-2 h-2 rounded-full" style="background: #2563eb;"></div>
                            </div>
                            <div class="h-px w-16 bg-gradient-to-l from-transparent via-gray-300 to-transparent"></div>
                        </div>
                    </div>

                    {{-- Right Side - Premium Login Form --}}
                    <div class="order-1 lg:order-2 flex justify-center lg:justify-end">
                        <div class="w-full max-w-md">
                            <div class="premium-login-card backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/30 transform hover:scale-[1.01] transition-all duration-500"
                                style="background: rgba(255, 255, 255, 0.98); box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;">

                                <div class="p-10">
                                    <x-auth-session-status class="mb-4" :status="session('status')" />

                                    {{-- Login Form --}}
                                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                                        @csrf

                                        {{-- Email --}}
                                        <div>
                                            <label for="email"
                                                class="block text-sm font-semibold text-gray-700 mb-2">Email
                                                Address</label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                                    </svg>
                                                </div>
                                                <input id="email" type="email" name="email"
                                                    value="{{ old('email') }}" required autofocus
                                                    autocomplete="username" class="input pl-12 h-12"
                                                    placeholder="your.email@nimr.or.tz" />
                                            </div>
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        {{-- Password --}}
                                        <div>
                                            <label for="password"
                                                class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                                            <div class="relative" x-data="{ show: false }">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                </div>
                                                <input id="password" :type="show ? 'text' : 'password'" name="password"
                                                    required autocomplete="current-password"
                                                    class="input pl-12 pr-12 h-12" placeholder="Enter your password" />
                                                <button type="button" @click="show = !show"
                                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
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
                                                <span class="ml-2 text-sm font-medium text-gray-700">Remember me</span>
                                            </label>

                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" style="color: #2563eb;"
                                                    class="text-sm font-semibold hover:underline transition-colors"
                                                    onmouseover="this.style.color='#1e40af'"
                                                    onmouseout="this.style.color='#2563eb'">
                                                    Forgot password?
                                                </a>
                                            @endif
                                        </div>

                                        {{-- Submit Button --}}
                                        <button type="submit"
                                            class="btn btn-primary w-full h-12 text-base font-semibold">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                            Sign In
                                        </button>

                                        {{-- Register Link --}}
                                        @if (Route::has('register'))
                                            <div class="text-center pt-5 border-t border-gray-200">
                                                <p class="text-sm text-gray-600">
                                                    Don't have an account?
                                                    <a href="{{ route('register') }}" style="color: #2563eb;"
                                                        class="font-semibold hover:underline transition-colors"
                                                        onmouseover="this.style.color='#1e40af'"
                                                        onmouseout="this.style.color='#2563eb'">
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
        <footer class="absolute bottom-0 left-0 right-0 py-6 text-center">
            <p class="text-gray-600 text-sm font-medium">
                &copy; {{ date('Y') }} National Institute for Medical Research (NIMR). All rights reserved.
            </p>
            <p class="text-gray-500 text-xs mt-1">
                Secure • Private • Professional
            </p>
        </footer>
    </div>

    {{-- Premium Animations --}}
    <style>
        @keyframes blob {

            0%,
            100% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(40px, -60px) scale(1.2);
            }

            66% {
                transform: translate(-30px, 40px) scale(0.9);
            }
        }

        .animate-blob {
            animation: blob 10s infinite cubic-bezier(0.45, 0.05, 0.55, 0.95);
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Premium Login Card Effects */
        .premium-login-card {
            position: relative;
        }

        .premium-login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.3));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: 0.6;
        }
    </style>

</body>

</html>
