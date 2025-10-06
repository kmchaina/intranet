@section('title', 'Register')

<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-nimr-neutral-900">Create Your Account</h2>
        <p class="text-sm text-nimr-neutral-600 mt-1">Join the NIMR Intranet community</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                autocomplete="name" class="input" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Work Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                autocomplete="username" class="input" placeholder="your.name@nimr.or.tz" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="text-xs text-nimr-neutral-500 mt-1">Use your official @nimr.or.tz email address</p>
        </div>

        {{-- Organizational Level --}}
        <div>
            <label for="organizational_level"
                class="block text-sm font-medium text-nimr-neutral-700 mb-2">Organizational Level</label>
            <select id="organizational_level" name="organizational_level" required class="select">
                <option value="">Select Your Level</option>
                <option value="headquarters" {{ old('organizational_level') == 'headquarters' ? 'selected' : '' }}>
                    Headquarters</option>
                <option value="centre" {{ old('organizational_level') == 'centre' ? 'selected' : '' }}>Centre</option>
            </select>
            <x-input-error :messages="$errors->get('organizational_level')" class="mt-2" />
        </div>

        {{-- HQ Department --}}
        <div id="hq_department_section" class="hidden">
            <label for="department_id" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Headquarters
                Department</label>
            <select id="department_id" name="department_id" class="select">
                <option value="">Select Department</option>
                @isset($hqDepartments)
                    @foreach ($hqDepartments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}</option>
                    @endforeach
                @endisset
            </select>
            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
        </div>

        {{-- Centre --}}
        <div id="centre_section" class="hidden">
            <label for="centre_id" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Centre</label>
            <select id="centre_id" name="centre_id" class="select">
                <option value="">Select Centre</option>
                @foreach ($centres as $centre)
                    <option value="{{ $centre->id }}" {{ old('centre_id') == $centre->id ? 'selected' : '' }}>
                        {{ $centre->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('centre_id')" class="mt-2" />
        </div>

        {{-- Work Location --}}
        <div id="work_location_section" class="hidden">
            <label for="work_location" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Where are you
                situated?</label>
            <select id="work_location" name="work_location" class="select">
                <option value="">Select Your Location</option>
                <option value="centre" {{ old('work_location') == 'centre' ? 'selected' : '' }}>At the Centre</option>
            </select>
            <x-input-error :messages="$errors->get('work_location')" class="mt-2" />
        </div>

        <input type="hidden" id="station_id" name="station_id" value="{{ old('station_id') }}">
        <x-input-error :messages="$errors->get('station_id')" class="mt-2" />

        {{-- Password --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="input" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-nimr-neutral-700 mb-2">Confirm
                    Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password" class="input" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>
        <p class="text-xs text-nimr-neutral-500">Use at least 8 characters with a mix of letters and numbers</p>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-primary w-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Create Account
        </button>

        {{-- Login Link --}}
        <div class="text-center pt-4 border-t border-nimr-neutral-200">
            <p class="text-sm text-nimr-neutral-600">
                Already have an account?
                <a href="{{ route('login') }}"
                    class="font-medium text-nimr-primary-600 hover:text-nimr-primary-700 hover:underline transition-colors">
                    Sign in here
                </a>
            </p>
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
                workLocationSelect.innerHTML =
                    '<option value="">Select Your Location</option><option value="centre">At the Centre</option>';
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
                workLocationSelect.innerHTML =
                    '<option value="">Select Your Location</option><option value="centre">At the Centre</option>';
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
                                option.textContent = `At ${station.name}`;
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

            // Rehydrate old values
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
                            @elseif (old('station_id'))
                                workLocationSelect.value = 'station_{{ old('station_id') }}';
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
