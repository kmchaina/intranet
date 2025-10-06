<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - NIMR Intranet</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl p-8 md:p-12 border border-white/20">
            <!-- Error Code -->
            <div class="text-center mb-8">
                <h1
                    class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-600 mb-4">
                    403
                </h1>
                <div class="w-24 h-1 bg-gradient-to-r from-red-600 to-orange-600 mx-auto rounded-full"></div>
            </div>

            <!-- Error Message -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Access Denied
                </h2>
                <p class="text-lg text-gray-600 mb-2">
                    You don't have permission to access this resource.
                </p>
                <p class="text-sm text-gray-500">
                    This page is restricted to users with specific roles or permissions.
                </p>
            </div>

            <!-- Icon -->
            <div class="flex justify-center mb-8">
                <div
                    class="w-32 h-32 rounded-full bg-gradient-to-br from-red-100 to-orange-100 flex items-center justify-center">
                    <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <!-- Information Box -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-amber-900 mb-1">Why am I seeing this?</h3>
                        <p class="text-sm text-amber-800">
                            This content is restricted based on your user role. If you believe this is an error, please
                            contact your administrator.
                        </p>
                    </div>
                </div>
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
                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg hover:from-red-700 hover:to-orange-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
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
                    Need access to this resource?
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <a href="{{ route('feedback.create') }}" class="text-red-600 hover:text-red-800 font-medium">
                        📝 Request Access
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="mailto:itsupport@nimr.or.tz" class="text-red-600 hover:text-red-800 font-medium">
                        ✉️ Contact Administrator
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
