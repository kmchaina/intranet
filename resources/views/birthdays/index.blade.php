@extends('layouts.dashboard')

@section('title', 'Birthdays')

@section('content')
    <div class="max-w-7xl mx-auto">
        <x-breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Birthdays']]" />

        <!-- Premium Header -->
        <div class="card-premium overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-pink-600 via-purple-600 to-indigo-600 p-8 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">🎂 Birthdays</h1>
                        <p class="text-white/90 mt-1">Celebrate your colleagues' special days</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Birthdays -->
        <div class="card-premium overflow-hidden mb-8">
            <div class="p-6 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-blue-100">
                <h2 class="text-lg font-bold text-nimr-neutral-900 flex items-center gap-2">
                    🎂 Today's Birthdays
                    @if ($todaysBirthdays->count() > 0)
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ $todaysBirthdays->count() }}
                        </span>
                    @endif
                </h2>
            </div>
            <div class="p-6">
                @if ($todaysBirthdays->count() > 0)
                    <div class="space-y-4">
                        @foreach ($todaysBirthdays as $user)
                            <div
                                class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl border border-blue-200 hover:shadow-md transition-all duration-200 group">
                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                    <div
                                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0 group-hover:scale-110 transition-transform">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-nimr-neutral-900 text-lg truncate">{{ $user->name }}
                                        </h3>
                                        <p class="text-sm text-nimr-neutral-600 font-medium">
                                            Happy Birthday! 🎉
                                        </p>
                                        @if ($user->centre || $user->station || $user->headquarters)
                                            <p class="text-xs text-nimr-neutral-500 mt-1 truncate">
                                                @if ($user->centre)
                                                    {{ $user->centre->name }}
                                                @elseif($user->station)
                                                    {{ $user->station->name }}
                                                @elseif($user->headquarters)
                                                    {{ $user->headquarters->name }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('birthdays.wishes', $user) }}"
                                    class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl text-sm font-bold transition-all duration-200 shadow-lg hover:shadow-xl flex-shrink-0 ml-4">
                                    🎉 Send Wish
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-12">
                        <div class="empty-state-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="empty-state-title">No birthdays today</p>
                        <p class="empty-state-description">Check back tomorrow!</p>
                        @if (!auth()->user()->birth_date)
                            <div
                                class="mt-6 p-6 bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-xl max-w-md mx-auto">
                                <div class="text-center">
                                    <p class="text-2xl mb-2">🎉</p>
                                    <h3 class="text-lg font-bold text-blue-800 mb-2">Want us to celebrate your birthday?
                                    </h3>
                                    <p class="text-sm text-blue-600 mb-4">Add your birth date so we can celebrate with you
                                        and your colleagues!</p>
                                    <a href="#birthday-settings"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                        Add My Birthday
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- This Week's Upcoming Birthdays -->
        @if ($weekBirthdays->count() > 0)
            <div class="card-premium overflow-hidden mb-8">
                <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-purple-100">
                    <h2 class="text-lg font-bold text-nimr-neutral-900 flex items-center gap-2">
                        📅 This Week's Birthdays
                        <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ $weekBirthdays->count() }}
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($weekBirthdays as $user)
                            @php
                                $today = now()->startOfDay();
                                $birthdate = $user->birth_date->copy()->setYear($today->year);

                                // If birthday already passed this year, use next year
                                if ($birthdate->lessThan($today)) {
                                    $birthdate->addYear();
                                }

                                // Calculate days until (will be 1-7 for this week's upcoming birthdays)
                                $daysUntil = intval($today->diffInDays($birthdate, false));
                            @endphp
                            <div
                                class="p-5 border-2 border-purple-200 rounded-2xl hover:bg-purple-50 hover:border-purple-300 transition-all duration-200 hover:shadow-md group">
                                <div class="flex items-center gap-3 mb-3">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-nimr-neutral-900 truncate">{{ $user->name }}</h3>
                                        <p class="text-sm text-nimr-neutral-600 font-medium">
                                            {{ $birthdate->format('M j') }}
                                        </p>
                                    </div>
                                </div>
                                @if ($daysUntil > 0)
                                    <div
                                        class="bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-lg inline-block">
                                        {{ $daysUntil }} {{ Str::plural('day', $daysUntil) }} away
                                    </div>
                                @endif
                                @if ($user->centre || $user->station || $user->headquarters)
                                    <p class="text-xs text-nimr-neutral-500 mt-2 truncate">
                                        @if ($user->centre)
                                            {{ $user->centre->name }}
                                        @elseif($user->station)
                                            {{ $user->station->name }}
                                        @elseif($user->headquarters)
                                            {{ $user->headquarters->name }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Update Profile Section -->
        <div class="card-premium overflow-hidden" id="birthday-settings">
            <div class="p-6 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-gray-200">
                <h2 class="text-lg font-bold text-nimr-neutral-900">⚙️ Your Birthday Settings</h2>
                <p class="text-sm text-nimr-neutral-600 mt-1">Manage your birthday preferences</p>
                @if (!auth()->user()->birth_date)
                    <div class="mt-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <div class="text-2xl">🎂</div>
                            <div class="flex-1">
                                <h3 class="font-bold text-amber-800 mb-1">Don't miss out on birthday celebrations!</h3>
                                <p class="text-sm text-amber-700 mb-3">Add your birth date below so we can celebrate your
                                    special day with you and your colleagues.</p>
                                <div class="flex items-center gap-2 text-xs text-amber-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Your birthday will only be visible to people you choose</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="p-6">
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('birthdays.update-profile') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="max-w-md">
                        <!-- Birthday Settings -->
                        <div class="space-y-4">
                            <h3 class="font-bold text-nimr-neutral-900 flex items-center gap-2">
                                <span class="text-2xl">🎂</span>
                                Birthday Settings
                            </h3>

                            <div>
                                <label for="birth_date" class="block text-sm font-semibold text-nimr-neutral-700 mb-2">
                                    Birth Date
                                    @if (!auth()->user()->birth_date)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <input type="date" name="birth_date" id="birth_date"
                                    value="{{ auth()->user()->birth_date?->format('Y-m-d') }}"
                                    class="w-full border-nimr-neutral-300 rounded-xl shadow-sm focus:border-nimr-primary-500 focus:ring-nimr-primary-500 @error('birth_date') border-red-500 @enderror">
                                @error('birth_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-nimr-neutral-500">
                                    @if (!auth()->user()->birth_date)
                                        <span class="text-amber-600 font-medium">Add your birth date to join the
                                            celebrations!</span><br>
                                    @endif
                                    Only month and day are shown to others - your age is never displayed
                                </p>
                            </div>

                            <div>
                                <label for="birthday_visibility"
                                    class="block text-sm font-semibold text-nimr-neutral-700 mb-2">
                                    Who can see your birthday?
                                </label>
                                <select name="birthday_visibility" id="birthday_visibility"
                                    class="w-full border-nimr-neutral-300 rounded-xl shadow-sm focus:border-nimr-primary-500 focus:ring-nimr-primary-500 @error('birthday_visibility') border-red-500 @enderror">
                                    <option value="private"
                                        {{ (auth()->user()->birthday_visibility ?? 'private') === 'private' ? 'selected' : '' }}>
                                        🔒 Private (only you)
                                    </option>
                                    <option value="team"
                                        {{ auth()->user()->birthday_visibility === 'team' ? 'selected' : '' }}>
                                        👥 Team (same location)
                                    </option>
                                    <option value="public"
                                        {{ auth()->user()->birthday_visibility === 'public' ? 'selected' : '' }}>
                                        🌍 Everyone
                                    </option>
                                </select>
                                @error('birthday_visibility')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-nimr-neutral-200">
                            <button type="submit"
                                class="bg-gradient-to-r from-nimr-primary-600 to-purple-600 hover:from-nimr-primary-700 hover:to-purple-700 text-white font-bold px-8 py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                @if (!auth()->user()->birth_date)
                                    🎉 Add My Birthday
                                @else
                                    💾 Save Settings
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
