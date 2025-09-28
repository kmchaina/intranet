<x-guest-layout>
	<x-auth-session-status class="mb-4" :status="session('status')" />

	<form method="POST" action="{{ route('login') }}" class="space-y-4">
		@csrf

		<div>
			<x-input-label for="email" :value="__('Email')" />
			<x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
			<x-input-error :messages="$errors->get('email')" class="mt-2" />
		</div>

		<div>
			<x-input-label for="password" :value="__('Password')" />
			<div class="relative">
				<x-text-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="current-password" />
				<button type="button" tabindex="-1" aria-label="Show password" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600" onclick="const i=document.getElementById('password'); i.type = i.type==='password' ? 'text' : 'password'; this.setAttribute('aria-pressed', i.type==='text');">👁️</button>
			</div>
			<x-input-error :messages="$errors->get('password')" class="mt-2" />
		</div>

		<div class="flex items-center justify-between">
			<label for="remember_me" class="inline-flex items-center gap-2">
				<input id="remember_me" name="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
				<span class="text-sm text-gray-600">{{ __('Remember me') }}</span>
			</label>

			@if (Route::has('password.request'))
				<a class="underline text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
					{{ __('Forgot your password?') }}
				</a>
			@endif
		</div>

		<x-primary-button class="w-full justify-center">{{ __('Log in') }}</x-primary-button>
	</form>
</x-guest-layout>
