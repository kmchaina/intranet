<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - NIMR Intranet</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gradient-to-br from-gray-50 via-slate-50 to-zinc-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 md:p-12 border border-white/20">
            <!-- Error Code -->
            <div class="text-center mb-8">
                <h1
                    class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-600 to-slate-600 mb-4">
                    500
                </h1>
                <div class="w-24 h-1 bg-gradient-to-r from-gray-600 to-slate-600 mx-auto rounded-full"></div>
            </div>

            <!-- Error Message -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Internal Server Error
                </h2>
                <p class="text-lg text-gray-600 mb-2">
                    Something went wrong on our end.
                </p>
                <p class="text-sm text-gray-500">
                    We've been notified and are working to fix the issue. Please try again in a few moments.
                </p>
            </div>

            <!-- Icon -->
            <div class="flex justify-center mb-8">
                <div
                    class="w-32 h-32 rounded-full bg-gradient-to-br from-gray-100 to-slate-100 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <!-- Information Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900 mb-1">What you can do:</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Refresh this page in a few moments</li>
                            <li>• Clear your browser cache and try again</li>
                            <li>• If the problem persists, contact IT support</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="location.reload()"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh Page
                </button>

                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-600 to-slate-600 text-white rounded-lg hover:from-gray-700 hover:to-slate-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Return to Dashboard
                </a>
            </div>

            <!-- Help Links -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600 mb-4">
                    Problem not resolved?
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <a href="{{ route('feedback.create') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                        📝 Report Issue
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="mailto:itsupport@nimr.or.tz" class="text-gray-600 hover:text-gray-800 font-medium">
                        ✉️ Email IT Support
                    </a>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-500">
                        📞 Ext: 1234
                    </span>
                </div>
            </div>

            <!-- Error Reference (for production) -->
            @if (app()->environment('production') && isset($exception))
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-400">
                        Error Reference: {{ substr(md5($exception->getMessage() . time()), 0, 8) }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-sm text-gray-500">
            <p>NIMR Intranet &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>

</html>
