<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" placeholder="yourname@nimr.or.tz" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="text-sm text-gray-600 mt-1">Email must be from @nimr.or.tz domain</p>
        </div>

        <!-- Organizational Level -->
        <div class="mt-4">
            <x-input-label for="organizational_level" :value="__('Organizational Level')" />
            <select id="organizational_level" name="organizational_level"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required>
                <option value="">Select Level</option>
                <option value="headquarters" {{ old('organizational_level') == 'headquarters' ? 'selected' : '' }}>
                    Headquarters</option>
                <option value="centre" {{ old('organizational_level') == 'centre' ? 'selected' : '' }}>Centre</option>
            </select>
            <x-input-error :messages="$errors->get('organizational_level')" class="mt-2" />
        </div>

        <!-- Centre (only shown for centre level) -->
        <div class="mt-4" id="centre_section" style="display: none;">
            <x-input-label for="centre_id" :value="__('Centre')" />
            <select id="centre_id" name="centre_id"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Select Centre</option>
                @foreach ($centres as $centre)
                    <option value="{{ $centre->id }}" {{ old('centre_id') == $centre->id ? 'selected' : '' }}>
                        {{ $centre->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('centre_id')" class="mt-2" />
        </div>

        <!-- Work Location Clarification (shown when centre with stations is selected) -->
        <div class="mt-4" id="work_location_section" style="display: none;">
            <x-input-label for="work_location" :value="__('Where are you situated?')" />
            <select id="work_location" name="work_location"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Select your location</option>
                <option value="centre" {{ old('work_location') == 'centre' ? 'selected' : '' }}>
                    At the centre
                </option>
                <!-- Station options will be populated dynamically here -->
            </select>
            <x-input-error :messages="$errors->get('work_location')" class="mt-2" />
        </div>

        <!-- Hidden Station ID field (populated when station is selected from work_location) -->
        <!-- Hidden Station ID field (populated when station is selected from work_location) -->
        <input type="hidden" id="station_id" name="station_id" value="{{ old('station_id') }}">
        <x-input-error :messages="$errors->get('station_id')" class="mt-2" />

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const organizationalLevel = document.getElementById('organizational_level');
            const centreSection = document.getElementById('centre_section');
            const workLocationSection = document.getElementById('work_location_section');
            const stationSection = document.getElementById('station_section');

            const centreSelect = document.getElementById('centre_id');
            const workLocationSelect = document.getElementById('work_location');
            const stationIdField = document.getElementById('station_id');

            let centreStations = [];

            // Show/hide sections based on organizational level
            organizationalLevel.addEventListener('change', function() {
                const level = this.value;

                // Reset all sections
                centreSection.style.display = 'none';
                workLocationSection.style.display = 'none';

                // Reset dropdowns
                workLocationSelect.innerHTML =
                    '<option value="">Select your location</option><option value="centre">At the centre</option>';
                stationIdField.value = '';
                centreStations = [];

                // Enable appropriate sections based on level
                switch (level) {
                    case 'headquarters':
                        // No additional selections needed
                        break;
                    case 'centre':
                        centreSection.style.display = 'block';
                        break;
                }
            });

            // Populate stations when centre is selected and add them to work_location dropdown
            centreSelect.addEventListener('change', function() {
                const centreId = this.value;
                const orgLevel = organizationalLevel.value;

                // Reset sections
                workLocationSection.style.display = 'none';
                workLocationSelect.innerHTML =
                    '<option value="">Select your location</option><option value="centre">At the centre</option>';
                stationIdField.value = '';
                centreStations = [];

                if (centreId) {
                    fetch(`/get-stations?centre_id=${centreId}`)
                        .then(response => response.json())
                        .then(stations => {
                            centreStations = stations;

                            // Add each station as an option in work_location dropdown
                            stations.forEach(station => {
                                const option = document.createElement('option');
                                option.value = `station_${station.id}`;
                                option.textContent = `At ${station.name}`;
                                workLocationSelect.appendChild(option);
                            });

                            // Show work location question only if centre has stations
                            if (orgLevel === 'centre' && stations.length > 0) {
                                workLocationSection.style.display = 'block';
                            }
                        })
                        .catch(error => console.error('Error fetching stations:', error));
                }
            });

            // Handle work location selection
            workLocationSelect.addEventListener('change', function() {
                const workLocation = this.value;

                stationIdField.value = '';

                if (workLocation.startsWith('station_')) {
                    // Extract station ID from the value (station_123 -> 123)
                    const stationId = workLocation.replace('station_', '');
                    stationIdField.value = stationId;
                } else if (workLocation === 'centre') {
                    // User is situated at centre, no station ID needed
                    stationIdField.value = '';
                }
            });

            // Initialize form with old values if validation errors occurred
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
                                // If station_id exists but no work_location, reconstruct the work_location value
                                workLocationSelect.value = 'station_{{ old('station_id') }}';
                                workLocationSelect.dispatchEvent(new Event('change'));
                            @endif
                        }, 100);
                    @endif
                }, 100);
            @endif
        });
    </script>
</x-guest-layout>
