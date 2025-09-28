<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Dark Mode Initialization -->
    <script>
        // Initialize dark mode before page renders to prevent flash
        (function() {
            const isDark = localStorage.getItem('darkMode') === 'true' || 
                         (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    {{-- Particles Background --}}
    <x-particles-background />

    {{-- Dark Mode Toggle --}}
    <div class="fixed top-4 right-4 z-20">
        <x-dark-mode-toggle />
    </div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-nimr-primary-900/70 via-nimr-primary-800/60 to-nimr-secondary-800/60 relative content-above-particles">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_20%_30%,rgba(255,255,255,0.12),transparent_60%),radial-gradient(circle_at_80%_70%,rgba(255,255,255,0.08),transparent_65%)]"></div>
        <div>
            <a href="/">
                <x-application-logo class="w-24 h-24 text-white drop-shadow" />
            </a>
        </div>
        <div class="w-full sm:max-w-md mt-8 relative glass-card-xl glass-accent-edge panel-hairline-top overflow-hidden">
            <div class="absolute inset-0 pointer-events-none [mask-image:radial-gradient(circle_at_30%_20%,white,transparent)]"></div>
            <div class="relative z-10">{{ $slot }}</div>
        </div>
    </div>
</body>
</html> 
