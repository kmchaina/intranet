<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NIMR Intranet') }} - @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
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

        <div class="relative z-10 w-full max-w-md px-6">
            {{-- Logo - Reduced Size --}}
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center justify-center mb-3">
                    <x-application-logo class="w-20 h-20 drop-shadow-lg" />
                </a>
                <h1 class="text-2xl font-bold">
                    <span class="bg-gradient-to-r from-nimr-primary-600 to-indigo-600 bg-clip-text text-transparent">
                        NIMR Intranet
                    </span>
                </h1>
                <p class="text-nimr-neutral-600 text-xs mt-1">National Institute for Medical Research</p>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-nimr-xl border border-nimr-neutral-200 overflow-hidden">
                <div class="p-8">
                    {{ $slot }}
                </div>
            </div>

            {{-- Footer --}}
            <div class="text-center mt-6 text-sm text-nimr-neutral-600">
                <p>&copy; {{ date('Y') }} NIMR. All rights reserved.</p>
            </div>
        </div>
    </div>

    {{-- Custom Animations --}}
    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>

</html>
