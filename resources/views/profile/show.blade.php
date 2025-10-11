@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto p-6">
            <x-breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'My Profile']]" />

            <!-- Header Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                <div class="bg-blue-600 p-6 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center border border-white/20">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">⚙️ My Profile</h1>
                                <p class="text-blue-100 mt-1">View and manage your personal information</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center px-4 py-2 bg-white hover:bg-blue-50 text-blue-700 text-sm font-medium rounded-lg transition-colors duration-200 border border-white shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200">
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Profile Information -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                            <div class="p-6 border-b border-gray-100">
                                <h2 class="text-lg font-semibold text-gray-900">Personal Information</h2>
                                <p class="text-sm text-gray-600 mt-1">Your basic profile information</p>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Name -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Full Name</label>
                                        <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Email Address</label>
                                        <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <p class="text-gray-900">{{ $user->email }}</p>
                                            @if (!$user->email_verified_at)
                                                <p class="text-xs text-red-600 mt-1">⚠️ Email not verified</p>
                                            @else
                                                <p class="text-xs text-green-600 mt-1">✅ Email verified</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Role -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Role</label>
                                        <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if ($user->role === 'super_admin') bg-red-100 text-red-800
                                    @elseif($user->role === 'hq_admin') bg-purple-100 text-purple-800
                                    @elseif($user->role === 'centre_admin') bg-blue-100 text-blue-800
                                    @elseif($user->role === 'station_admin') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Location -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Work Location</label>
                                        <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <p class="text-gray-900">
                                                @if ($user->station)
                                                    {{ $user->station->name }}
                                                @elseif($user->centre)
                                                    {{ $user->centre->name }}
                                                @else
                                                    NIMR Headquarters
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Phone Number</label>
                                        <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <p class="text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                                        </div>
                                    </div>

                                    <!-- Date of Birth -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Date of Birth</label>
                                        <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <p class="text-gray-900">
                                                @if ($user->birth_date)
                                                    {{ \Carbon\Carbon::parse($user->birth_date)->format('F j, Y') }}
                                                @else
                                                    Not provided
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100">
                            <div class="p-6 border-b border-gray-100">
                                <h2 class="text-lg font-semibold text-gray-900">Account Information</h2>
                                <p class="text-sm text-gray-600 mt-1">Account status and activity</p>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Account Created -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Member Since</label>
                                        <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <p class="text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Last Updated -->
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Profile Updated</label>
                                        <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                            <p class="text-gray-900">{{ $user->updated_at->format('F j, Y') }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $user->updated_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Actions & Statistics -->
                    <div class="space-y-6">
                        <!-- Profile Picture -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                            <div class="p-6 text-center">
                                @if ($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                                        class="w-24 h-24 rounded-full mx-auto mb-4 object-cover border-4 border-gray-100">
                                @else
                                    <div
                                        class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-blue-100">
                                        <span class="text-2xl font-bold text-white">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif

                                <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>

                                <!-- Upload Profile Picture Form -->
                                <form action="{{ route('profile.picture.update') }}" method="POST"
                                    enctype="multipart/form-data" class="mt-4">
                                    @csrf
                                    <label for="profile_picture"
                                        class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Change Picture
                                    </label>
                                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                                        class="hidden" onchange="this.form.submit()">
                                </form>
                                <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (max. 2MB)</p>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                            <div class="p-6 border-b border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                            </div>
                            <div class="p-6 space-y-3">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Edit
                                        Profile</span>
                                </a>

                                <a href="{{ route('password-vault.index') }}"
                                    class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Password
                                        Vault</span>
                                </a>

                                <a href="{{ route('todos.index') }}"
                                    class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">My
                                        Tasks</span>
                                </a>

                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Dashboard</span>
                                </a>
                            </div>
                        </div>

                        <!-- Account Statistics -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                            <div class="p-6 border-b border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900">Activity Stats</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Account Age</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $user->created_at->diffForHumans(null, true) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Last Login</span>
                                    <span class="text-sm font-medium text-gray-900">Today</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Profile Completion</span>
                                    <span class="text-sm font-medium text-gray-900">
                                        @php
                                            $completion = 0;
                                            $completion += $user->name ? 15 : 0;
                                            $completion += $user->email ? 15 : 0;
                                            $completion += $user->phone ? 15 : 0;
                                            $completion += $user->birth_date ? 15 : 0;
                                            $completion += $user->profile_picture ? 40 : 0;
                                        @endphp
                                        {{ $completion }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
