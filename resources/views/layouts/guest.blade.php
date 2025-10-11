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
        {{-- Premium Gradient Background --}}
        <div class="absolute inset-0"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%); opacity: 0.03;">
        </div>
        <div class="absolute inset-0"
            style="background: radial-gradient(circle at 20% 20%, rgba(37, 99, 235, 0.08) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.06) 0%, transparent 50%);">
        </div>

        {{-- Mesh Gradient Overlay --}}
        <div class="absolute inset-0 opacity-30">
            <div
                class="absolute top-0 -left-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob">
            </div>
            <div
                class="absolute top-0 -right-4 w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000">
            </div>
        </div>

        {{-- Grid Pattern Overlay --}}
        <div class="absolute inset-0 opacity-[0.015]"
            style="background-image: linear-gradient(rgba(37, 99, 235, 0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(37, 99, 235, 0.5) 1px, transparent 1px); background-size: 50px 50px;">
        </div>

        <div class="relative z-10 w-full max-w-xl px-6">
            {{-- Premium Logo & Branding --}}
            <div class="text-center mb-10">
                <a href="/" class="inline-block mb-4 group">
                    <div class="relative">
                        {{-- Glow effect behind logo --}}
                        <div class="absolute inset-0 blur-2xl opacity-50 group-hover:opacity-75 transition-opacity duration-300"
                            style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);"></div>
                        <x-application-logo
                            class="relative w-24 h-24 drop-shadow-2xl transform group-hover:scale-105 transition-transform duration-300" />
                    </div>
                </a>

                {{-- Enhanced Branding --}}
                <h1 class="text-3xl font-extrabold mb-2 tracking-tight">
                    <span
                        style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 50%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: drop-shadow(0 2px 8px rgba(37, 99, 235, 0.15));">
                        NIMR Intranet
                    </span>
                </h1>
                <p class="text-sm text-gray-600 font-medium">National Institute for Medical Research</p>
                <div class="mt-3 flex items-center justify-center gap-2">
                    <div class="h-px w-8 bg-gradient-to-r from-transparent to-gray-300"></div>
                    <div class="w-1.5 h-1.5 rounded-full" style="background: #2563eb;"></div>
                    <div class="h-px w-8 bg-gradient-to-l from-transparent to-gray-300"></div>
                </div>
            </div>

            {{-- Premium Glass Card --}}
            <div class="premium-auth-card backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/20 transform hover:scale-[1.01] transition-all duration-300"
                style="background: rgba(255, 255, 255, 0.95); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;">

                {{-- Subtle top border accent --}}
                <div class="h-1 w-full"
                    style="background: linear-gradient(90deg, #2563eb 0%, #3b82f6 50%, #2563eb 100%);"></div>

                <div class="p-7 sm:p-8">
                    {{ $slot }}
                </div>
            </div>

            {{-- Premium Footer --}}
            <div class="text-center mt-8 space-y-2">
                <p class="text-sm text-gray-600 font-medium">&copy; {{ date('Y') }} NIMR. All rights reserved.</p>
                <p class="text-xs text-gray-500">Secure • Private • Professional</p>
            </div>
        </div>
    </div>

    {{-- Premium Animations --}}
    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(40px, -60px) scale(1.15);
            }

            66% {
                transform: translate(-30px, 30px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 8s infinite cubic-bezier(0.45, 0.05, 0.55, 0.95);
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Premium card hover effect */
        .premium-auth-card {
            position: relative;
        }

        .premium-auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.2));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: 0.5;
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Premium focus states */
        .input:focus,
        .select:focus,
        .btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
    </style>
</body>

</html>
