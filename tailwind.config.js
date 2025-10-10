import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';
import lineClamp from '@tailwindcss/line-clamp';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        {
            pattern: /(bg|text)-(red|orange|green|blue|gray|yellow)-(100|600|800)/,
            variants: ['hover'],
        },
        'status-active',
        'status-draft',
        'status-closed',
        'status-error',
    ],

    // Add custom styles for status indicators
    plugins: [
        forms,
        typography,
        aspectRatio,
        lineClamp,
        function({ addComponents }) {
            addComponents({
                '.status-active': {
                    '@apply bg-green-100 text-green-800': {},
                },
                '.status-draft': {
                    '@apply bg-gray-100 text-gray-800': {},
                },
                '.status-closed': {
                    '@apply bg-yellow-100 text-yellow-800': {},
                },
                '.status-error': {
                    '@apply bg-red-100 text-red-800': {},
                },
            })
        },
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', '-apple-system', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // NIMR Professional Color System
                'nimr': {
                    // Primary - NIMR Cyan (Innovation, Trust, Clarity)
                    'primary': {
                        50: '#e6f9ff',   // Lightest cyan tint
                        100: '#ccf3ff',  // Very light cyan
                        200: '#99e7ff',  // Light cyan
                        300: '#66dbff',  // Soft cyan
                        400: '#33ceff',  // Medium cyan
                        500: '#00aced',  // NIMR Brand Color ⭐
                        600: '#0099d4',  // Darker cyan
                        700: '#0086bb',  // Deep cyan
                        800: '#0073a2',  // Very deep cyan
                        900: '#006089',  // Darkest cyan
                        950: '#004d70',  // Ultra dark cyan
                    },
                    // Secondary - Research Green (Health, Growth)
                    'secondary': {
                        50: '#f0fdf4',
                        100: '#dcfce7',
                        200: '#bbf7d0',
                        300: '#86efac',
                        400: '#4ade80',
                        500: '#22c55e',
                        600: '#16a34a',
                        700: '#15803d',  // Main secondary
                        800: '#166534',
                        900: '#14532d',
                        950: '#052e16',
                    },
                    // Accent - Warm Orange (Action, Energy)
                    'accent': {
                        50: '#fff7ed',
                        100: '#ffedd5',
                        200: '#fed7aa',
                        300: '#fdba74',
                        400: '#fb923c',
                        500: '#f97316',
                        600: '#ea580c',  // Main accent
                        700: '#c2410c',
                        800: '#9a3412',
                        900: '#7c2d12',
                        950: '#431407',
                    },
                    // Neutral - Professional Grays
                    'neutral': {
                        50: '#f8fafc',
                        100: '#f1f5f9',
                        200: '#e2e8f0',
                        300: '#cbd5e1',
                        400: '#94a3b8',
                        500: '#64748b',
                        600: '#475569',  // Main neutral
                        700: '#334155',
                        800: '#1e293b',
                        900: '#0f172a',
                        950: '#020617',
                    },
                },
                // Semantic colors for feedback
                'feedback': {
                    'success': {
                        50: '#f0fdf4',
                        500: '#22c55e',
                        600: '#16a34a',
                        700: '#15803d',
                    },
                    'warning': {
                        50: '#fffbeb',
                        500: '#f59e0b',
                        600: '#d97706',
                        700: '#b45309',
                    },
                    'error': {
                        50: '#fef2f2',
                        500: '#ef4444',
                        600: '#dc2626',
                        700: '#b91c1c',
                    },
                    'info': {
                        50: '#eff6ff',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                    },
                },
            },
            boxShadow: {
                'nimr-sm': '0 2px 4px rgba(0, 172, 237, 0.1)',
                'nimr-md': '0 4px 8px rgba(0, 172, 237, 0.12)',
                'nimr-lg': '0 8px 16px rgba(0, 172, 237, 0.15)',
                'nimr-xl': '0 12px 24px rgba(0, 172, 237, 0.18)',
                'nimr-2xl': '0 16px 32px rgba(0, 172, 237, 0.2)',
                'nimr-glow': '0 0 20px rgba(0, 172, 237, 0.3)',
                'nimr-glow-lg': '0 0 40px rgba(0, 172, 237, 0.4)',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.8' },
                },
            },
        },
    },

    plugins: [forms, typography, aspectRatio, lineClamp],
};
