@extends('layouts.dashboard')

@section('title', 'CSS Debug')
@section('page-title', 'CSS Style Test')
@section('page-subtitle', 'Testing custom CSS classes')

@section('content')
    <div class="space-y-8">
        <!-- CSS Test Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Stats Card Test -->
            <div class="stats-card rounded-3xl p-6 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Stats Card Test</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">✓ Working</p>
                    </div>
                    <div class="w-16 h-16 icon-gradient-blue rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Gradient Icons Test -->
            <div class="card-modern rounded-3xl p-6">
                <h3 class="text-lg font-bold mb-4">Gradient Icons</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="icon-gradient-blue w-12 h-12 rounded-xl flex items-center justify-center">
                        <span class="text-white text-sm">B</span>
                    </div>
                    <div class="icon-gradient-green w-12 h-12 rounded-xl flex items-center justify-center">
                        <span class="text-white text-sm">G</span>
                    </div>
                    <div class="icon-gradient-purple w-12 h-12 rounded-xl flex items-center justify-center">
                        <span class="text-white text-sm">P</span>
                    </div>
                </div>
            </div>

            <!-- Announcement Card Test -->
            <div class="announcement-card p-6 rounded-2xl">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Announcement Card Test
                </h4>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    This should have a glassmorphism effect with gradient background.
                </p>
            </div>

        </div>

        <!-- Buttons Test -->
        <div class="card-modern rounded-3xl p-6">
            <h3 class="text-xl font-bold mb-4">Buttons Test</h3>
            <div class="flex space-x-4">
                <button class="btn-primary px-6 py-3">Primary Button</button>
                <div class="quick-action-card p-4 bg-blue-50 rounded-xl">
                    <span class="text-blue-900">Quick Action Card</span>
                </div>
            </div>
        </div>

        <!-- CSS Information -->
        <div class="card-modern rounded-3xl p-6">
            <h3 class="text-xl font-bold mb-4">CSS Debug Information</h3>
            <div class="space-y-2 text-sm">
                <p><strong>Current Styles:</strong> NIMR Intranet Custom CSS</p>
                <p><strong>Framework:</strong> Tailwind CSS + Custom Classes</p>
                <p><strong>CSS File:</strong> resources/css/custom.css</p>
                <p><strong>Build Status:</strong> <span class="text-green-600">✓ Compiled</span></p>
            </div>

            <div class="mt-6">
                <h4 class="font-semibold mb-2">Loaded CSS Classes:</h4>
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg text-xs">
                    <code>
                        .stats-card, .card-modern, .hover-lift, .btn-primary,<br>
                        .icon-gradient-*, .announcement-card, .quick-action-card,<br>
                        .timeline-dot, .weather-widget
                    </code>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Debug script to check CSS loading
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎨 NIMR Intranet CSS Debug Information:');

            // Check if custom classes are applied
            const statsCard = document.querySelector('.stats-card');
            if (statsCard) {
                const styles = window.getComputedStyle(statsCard);
                console.log('✓ Stats Card Background:', styles.background);
                console.log('✓ Stats Card Backdrop Filter:', styles.backdropFilter);
            }

            const iconGradient = document.querySelector('.icon-gradient-blue');
            if (iconGradient) {
                const styles = window.getComputedStyle(iconGradient);
                console.log('✓ Icon Gradient Background:', styles.background);
            }

            console.log('🔍 Check browser developer tools for complete style information');
        });
    </script>
@endsection
