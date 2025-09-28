<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NIMR Intranet') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col font-sans relative">
    <x-particles-background />
    <!-- Hero background image with blue overlay -->
    <div class="fixed inset-0 -z-10">
        {{-- TODO: Move this logic to a view composer or controller --}}
        @php
            $hero = file_exists(public_path('images/hero/nimr-hero.jpg'))
                ? asset('images/hero/nimr-hero.jpg')
                : 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=2400&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=';
        @endphp
    <div class="absolute inset-0 bg-center bg-cover brightness-50" style="background-image: url('{{ $hero }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-nimr-primary-900/85 via-nimr-primary-700/75 to-nimr-secondary-800/70"></div>
    </div>
    <div class="relative z-10 flex-1 grid grid-cols-1 lg:grid-cols-2">
        <!-- Left: Brand / Messaging -->
    <section class="hidden lg:flex flex-col justify-between p-10 lg:pl-56 xl:pl-64">
            <div class="flex flex-col flex-1">
                <div class="pt-2 pl-8">
                    <a href="/" class="inline-flex items-center gap-4 text-gray-700">
                        <x-application-logo class="w-32 h-32" />
                    </a>
                </div>
                <div class="flex-1 flex items-center pl-8">
                    <div class="max-w-xl mt-8 lg:mt-4">
                        <h1 class="text-5xl font-extrabold text-white leading-tight drop-shadow">{{ __('Welcome to NIMR Intranet') }}</h1>
                        <p class="mt-4 text-white/95 text-lg drop-shadow">
                            {{ __('Find announcements, events, documents, and tools — all in one place.') }}
                        </p>
                        <ul class="mt-6 space-y-3 text-white">
                            <li class="flex items-center gap-3"><span class="text-green-300">✓</span> {{ __('Centralized resources and quick links') }}</li>
                            <li class="flex items-center gap-3"><span class="text-green-300">✓</span> {{ __('Organization-wide announcements') }}</li>
                            <li class="flex items-center gap-3"><span class="text-green-300">✓</span> {{ __('Events calendar and staff directory') }}</li>
                        </ul>
                    </div>
                </div>
                <!-- Footer removed; now global below -->
            </div>
        </section>

        <!-- Right: Auth Panel -->
    <section class="flex items-center justify-center p-6 lg:p-10">
            <!-- Mobile hero text -->
            <div class="absolute top-6 left-6 right-6 lg:hidden">
                <h1 class="text-2xl font-bold text-white drop-shadow-sm">{{ __('Welcome to NIMR Intranet') }}</h1>
                <p class="mt-2 text-white/90 text-sm">{{ __('Find announcements, events, documents, and tools — all in one place.') }}</p>
            </div>
            <div class="w-full max-w-md relative glass-card-xl glass-accent-edge panel-hairline-top">
                <div class="absolute inset-0 rounded-2xl pointer-events-none [mask-image:radial-gradient(circle_at_30%_20%,white,transparent)]"></div>
                <div class="absolute -top-px inset-x-0 h-px rounded-t-2xl bg-gradient-to-r from-white/0 via-white/40 to-white/0"></div>
                <h2 class="text-2xl font-semibold text-white mb-1 tracking-tight">{{ __('Welcome back') }}</h2>
                <p class="text-sm text-white mb-6">{{ __('Sign in to continue') }}</p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-white/80" />
                        <x-text-input id="email" class="block mt-1 w-full bg-white/15 border-white/20 placeholder-white/40 text-white focus:bg-white/20 focus:border-white/40 focus:ring-2 focus:ring-indigo-300/40 focus:outline-none transition" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-white/80" />
                        <div class="relative">
                            <x-text-input id="password" class="block mt-1 w-full pr-10 bg-white/15 border-white/20 placeholder-white/40 text-white focus:bg-white/20 focus:border-white/40 focus:ring-2 focus:ring-indigo-300/40 focus:outline-none transition" type="password" name="password" required autocomplete="current-password" />
                            <button type="button" id="toggle-password" aria-label="Show password" class="absolute inset-y-0 right-0 px-3 flex items-center text-white/50 hover:text-white/80 transition">
                                <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .95-3.036 3.29-5.43 6.21-6.575M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c1.206 0 2.37.246 3.45.696M19.042 16.542A8.268 8.268 0 0012 19c-4.478 0-8.268-2.943-9.542-7 .95-3.036 3.29-5.43 6.21-6.575" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l22 22" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center gap-2">
                            <input id="remember_me" name="remember" type="checkbox" class="rounded bg-white/10 border-white/30 text-indigo-400 focus:ring-indigo-300/50 focus:ring-2">
                            <span class="text-sm text-white">{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-indigo-300 hover:text-indigo-200 underline-offset-4 hover:underline transition" href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
                        @endif
                    </div>

                    <x-primary-button class="w-full justify-center bg-indigo-500/80 hover:bg-indigo-500 text-white backdrop-blur-sm border border-white/20 shadow-lg shadow-indigo-900/30 transition">{{ __('Sign in') }}</x-primary-button>
                </form>
                @if (Route::has('register'))
                    <p class="mt-6 text-center text-sm text-white">
                        {{ __('No account?') }}
                        <a href="{{ route('register') }}" class="text-indigo-300 hover:text-indigo-200 font-medium underline-offset-4 hover:underline transition">{{ __('Create account') }}</a>
                    </p>
                @endif
            </div>
        </section>
    </div>
    <!-- Global footer -->
    <footer class="relative z-10 w-full py-6 text-center text-white/80 text-sm mt-auto">
        {{ __('©') }} {{ date('Y') }} {{ __('NIMR. All rights reserved.') }}
    </footer>
    @push('scripts')
    <script type="module">import '../js/auth.js';</script>
    @endpush
</body>
</html>