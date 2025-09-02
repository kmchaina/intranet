@extends('layouts.dashboard')

@section('title', 'Password Details')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900">Password Details</h1>
                    <p class="text-lg text-gray-600 mt-1">View and manage your password</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('password-vault.edit', $password) }}"
                        class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Password
                    </a>
                    <a href="{{ route('password-vault.index') }}"
                        class="flex items-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="p-8">
            <!-- Password Info Card -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="flex items-center mb-4">
                    <!-- Category Dot -->
                    <span
                        class="w-4 h-4 rounded-full mr-3
                                @if ($password->category === 'work') bg-blue-500
                                @elseif($password->category === 'personal') bg-green-500
                                @elseif($password->category === 'banking') bg-red-500
                                @elseif($password->category === 'social') bg-purple-500
                                @else bg-gray-400 @endif"></span>
                    <h3 class="text-xl font-semibold text-gray-800">{{ $password->title }}</h3>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Website/Service -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website/Service</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            @if ($password->website)
                                <a href="{{ $password->website }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 underline">
                                    {{ $password->website }}
                                </a>
                            @else
                                <span class="text-gray-500">No website specified</span>
                            @endif
                        </div>
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200 font-mono">
                            {{ $password->username }}
                        </div>
                    </div>

                    <!-- Password (Hidden by Default) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200 flex items-center justify-between">
                            <input type="password" id="password-field" value="{{ $password->password }}"
                                class="font-mono text-gray-800 bg-transparent border-none outline-none flex-1" readonly>
                            <button onclick="togglePassword()"
                                class="ml-3 text-gray-500 hover:text-gray-700 transition-colors">
                                <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200 capitalize">
                            {{ $password->category }}
                        </div>
                    </div>

                    <!-- Created Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            {{ $password->created_at->format('M j, Y g:i A') }}
                        </div>
                    </div>
                </div>

                <!-- Notes (if any) -->
                @if ($password->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            {{ $password->notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password-field');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordField.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
@endsection
