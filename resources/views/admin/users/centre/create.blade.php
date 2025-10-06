@extends('layouts.dashboard')

@section('title', 'Create User')
@section('page-title', 'Create New User')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.centre.users.index') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Users
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('admin.centre.users.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column - Basic Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>

                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name
                                        *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address
                                        *</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-300 @enderror">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Employee ID -->
                                <div class="mb-4">
                                    <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee
                                        ID</label>
                                    <input type="text" id="employee_id" name="employee_id"
                                        value="{{ old('employee_id') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('employee_id') border-red-300 @enderror">
                                    @error('employee_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone
                                        Number</label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('phone') border-red-300 @enderror">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Role & Organizational Assignment -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Role & Organizational Assignment</h3>

                                <!-- Role -->
                                <div class="mb-4">
                                    <label for="role" class="block text-sm font-medium text-gray-700">User Role
                                        *</label>
                                    <select id="role" name="role" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('role') border-red-300 @enderror">
                                        <option value="">Select a Role</option>
                                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff
                                        </option>
                                        <option value="station_admin"
                                            {{ old('role') == 'station_admin' ? 'selected' : '' }}>Station Admin</option>
                                    </select>
                                    @error('role')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <div
                                        class="mt-3 p-3 bg-blue-50 border border-blue-100 rounded-md text-sm text-blue-700 space-y-1">
                                        <p><strong>Staff:</strong> Regular staff member assigned to centre or station.</p>
                                        <p><strong>Station Admin:</strong> Administrator for a specific station within your
                                            centre.</p>
                                    </div>
                                </div>

                                <!-- Station Selection -->
                                <div class="mb-4" id="station-selection" style="display: none;">
                                    <label for="station_id" class="block text-sm font-medium text-gray-700">Station
                                        *</label>
                                    <select id="station_id" name="station_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('station_id') border-red-300 @enderror">
                                        <option value="">Select a Station</option>
                                        @foreach ($stations as $station)
                                            <option value="{{ $station->id }}"
                                                {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                                {{ $station->name }} ({{ $station->code }}) - {{ $station->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('station_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Account Security</h3>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                                    <input type="password" id="password" name="password" required minlength="8"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-300 @enderror">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">Minimum 8 characters</p>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required
                                        minlength="8"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password_confirmation') border-red-300 @enderror">
                                    @error('password_confirmation')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">
                                                Email Verification
                                            </h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <p>The user's email will be automatically verified upon creation.
                                                    They will receive their login credentials via email.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('admin.centre.users.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const stationSelection = document.getElementById('station-selection');
            const stationSelect = document.getElementById('station_id');

            function updateStationVisibility() {
                if (roleSelect.value === 'station_admin') {
                    stationSelection.style.display = 'block';
                    stationSelect.required = true;
                } else {
                    stationSelection.style.display = 'none';
                    stationSelect.required = false;
                    stationSelect.value = '';
                }
            }

            // Initial state
            updateStationVisibility();

            // Listen for changes
            roleSelect.addEventListener('change', updateStationVisibility);
        });
    </script>
@endsection
