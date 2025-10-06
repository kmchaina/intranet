<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - NIMR Intranet</title>
    @vite(['resources/css/app.css'])
</head>

<body
    class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 md:p-12 border border-white/20">
            <!-- Error Code -->
            <div class="text-center mb-8">
                <h1
                    class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-4">
                    404
                </h1>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-purple-600 mx-auto rounded-full"></div>
            </div>

            <!-- Error Message -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Page Not Found
                </h2>
                <p class="text-lg text-gray-600 mb-2">
                    Oops! The page you're looking for doesn't exist.
                </p>
                <p class="text-sm text-gray-500">
                    It might have been moved, deleted, or the URL might be incorrect.
                </p>
            </div>

            <!-- Illustration -->
            <div class="flex justify-center mb-8">
                <svg class="w-64 h-64 text-blue-100" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="javascript:history.back()"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Go Back
                </a>

                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
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
                    Need assistance?
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <a href="{{ route('search') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        🔍 Search
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('feedback.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        💬 Contact Support
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="mailto:itsupport@nimr.or.tz" class="text-blue-600 hover:text-blue-800 font-medium">
                        ✉️ Email IT
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-sm text-gray-500">
            <p>NIMR Intranet &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>

</html>
