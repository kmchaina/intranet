<x-guest-layout>
	<div class="mb-6 text-center">
		<h1 class="text-3xl font-semibold tracking-tight text-white drop-shadow">{{ __('Create your account') }}</h1>
		<p class="mt-2 text-sm text-white/90">{{ __('Access announcements, documents and collaboration tools.') }}</p>
	</div>

	<form method="POST" action="{{ route('register') }}" class="space-y-6">
		@csrf
		<!-- Name -->
		<div>
			<x-input-label for="name" :value="__('Full Name')" class="text-white" />
			<x-text-input id="name" class="glass-input mt-1 text-white" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
			<x-input-error :messages="$errors->get('name')" class="mt-2" />
		</div>

		<!-- Email Address -->
		<div>
			<x-input-label for="email" :value="__('Work Email')" class="text-white" />
			<x-text-input id="email" class="glass-input mt-1 text-white" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="your.name@nimr.or.tz" />
			<x-input-error :messages="$errors->get('email')" class="mt-2" />
			<p class="text-xs text-white/80 mt-1">{{ __('Use your official @nimr.or.tz email address.') }}</p>
		</div>

		<!-- Organizational Level -->
		<div>
			<x-input-label for="organizational_level" :value="__('Organizational Level')" class="text-white" />
			<select id="organizational_level" name="organizational_level" class="glass-input mt-1 text-sm text-white" required>
				<option value="">{{ __('Select Level') }}</option>
				<option value="headquarters" {{ old('organizational_level') == 'headquarters' ? 'selected' : '' }}>{{ __('Headquarters') }}</option>
				<option value="centre" {{ old('organizational_level') == 'centre' ? 'selected' : '' }}>{{ __('Centre') }}</option>
			</select>
			<x-input-error :messages="$errors->get('organizational_level')" class="mt-2" />
		</div>

		<!-- Headquarters Department (only for HQ level) -->
		<div id="hq_department_section" class="hidden">
			<x-input-label for="department_id" :value="__('Headquarters Department')" class="text-white" />
			<select id="department_id" name="department_id" class="glass-input mt-1 text-sm text-white">
				<option value="">{{ __('Select Department') }}</option>
				@isset($hqDepartments)
					@foreach ($hqDepartments as $dept)
						<option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
					@endforeach
				@endisset
			</select>
			<x-input-error :messages="$errors->get('department_id')" class="mt-2" />
		</div>

		<!-- Centre (only for Centre level) -->
		<div id="centre_section" class="hidden">
			<x-input-label for="centre_id" :value="__('Centre')" class="text-white" />
			<select id="centre_id" name="centre_id" class="glass-input mt-1 text-sm text-white">
				<option value="">{{ __('Select Centre') }}</option>
				@foreach ($centres as $centre)
					<option value="{{ $centre->id }}" {{ old('centre_id') == $centre->id ? 'selected' : '' }}>{{ $centre->name }}</option>
				@endforeach
			</select>
			<x-input-error :messages="$errors->get('centre_id')" class="mt-2" />
		</div>

		<!-- Work Location (conditional) -->
		<div id="work_location_section" class="hidden">
			<x-input-label for="work_location" :value="__('Where are you situated?')" class="text-white" />
			<select id="work_location" name="work_location" class="glass-input mt-1 text-sm text-white">
				<option value="">{{ __('Select your location') }}</option>
				<option value="centre" {{ old('work_location') == 'centre' ? 'selected' : '' }}>{{ __('At the centre') }}</option>
			</select>
			<x-input-error :messages="$errors->get('work_location')" class="mt-2" />
		</div>

		<!-- Hidden Station ID -->
		<input type="hidden" id="station_id" name="station_id" value="{{ old('station_id') }}">
		<x-input-error :messages="$errors->get('station_id')" class="mt-2" />

		<!-- Password -->
		<div class="grid gap-4 sm:grid-cols-2">
			<div class="sm:col-span-1">
				<x-input-label for="password" :value="__('Password')" class="text-white" />
				<x-text-input id="password" class="glass-input mt-1 text-white" type="password" name="password" required autocomplete="new-password" />
				<x-input-error :messages="$errors->get('password')" class="mt-2" />
			</div>
			<div class="sm:col-span-1">
				<x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white" />
				<x-text-input id="password_confirmation" class="glass-input mt-1 text-white" type="password" name="password_confirmation" required autocomplete="new-password" />
				<x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
			</div>
		</div>
		<p class="text-xs text-white/80">{{ __('Use at least 8 characters with a mix of letters & numbers.') }}</p>

		<div class="flex items-center justify-between pt-2">
			<a href="{{ route('login') }}" class="text-sm text-indigo-200 hover:text-white font-medium transition">{{ __('Already registered?') }}</a>
			<x-primary-button class="inline-flex items-center gap-2">
				<span>{{ __('Register') }}</span>
			</x-primary-button>
		</div>
	</form>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const organizationalLevel = document.getElementById('organizational_level');
			const centreSection = document.getElementById('centre_section');
			const workLocationSection = document.getElementById('work_location_section');
				const hqDepartmentSection = document.getElementById('hq_department_section');
			const centreSelect = document.getElementById('centre_id');
			const workLocationSelect = document.getElementById('work_location');
			const stationIdField = document.getElementById('station_id');
				const departmentSelect = document.getElementById('department_id');
			let centreStations = [];

			organizationalLevel.addEventListener('change', function() {
				const level = this.value;
				centreSection.classList.add('hidden');
				workLocationSection.classList.add('hidden');
					hqDepartmentSection.classList.add('hidden');
				workLocationSelect.innerHTML = '<option value="">{{ __('Select your location') }}</option><option value="centre">{{ __('At the centre') }}</option>';
				stationIdField.value = '';
				centreStations = [];
					if (departmentSelect) departmentSelect.value = '';
				if (level === 'centre') {
					centreSection.classList.remove('hidden');
					} else if (level === 'headquarters') {
						hqDepartmentSection.classList.remove('hidden');
				}
			});

			centreSelect.addEventListener('change', function() {
				const centreId = this.value;
				const orgLevel = organizationalLevel.value;
				workLocationSection.classList.add('hidden');
				workLocationSelect.innerHTML = '<option value="">{{ __('Select your location') }}</option><option value="centre">{{ __('At the centre') }}</option>';
				stationIdField.value = '';
				centreStations = [];
				if (centreId) {
					fetch(`/get-stations?centre_id=${centreId}`)
						.then(r => r.json())
						.then(stations => {
							centreStations = stations;
							stations.forEach(station => {
								const option = document.createElement('option');
								option.value = `station_${station.id}`;
								option.textContent = '{{ __('At') }} ' + station.name;
								workLocationSelect.appendChild(option);
							});
							if (orgLevel === 'centre' && stations.length > 0) {
								workLocationSection.classList.remove('hidden');
							}
						})
						.catch(err => console.error('Error fetching stations:', err));
				}
			});

			workLocationSelect.addEventListener('change', function() {
				const workLocation = this.value;
				stationIdField.value = '';
				if (workLocation.startsWith('station_')) {
					stationIdField.value = workLocation.replace('station_', '');
				}
			});

			// Rehydrate old values after validation errors
			@if (old('organizational_level'))
				organizationalLevel.value = '{{ old('organizational_level') }}';
				organizationalLevel.dispatchEvent(new Event('change'));
				setTimeout(() => {
					@if (old('centre_id'))
						centreSelect.value = '{{ old('centre_id') }}';
						centreSelect.dispatchEvent(new Event('change'));
						setTimeout(() => {
							@if (old('work_location'))
								workLocationSelect.value = '{{ old('work_location') }}';
								workLocationSelect.dispatchEvent(new Event('change'));
							@elseif (old('station_id'))
								workLocationSelect.value = 'station_{{ old('station_id') }}';
								workLocationSelect.dispatchEvent(new Event('change'));
							@endif
						}, 120);
					@endif
						@if (old('organizational_level') === 'headquarters' && old('department_id'))
							departmentSelect.value = '{{ old('department_id') }}';
						@endif
				}, 120);
			@endif
		});
	</script>
</x-guest-layout>
