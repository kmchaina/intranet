@section('title', 'Reset Password')

<x-guest-layout>
    <div class="mb-6 text-center">
        <div
            class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-nimr-neutral-900">Reset Your Password</h2>
        <p class="text-sm text-nimr-neutral-600 mt-2">Enter your new password below</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                autofocus autocomplete="username" class="input" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-nimr-neutral-700 mb-2">New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="input"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Confirm
                Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" class="input" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-primary w-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Reset Password
        </button>
    </form>
</x-guest-layout>
