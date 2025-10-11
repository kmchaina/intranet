@section('title', 'Register')

<x-guest-layout>
    <div x-data="registrationWizard()" x-init="init()">
        {{-- Header --}}
        <div class="mb-3 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Create Your Account</h2>
            <p class="text-sm text-gray-600 mt-0.5">Join the NIMR Intranet community</p>
        </div>

        {{-- Progress Steps --}}
        <div class="mb-5">
            <div class="flex items-center justify-center">
                <template x-for="(step, index) in steps" :key="index">
                    <div class="flex items-center">
                        {{-- Step Circle --}}
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border-2 transition-all duration-300"
                                :class="{
                                    'bg-blue-600 text-white': index < currentStep,
                                    'border-blue-600 text-blue-600': index === currentStep,
                                    'border-gray-300 text-gray-400': index > currentStep
                                }"
                                style="border-color: #2563eb;">
                                <svg x-show="index < currentStep" class="w-4 h-4" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span x-show="index >= currentStep" class="text-sm font-semibold"
                                    x-text="index + 1"></span>
                            </div>
                            <span class="text-xs mt-1 font-medium"
                                :class="index === currentStep ? 'text-blue-600' : 'text-gray-500'"
                                x-text="step.label"></span>
                        </div>
                        {{-- Connector Line --}}
                        <div x-show="index < steps.length - 1" class="w-10 h-0.5 mx-1.5 transition-all duration-300"
                            :class="index < currentStep ? 'bg-blue-600' : 'bg-gray-300'"
                            :style="index < currentStep ? 'background-color: #2563eb;' : ''"></div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('register') }}" @submit="handleSubmit">
            @csrf

            {{-- Hidden fields --}}
            <input type="hidden" name="organizational_level" x-model="formData.organizational_level">
            <input type="hidden" name="centre_id" x-model="formData.centre_id">
            <input type="hidden" name="station_id" x-model="formData.station_id">
            <input type="hidden" name="department_id" x-model="formData.department_id">
            <input type="hidden" name="work_location" x-model="formData.work_location">

            <div class="min-h-[340px]">
                {{-- STEP 1: Basic Information --}}
                <div x-show="currentStep === 0" x-transition class="space-y-3.5">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                        <input id="name" type="text" name="name" x-model="formData.name" required autofocus
                            autocomplete="name" class="input h-11" placeholder="John Doe" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Work
                            Email</label>
                        <input id="email" type="email" name="email" x-model="formData.email" required
                            autocomplete="username" class="input h-11" placeholder="your.name@nimr.or.tz" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        <p class="text-xs text-gray-500 mt-1">Use your official @nimr.or.tz email address</p>
                    </div>
                </div>

                {{-- STEP 2: Password --}}
                <div x-show="currentStep === 1" x-transition>
                    <x-password-strength-indicator inputId="password" inputName="password"
                        confirmInputId="password_confirmation" label="Password" :required="true" />
                </div>

                {{-- STEP 3: Organization Details --}}
                <div x-show="currentStep === 2" x-transition class="space-y-3.5">
                    <div>
                        <label for="organizational_level" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Organizational Level
                        </label>
                        <select id="organizational_level" x-model="formData.organizational_level"
                            @change="handleOrgLevelChange" required class="select h-11">
                            <option value="">Select Your Level</option>
                            <option value="headquarters">Headquarters</option>
                            <option value="centre">Centre</option>
                        </select>
                        <x-input-error :messages="$errors->get('organizational_level')" class="mt-1" />
                    </div>

                    {{-- HQ Department --}}
                    <div x-show="formData.organizational_level === 'headquarters'" x-transition>
                        <label for="department_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Headquarters Department
                        </label>
                        <select id="department_id" x-model="formData.department_id" class="select h-11">
                            <option value="">Select Department</option>
                            @isset($hqDepartments)
                                @foreach ($hqDepartments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                        <x-input-error :messages="$errors->get('department_id')" class="mt-1" />
                    </div>

                    {{-- Centre --}}
                    <div x-show="formData.organizational_level === 'centre'" x-transition>
                        <label for="centre_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Centre</label>
                        <select id="centre_id" x-model="formData.centre_id" @change="fetchStations" class="select h-11">
                            <option value="">Select Centre</option>
                            @foreach ($centres as $centre)
                                <option value="{{ $centre->id }}">{{ $centre->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('centre_id')" class="mt-1" />
                    </div>

                    {{-- Work Location --}}
                    <div x-show="formData.organizational_level === 'centre' && formData.centre_id && stations.length > 0"
                        x-transition>
                        <label for="work_location" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Where are you situated?
                        </label>
                        <select id="work_location" x-model="formData.work_location"
                            @change="handleWorkLocationChange" class="select h-11">
                            <option value="">Select Your Location</option>
                            <option value="centre">At the Centre</option>
                            <template x-for="station in stations" :key="station.id">
                                <option :value="'station_' + station.id" x-text="'At ' + station.name"></option>
                            </template>
                        </select>
                        <x-input-error :messages="$errors->get('work_location')" class="mt-1" />
                    </div>
                </div>
            </div>

            {{-- Navigation Buttons --}}
            <div class="mt-5 flex items-center justify-between gap-4">
                <button type="button" @click="prevStep" x-show="currentStep > 0"
                    class="px-5 py-2 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all">
                    <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </button>

                <button type="button" @click="nextStep" x-show="currentStep < steps.length - 1"
                    class="ml-auto btn btn-primary px-7 py-2 font-semibold">
                    Next
                    <svg class="w-4 h-4 inline ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <button type="submit" x-show="currentStep === steps.length - 1"
                    class="ml-auto btn btn-primary px-7 py-2 font-semibold">
                    <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Create Account
                </button>
            </div>
        </form>

        {{-- Login Link --}}
        <div class="text-center pt-4 mt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" style="color: #2563eb;"
                    class="font-semibold hover:underline transition-colors" onmouseover="this.style.color='#1e40af'"
                    onmouseout="this.style.color='#2563eb'">
                    Sign in here
                </a>
            </p>
        </div>
    </div>

    <script>
        function registrationWizard() {
            return {
                currentStep: 0,
                steps: [{
                        label: 'Basic Info'
                    },
                    {
                        label: 'Password'
                    },
                    {
                        label: 'Organization'
                    }
                ],
                formData: {
                    name: '{{ old('name') }}',
                    email: '{{ old('email') }}',
                    organizational_level: '{{ old('organizational_level') }}',
                    centre_id: '{{ old('centre_id') }}',
                    station_id: '{{ old('station_id') }}',
                    department_id: '{{ old('department_id') }}',
                    work_location: '{{ old('work_location') }}'
                },
                stations: [],

                init() {
                    @if ($errors->any())
                        @if ($errors->has('name') || $errors->has('email'))
                            this.currentStep = 0;
                        @elseif ($errors->has('password') || $errors->has('password_confirmation'))
                            this.currentStep = 1;
                        @else
                            this.currentStep = 2;
                        @endif
                    @endif

                    if (this.formData.centre_id) {
                        this.fetchStations();
                    }
                },

                nextStep() {
                    if (this.validateCurrentStep()) {
                        this.currentStep++;
                    }
                },

                prevStep() {
                    if (this.currentStep > 0) {
                        this.currentStep--;
                    }
                },

                validateCurrentStep() {
                    if (this.currentStep === 0) {
                        return this.formData.name && this.formData.email;
                    } else if (this.currentStep === 1) {
                        const password = document.getElementById('password').value;
                        const passwordConfirm = document.getElementById('password_confirmation').value;
                        return password && passwordConfirm && password === passwordConfirm && password.length >= 8;
                    }
                    return true;
                },

                handleOrgLevelChange() {
                    this.formData.centre_id = '';
                    this.formData.station_id = '';
                    this.formData.department_id = '';
                    this.formData.work_location = '';
                    this.stations = [];
                },

                async fetchStations() {
                    if (!this.formData.centre_id) return;

                    try {
                        const response = await fetch(`/get-stations?centre_id=${this.formData.centre_id}`);
                        this.stations = await response.json();
                    } catch (error) {
                        console.error('Error fetching stations:', error);
                    }
                },

                handleWorkLocationChange() {
                    if (this.formData.work_location.startsWith('station_')) {
                        this.formData.station_id = this.formData.work_location.replace('station_', '');
                    } else {
                        this.formData.station_id = '';
                    }
                },

                handleSubmit(event) {
                    if (!this.validateCurrentStep()) {
                        event.preventDefault();
                    }
                }
            };
        }
    </script>
</x-guest-layout>
