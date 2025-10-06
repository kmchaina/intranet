@extends('layouts.dashboard')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                <p class="text-gray-600 mt-1">Manage {{ $user->name }}'s role and organizational assignment</p>
            </div>
            <a href="{{ route('admin.centre.users.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                Back to Users
            </a>
        </div>

        <!-- User Info Card -->
        <div class="bg-white rounded-lg p-6 shadow-card">
            <div class="flex items-center space-x-4">
                <img class="h-16 w-16 rounded-full"
                    src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4A90E2&color=fff&size=64"
                    alt="{{ $user->name }}">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            Current: {{ str_replace('_', ' ', ucwords($user->role)) }}
                        </span>
                        @if ($user->email_verified_at)
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Verified
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending Verification
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg p-6 shadow-card">
            <form method="POST" action="{{ route('admin.centre.users.update', $user) }}" x-data="{
                selectedRole: '{{ $user->role }}',
                selectedStation: '{{ $user->station_id }}',
                stations: {{ $stations->toJson() }}
            }">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name
                                    *</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                    required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address
                                    *</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone
                                    Number</label>
                                <input type="text" id="phone" name="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Employee ID -->
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee
                                    ID</label>
                                <input type="text" id="employee_id" name="employee_id"
                                    value="{{ old('employee_id', $user->employee_id) }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('employee_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">User Role</label>
                        <select name="role" id="role" x-model="selectedRole" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a role...</option>
                            <option value="staff">Staff Member</option>
                            <option value="station_admin">Station Administrator</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Role Description -->
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg space-y-1 text-sm text-gray-600">
                            <div x-show="selectedRole === 'staff'">
                                <strong>Staff Member:</strong> Regular staff member assigned to centre or station.
                            </div>
                            <div x-show="selectedRole === 'station_admin'">
                                <strong>Station Administrator:</strong> Manages a specific research station within your
                                centre.
                            </div>
                        </div>
                    </div>

                    <!-- Station Selection -->
                    <div x-show="selectedRole === 'station_admin'" x-transition>
                        <label for="station_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Research Station
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="station_id" id="station_id" x-model="selectedStation"
                            x-bind:required="selectedRole === 'station_admin'"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a station...</option>
                            <template x-for="station in stations" :key="station.id">
                                <option x-bind:value="station.id" x-text="station.name"></option>
                            </template>
                        </select>
                        @error('station_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Assignment Display -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Current Assignment</h4>
                        <div class="text-sm text-blue-700 space-y-1">
                            @if ($user->station)
                                <p><strong>Station:</strong> {{ $user->station->name }}</p>
                                @if ($user->centre)
                                    <p><strong>Centre:</strong> {{ $user->centre->name }}</p>
                                @endif
                            @elseif($user->centre)
                                <p><strong>Centre:</strong> {{ $user->centre->name }}</p>
                            @else
                                <p>Centre Level</p>
                            @endif
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password (Optional)</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New
                                    Password</label>
                                <input type="password" id="password" name="password" minlength="8"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    minlength="8"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Warnings -->
                    <div x-show="selectedRole === 'station_admin' && !selectedStation" x-transition
                        class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <p class="text-sm text-yellow-700">
                                Station Administrators must be assigned to a specific research station.
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.centre.users.index') }}"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Update User
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Activity Summary -->
        <div class="bg-white rounded-lg p-6 shadow-card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Activity Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-gray-900">
                        {{ $user->created_at->diffForHumans() }}
                    </div>
                    <div class="text-sm text-gray-600">Account Created</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-gray-900">
                        @if ($user->email_verified_at)
                            {{ $user->email_verified_at->diffForHumans() }}
                        @else
                            Not Verified
                        @endif
                    </div>
                    <div class="text-sm text-gray-600">Email Status</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-gray-900">
                        {{ str_replace('_', ' ', ucwords($user->role)) }}
                    </div>
                    <div class="text-sm text-gray-600">Current Role</div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                form.addEventListener('submit', function(e) {
                    const role = document.getElementById('role').value;
                    const stationId = document.getElementById('station_id')?.value;

                    if (role === 'station_admin' && !stationId) {
                        e.preventDefault();
                        alert('Station Administrators must be assigned to a station.');
                        return;
                    }
                });
            });
        </script>
    @endpush
@endsection
