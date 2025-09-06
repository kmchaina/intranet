@extends('layouts.dashboard')

@section('title', 'Create New Poll')

@section('page-title', 'Create New Poll')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create New Poll</h1>
        <a href="{{ route('polls.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Polls
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('polls.store') }}" id="pollForm">
                @csrf

                <!-- Basic Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Basic Information</h3>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Poll Title *
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="Optional description of the poll">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Poll Type *
                            </label>
                            <select name="type" id="type"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required onchange="updatePollOptions()">
                                <option value="">Select a poll type</option>
                                <option value="single_choice" {{ old('type') === 'single_choice' ? 'selected' : '' }}>Single
                                    Choice
                                </option>
                                <option value="multiple_choice" {{ old('type') === 'multiple_choice' ? 'selected' : '' }}>
                                    Multiple Choice
                                </option>
                                <option value="rating" {{ old('type') === 'rating' ? 'selected' : '' }}>Rating
                                </option>
                                <option value="yes_no" {{ old('type') === 'yes_no' ? 'selected' : '' }}>Yes/No
                                </option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Poll Options -->
                <div class="mb-6" id="optionsSection">
                    <h3 class="text-lg font-semibold mb-4">Poll Options</h3>

                    <div id="choiceOptions"
                        style="{{ old('type') && in_array(old('type'), ['single_choice', 'multiple_choice']) ? 'display: block;' : 'display: none;' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Options * <span class="text-sm text-gray-500">(You need at least 2 options)</span>
                        </label>
                        <div id="optionsList">
                            <div class="flex items-center mb-2 option-input">
                                <input type="text" name="options[]"
                                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-2"
                                    placeholder="Option 1" value="{{ old('options.0') }}" required>
                                <button type="button" onclick="removeOption(this)"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded">×</button>
                            </div>
                            <div class="flex items-center mb-2 option-input">
                                <input type="text" name="options[]"
                                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-2"
                                    placeholder="Option 2" value="{{ old('options.1') }}" required>
                                <button type="button" onclick="removeOption(this)"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded">×</button>
                            </div>
                        </div>
                        <button type="button" onclick="addOption()"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-2">
                            Add Option
                        </button>
                        @error('options')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="ratingOptions" style="{{ old('type') === 'rating' ? 'display: block;' : 'display: none;' }}">
                        <label for="max_rating" class="block text-sm font-medium text-gray-700 mb-2">
                            Maximum Rating *
                        </label>
                        <select name="max_rating" id="max_rating"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="5" {{ old('max_rating') == '5' ? 'selected' : '' }}>1 to 5
                            </option>
                            <option value="10" {{ old('max_rating') == '10' ? 'selected' : '' }}>1 to 10
                            </option>
                        </select>
                        @error('max_rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Poll Settings</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="anonymous" value="1"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    {{ old('anonymous') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Anonymous voting</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Responses will not be linked to specific users
                            </p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="show_results" value="1" checked
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    {{ old('show_results', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Show results after voting</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Users can see results after they vote</p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="allow_comments" value="1"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    {{ old('allow_comments') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Allow comments</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Users can add comments with their votes</p>
                        </div>
                    </div>
                </div>

                <!-- Visibility -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Visibility</h3>

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" name="visibility" value="public" class="mr-2"
                                {{ old('visibility', 'public') === 'public' ? 'checked' : '' }}
                                onchange="updateVisibilityOptions()">
                            <span class="text-sm text-gray-700">Everyone can see and vote</span>
                        </label>

                        <label class="flex items-center">
                            <input type="radio" name="visibility" value="department" class="mr-2"
                                {{ old('visibility') === 'department' ? 'checked' : '' }}
                                onchange="updateVisibilityOptions()">
                            <span class="text-sm text-gray-700">Specific departments only</span>
                        </label>

                        <label class="flex items-center">
                            <input type="radio" name="visibility" value="custom" class="mr-2"
                                {{ old('visibility') === 'custom' ? 'checked' : '' }}
                                onchange="updateVisibilityOptions()">
                            <span class="text-sm text-gray-700">Custom users only</span>
                        </label>
                    </div>

                    <div id="departmentSelect" style="display: none;" class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Departments
                        </label>
                        <select name="visible_departments[]" multiple
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ in_array($department->id, old('visible_departments', [])) ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="userSelect" style="display: none;" class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Users
                        </label>
                        <select name="visible_users[]" multiple
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ in_array($user->id, old('visible_users', [])) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                    ({{ $user->department ? $user->department->name : 'No Department' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Schedule -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Schedule (Optional)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date & Time
                            </label>
                            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('starts_at')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-2">
                                End Date & Time
                            </label>
                            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('ends_at')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Initial Status</h3>

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="draft" class="mr-2"
                                {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Save as draft</span>
                        </label>

                        <label class="flex items-center">
                            <input type="radio" name="status" value="active" class="mr-2"
                                {{ old('status') === 'active' ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Activate immediately</span>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                        Create Poll
                    </button>
                    <a href="{{ route('polls.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>

    <script>
        function updatePollOptions() {
            const typeSelect = document.getElementById('type');
            const selectedType = typeSelect.value;

            const optionsSection = document.getElementById('optionsSection');
            const choiceOptions = document.getElementById('choiceOptions');
            const ratingOptions = document.getElementById('ratingOptions');

            if (selectedType === 'single_choice' || selectedType === 'multiple_choice') {
                optionsSection.style.display = 'block';
                choiceOptions.style.display = 'block';
                ratingOptions.style.display = 'none';

                // Make option inputs required
                const optionInputs = choiceOptions.querySelectorAll('input[name="options[]"]');
                optionInputs.forEach(input => input.required = true);
            } else if (selectedType === 'rating') {
                optionsSection.style.display = 'block';
                choiceOptions.style.display = 'none';
                ratingOptions.style.display = 'block';

                // Remove required from option inputs
                const optionInputs = choiceOptions.querySelectorAll('input[name="options[]"]');
                optionInputs.forEach(input => input.required = false);
            } else {
                optionsSection.style.display = 'none';
                choiceOptions.style.display = 'none';
                ratingOptions.style.display = 'none';

                // Remove required from option inputs
                const optionInputs = choiceOptions.querySelectorAll('input[name="options[]"]');
                optionInputs.forEach(input => input.required = false);
            }
        }

        function addOption() {
            const optionsList = document.getElementById('optionsList');
            const optionCount = optionsList.children.length + 1;

            const newOption = document.createElement('div');
            newOption.className = 'flex items-center mb-2 option-input';
            newOption.innerHTML = `
                <input type="text" name="options[]" 
                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-2"
                       placeholder="Option ${optionCount}">
                <button type="button" onclick="removeOption(this)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded">×</button>
            `;

            optionsList.appendChild(newOption);
        }

        function removeOption(button) {
            const optionsList = document.getElementById('optionsList');
            if (optionsList.children.length > 2) {
                button.parentElement.remove();
            }
        }

        function updateVisibilityOptions() {
            const visibility = document.querySelector('input[name="visibility"]:checked').value;
            const departmentSelect = document.getElementById('departmentSelect');
            const userSelect = document.getElementById('userSelect');

            departmentSelect.style.display = visibility === 'department' ? 'block' : 'none';
            userSelect.style.display = visibility === 'custom' ? 'block' : 'none';
        }

        // Initialize form state
        document.addEventListener('DOMContentLoaded', function() {
            updatePollOptions();
            updateVisibilityOptions();
        });
    </script>
@endsection

@push('scripts')
    <script>
        function updateVisibilityOptions() {
            const visibility = document.querySelector('input[name="visibility"]:checked').value;
            const departmentSelect = document.getElementById('departmentSelect');
            const userSelect = document.getElementById('userSelect');

            departmentSelect.style.display = visibility === 'department' ? 'block' : 'none';
            userSelect.style.display = visibility === 'custom' ? 'block' : 'none';
        }

        // Initialize form state
        document.addEventListener('DOMContentLoaded', function() {
            updatePollOptions();
            updateVisibilityOptions();
        });
    </script>
@endpush
