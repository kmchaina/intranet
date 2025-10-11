@props([
    'inputId' => 'password',
    'inputName' => 'password',
    'confirmInputId' => 'password_confirmation',
    'required' => false,
    'label' => 'Password',
])

<div x-data="passwordStrength('{{ $inputId }}', '{{ $confirmInputId }}')" x-init="init()">
    <!-- Password Input -->
    <div class="mb-2.5">
        <label for="{{ $inputId }}" class="block text-sm font-semibold text-gray-700 mb-1.5">
            {{ $label }} @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
        <div class="relative">
            <input :type="showPassword ? 'text' : 'password'" id="{{ $inputId }}" name="{{ $inputName }}"
                {{ $required ? 'required' : '' }} x-model="password" @input="checkStrength"
                class="block w-full h-11 pr-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('{{ $inputName }}') border-red-300 @enderror"
                autocomplete="new-password">
            <button type="button" @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>
        @error($inputName)
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password Requirements Indicator - Compact Version -->
    <div x-show="password.length > 0" class="mb-2.5 p-2.5 bg-gray-50 border border-gray-200 rounded-md">
        <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
            <!-- Minimum Length -->
            <div class="flex items-center">
                <svg :class="requirements.length ? 'text-green-500' : 'text-red-500'" class="h-4 w-4 mr-1.5"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path x-show="requirements.length" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                    <path x-show="!requirements.length" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span :class="requirements.length ? 'text-green-700' : 'text-red-700'">8+ characters</span>
            </div>

            <!-- Uppercase -->
            <div class="flex items-center">
                <svg :class="requirements.uppercase ? 'text-green-500' : 'text-red-500'" class="h-4 w-4 mr-1.5"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path x-show="requirements.uppercase" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                    <path x-show="!requirements.uppercase" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span :class="requirements.uppercase ? 'text-green-700' : 'text-red-700'">Uppercase (A-Z)</span>
            </div>

            <!-- Lowercase -->
            <div class="flex items-center">
                <svg :class="requirements.lowercase ? 'text-green-500' : 'text-red-500'" class="h-4 w-4 mr-1.5"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path x-show="requirements.lowercase" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                    <path x-show="!requirements.lowercase" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span :class="requirements.lowercase ? 'text-green-700' : 'text-red-700'">Lowercase (a-z)</span>
            </div>

            <!-- Number -->
            <div class="flex items-center">
                <svg :class="requirements.number ? 'text-green-500' : 'text-red-500'" class="h-4 w-4 mr-1.5"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path x-show="requirements.number" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                    <path x-show="!requirements.number" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span :class="requirements.number ? 'text-green-700' : 'text-red-700'">Number (0-9)</span>
            </div>

            <!-- Special Character -->
            <div class="flex items-center">
                <svg :class="requirements.special ? 'text-green-500' : 'text-red-500'" class="h-4 w-4 mr-1.5"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path x-show="requirements.special" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                    <path x-show="!requirements.special" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span :class="requirements.special ? 'text-green-700' : 'text-red-700'">Special (!@#$)</span>
            </div>
        </div>

        <!-- Strength Meter - Compact -->
        <div class="mt-2 pt-2 border-t border-gray-200">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-medium text-gray-700">Strength:</span>
                <span class="text-xs font-semibold"
                    :class="{
                        'text-red-600': strength === 'weak',
                        'text-yellow-600': strength === 'fair',
                        'text-blue-600': strength === 'good',
                        'text-green-600': strength === 'strong'
                    }"
                    x-text="strength.toUpperCase()"></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5">
                <div class="h-1.5 rounded-full transition-all duration-300"
                    :class="{
                        'bg-red-500 w-1/4': strength === 'weak',
                        'bg-yellow-500 w-2/4': strength === 'fair',
                        'bg-blue-500 w-3/4': strength === 'good',
                        'bg-green-500 w-full': strength === 'strong'
                    }">
                </div>
            </div>
        </div>
    </div>

    <!-- Password Confirmation -->
    <div class="mb-0">
        <label for="{{ $confirmInputId }}" class="block text-sm font-semibold text-gray-700 mb-1.5">
            Confirm Password @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
        <div class="relative">
            <input :type="showConfirmPassword ? 'text' : 'password'" id="{{ $confirmInputId }}"
                name="password_confirmation" {{ $required ? 'required' : '' }} x-model="confirmPassword"
                @input="checkMatch"
                class="block w-full h-11 pr-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('password_confirmation') border-red-300 @enderror"
                autocomplete="new-password">
            <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>
        @error('password_confirmation')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
        <p x-show="confirmPassword.length > 0 && !passwordsMatch" class="mt-1 text-xs text-red-600">
            Passwords do not match
        </p>
        <p x-show="confirmPassword.length > 0 && passwordsMatch" class="mt-1 text-xs text-green-600">
            ✓ Passwords match
        </p>
    </div>
</div>

<script>
    function passwordStrength(inputId, confirmInputId) {
        return {
            password: '',
            confirmPassword: '',
            showPassword: false,
            showConfirmPassword: false,
            passwordsMatch: false,
            requirements: {
                length: false,
                uppercase: false,
                lowercase: false,
                number: false,
                special: false
            },
            strength: 'weak',

            init() {
                // If editing a form with existing password, don't show the indicator initially
            },

            checkStrength() {
                const pwd = this.password;

                // Check each requirement
                this.requirements.length = pwd.length >= 8;
                this.requirements.uppercase = /[A-Z]/.test(pwd);
                this.requirements.lowercase = /[a-z]/.test(pwd);
                this.requirements.number = /[0-9]/.test(pwd);
                this.requirements.special = /[!@#$%^&*(),.?":{}|<>]/.test(pwd);

                // Calculate strength
                const metRequirements = Object.values(this.requirements).filter(Boolean).length;

                if (metRequirements <= 2) {
                    this.strength = 'weak';
                } else if (metRequirements === 3) {
                    this.strength = 'fair';
                } else if (metRequirements === 4) {
                    this.strength = 'good';
                } else if (metRequirements === 5) {
                    this.strength = 'strong';
                }

                // Check if passwords match
                this.checkMatch();
            },

            checkMatch() {
                this.passwordsMatch = this.password === this.confirmPassword && this.password.length > 0;
            }
        };
    }
</script>
