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
                <option value="station" {{ old('organizational_level') == 'station' ? 'selected' : '' }}>Station
                </option>
                <option value="department" {{ old('organizational_level') == 'department' ? 'selected' : '' }}>
                    Department</option>
            </select>
            <x-input-error :messages="$errors->get('organizational_level')" class="mt-2" />
        </div>

        <!-- Headquarters -->
        <div class="mt-4" id="headquarters_section" style="display: none;">
            <x-input-label for="headquarters_id" :value="__('Headquarters')" />
            <select id="headquarters_id" name="headquarters_id"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Select Headquarters</option>
                @foreach ($headquarters as $hq)
                    <option value="{{ $hq->id }}" {{ old('headquarters_id') == $hq->id ? 'selected' : '' }}>
                        {{ $hq->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('headquarters_id')" class="mt-2" />
        </div>

        <!-- Centre -->
        <div class="mt-4" id="centre_section" style="display: none;">
            <x-input-label for="centre_id" :value="__('Centre')" />
            <select id="centre_id" name="centre_id"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                disabled>
                <option value="">Select Centre</option>
                @foreach ($centres as $centre)
                    <option value="{{ $centre->id }}" {{ old('centre_id') == $centre->id ? 'selected' : '' }}>
                        {{ $centre->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('centre_id')" class="mt-2" />
        </div>

        <!-- Station -->
        <div class="mt-4" id="station_section" style="display: none;">
            <x-input-label for="station_id" :value="__('Station')" />
            <select id="station_id" name="station_id"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                disabled>
                <option value="">Select Station</option>
            </select>
            <x-input-error :messages="$errors->get('station_id')" class="mt-2" />
        </div>

        <!-- Department -->
        <div class="mt-4" id="department_section" style="display: none;">
            <x-input-label for="department_id" :value="__('Department')" />
            <select id="department_id" name="department_id"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                disabled>
                <option value="">Select Department</option>
            </select>
            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
        </div>

        <!-- Employee ID -->
        <div class="mt-4">
            <x-input-label for="employee_id" :value="__('Employee ID (Optional)')" />
            <x-text-input id="employee_id" class="block mt-1 w-full" type="text" name="employee_id"
                :value="old('employee_id')" />
            <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
        </div>

        <!-- Job Title -->
        <div class="mt-4">
            <x-input-label for="job_title" :value="__('Job Title (Optional)')" />
            <x-text-input id="job_title" class="block mt-1 w-full" type="text" name="job_title" :value="old('job_title')" />
            <x-input-error :messages="$errors->get('job_title')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone (Optional)')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

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
            const headquartersSection = document.getElementById('headquarters_section');
            const centreSection = document.getElementById('centre_section');
            const stationSection = document.getElementById('station_section');
            const departmentSection = document.getElementById('department_section');

            const headquartersSelect = document.getElementById('headquarters_id');
            const centreSelect = document.getElementById('centre_id');
            const stationSelect = document.getElementById('station_id');
            const departmentSelect = document.getElementById('department_id');

            // Show/hide sections based on organizational level
            organizationalLevel.addEventListener('change', function() {
                const level = this.value;

                // Reset all sections
                headquartersSection.style.display = 'none';
                centreSection.style.display = 'none';
                stationSection.style.display = 'none';
                departmentSection.style.display = 'none';

                // Reset all dropdowns
                centreSelect.innerHTML = '<option value="">Select Centre</option>';
                stationSelect.innerHTML = '<option value="">Select Station</option>';
                departmentSelect.innerHTML = '<option value="">Select Department</option>';
                centreSelect.disabled = true;
                stationSelect.disabled = true;
                departmentSelect.disabled = true;

                // Enable appropriate sections based on level
                switch (level) {
                    case 'headquarters':
                        headquartersSection.style.display = 'block';
                        break;
                    case 'centre':
                        headquartersSection.style.display = 'block';
                        centreSection.style.display = 'block';
                        break;
                    case 'station':
                        headquartersSection.style.display = 'block';
                        centreSection.style.display = 'block';
                        stationSection.style.display = 'block';
                        break;
                    case 'department':
                        headquartersSection.style.display = 'block';
                        centreSection.style.display = 'block';
                        stationSection.style.display = 'block';
                        departmentSection.style.display = 'block';
                        break;
                }
            });

            // Populate centres when headquarters is selected
            headquartersSelect.addEventListener('change', function() {
                const headquartersId = this.value;

                centreSelect.innerHTML = '<option value="">Select Centre</option>';
                stationSelect.innerHTML = '<option value="">Select Station</option>';
                departmentSelect.innerHTML = '<option value="">Select Department</option>';
                centreSelect.disabled = !headquartersId;

                if (headquartersId) {
                    fetch(`/get-centres?headquarters_id=${headquartersId}`)
                        .then(response => response.json())
                        .then(centres => {
                            centres.forEach(centre => {
                                const option = document.createElement('option');
                                option.value = centre.id;
                                option.textContent = centre.name;
                                centreSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching centres:', error));
                }
            });

            // Populate stations when centre is selected
            centreSelect.addEventListener('change', function() {
                const centreId = this.value;

                stationSelect.innerHTML = '<option value="">Select Station</option>';
                departmentSelect.innerHTML = '<option value="">Select Department</option>';
                stationSelect.disabled = !centreId;

                if (centreId) {
                    fetch(`/get-stations?centre_id=${centreId}`)
                        .then(response => response.json())
                        .then(stations => {
                            stations.forEach(station => {
                                const option = document.createElement('option');
                                option.value = station.id;
                                option.textContent = station.name;
                                stationSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching stations:', error));

                    // Also fetch centre-level departments
                    const level = organizationalLevel.value;
                    if (level === 'department') {
                        loadDepartments('centre', centreId);
                    }
                }
            });

            // Populate departments when station is selected
            stationSelect.addEventListener('change', function() {
                const stationId = this.value;
                departmentSelect.innerHTML = '<option value="">Select Department</option>';

                const level = organizationalLevel.value;
                if (level === 'department' && stationId) {
                    loadDepartments('station', stationId);
                }
            });

            // Function to load departments based on level and parent ID
            function loadDepartments(level, parentId) {
                departmentSelect.disabled = !parentId;

                if (parentId) {
                    fetch(`/get-departments?level=${level}&parent_id=${parentId}`)
                        .then(response => response.json())
                        .then(departments => {
                            departments.forEach(department => {
                                const option = document.createElement('option');
                                option.value = department.id;
                                option.textContent = department.name;
                                departmentSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching departments:', error));
                }
            }

            // Load departments for headquarters level
            headquartersSelect.addEventListener('change', function() {
                const level = organizationalLevel.value;
                if (level === 'department' && this.value) {
                    loadDepartments('headquarters', this.value);
                }
            });

            // Initialize form with old values if validation errors occurred
            @if (old('organizational_level'))
                organizationalLevel.value = '{{ old('organizational_level') }}';
                organizationalLevel.dispatchEvent(new Event('change'));

                setTimeout(() => {
                    @if (old('headquarters_id'))
                        headquartersSelect.value = '{{ old('headquarters_id') }}';
                        headquartersSelect.dispatchEvent(new Event('change'));

                        setTimeout(() => {
                            @if (old('centre_id'))
                                centreSelect.value = '{{ old('centre_id') }}';
                                centreSelect.dispatchEvent(new Event('change'));

                                setTimeout(() => {
                                    @if (old('station_id'))
                                        stationSelect.value = '{{ old('station_id') }}';
                                        stationSelect.dispatchEvent(new Event('change'));

                                        setTimeout(() => {
                                            @if (old('department_id'))
                                                departmentSelect.value =
                                                    '{{ old('department_id') }}';
                                            @endif
                                        }, 100);
                                    @endif
                                }, 100);
                            @endif
                        }, 100);
                    @endif
                }, 100);
            @endif
        });
    </script>
</x-guest-layout>
