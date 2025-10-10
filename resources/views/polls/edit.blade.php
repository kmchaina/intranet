@extends('layouts.dashboard')

@section('title', 'Edit Poll')

@section('page-title', 'Edit Poll: ' . $poll->title)

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Enhanced Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg mb-8 overflow-hidden">
            <div class="px-8 py-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Edit Poll</h1>
                        <p class="text-blue-100">Update your poll settings and configuration</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('polls.show', $poll) }}"
                            class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            View Poll
                        </a>
                        <a href="{{ route('polls.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Polls
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if ($poll->responses()->exists())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-8 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Limited Editing</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>This poll has received responses, so some options (type, choices, rating scale) cannot be
                                changed to preserve data integrity.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('polls.update', $poll) }}" class="space-y-8">
            @csrf
            @method('PATCH')

            <!-- Step 1: Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                1</div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                            <p class="text-sm text-gray-600">Update your poll title and description</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Poll Question or Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $poll->title) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="What would you like to ask?" required>
                        <p class="mt-1 text-sm text-gray-500">Make it clear and specific so people understand what you're
                            asking</p>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Additional Details <span class="text-gray-400">(Optional)</span>
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Add any extra context or instructions...">{{ old('description', $poll->description) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">This helps provide context and increases participation</p>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            @if (!$poll->responses()->exists())
                <!-- Step 2: Poll Configuration -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    2</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Poll Configuration</h3>
                                <p class="text-sm text-gray-600">Configure poll type and options</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Poll Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Poll Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                                @foreach (\App\Models\Poll::getTypes() as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('type', $poll->type) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Options for choice-based polls -->
                        <div id="options-section"
                            style="display: {{ in_array(old('type', $poll->type), ['single_choice', 'multiple_choice']) ? 'block' : 'none' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Poll Options <span class="text-red-500">*</span>
                            </label>
                            <div id="options-container" class="space-y-3">
                                @if ($poll->options)
                                    @foreach ($poll->options as $index => $option)
                                        <div
                                            class="option-input bg-gray-50 p-4 rounded-lg border-2 border-dashed border-gray-200">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <span
                                                        class="text-sm font-medium text-blue-600">{{ $index + 1 }}</span>
                                                </div>
                                                <input type="text" name="options[]"
                                                    value="{{ old('options.' . $index, $option) }}"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="Option {{ $index + 1 }}" required>
                                                <button type="button"
                                                    class="ml-3 p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors remove-option">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-option"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-green-50 hover:bg-green-100 text-green-700 text-sm font-medium rounded-lg border border-green-200 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Another Option
                            </button>
                            @error('options')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rating Scale -->
                        <div id="rating-section"
                            style="display: {{ old('type', $poll->type) === 'rating' ? 'block' : 'none' }}">
                            <label for="max_rating" class="block text-sm font-medium text-gray-700 mb-2">
                                Maximum Rating <span class="text-red-500">*</span>
                            </label>
                            <select name="max_rating" id="max_rating"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                @for ($i = 2; $i <= 10; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('max_rating', $poll->max_rating) == $i ? 'selected' : '' }}>
                                        1 to {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('max_rating')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Anonymous Voting -->
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <label class="flex items-start">
                                <input type="checkbox" name="anonymous" value="1"
                                    {{ old('anonymous', $poll->anonymous) ? 'checked' : '' }}
                                    class="mt-1 rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900">Anonymous Voting</div>
                                    <p class="text-sm text-gray-600">Responses won't be linked to specific users</p>
                                    <p class="text-xs text-gray-500 mt-1">💡 This encourages honest feedback</p>
                                </div>
                            </label>
                            @error('anonymous')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @else
                <!-- Show current settings for polls with responses -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    2</div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Current Poll Settings</h3>
                                <p class="text-sm text-gray-600">These settings are locked because the poll has responses
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-32">Type:</span>
                                    <span class="text-sm text-gray-900 font-semibold">{{ $poll->getTypeLabel() }}</span>
                                </div>
                                @if ($poll->options)
                                    <div class="flex items-start">
                                        <span class="text-sm font-medium text-gray-700 w-32">Options:</span>
                                        <span class="text-sm text-gray-900">{{ implode(', ', $poll->options) }}</span>
                                    </div>
                                @endif
                                @if ($poll->max_rating)
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-700 w-32">Rating Scale:</span>
                                        <span class="text-sm text-gray-900">1 to {{ $poll->max_rating }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-32">Anonymous:</span>
                                    <span class="text-sm text-gray-900">{{ $poll->anonymous ? 'Yes' : 'No' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step 3: Poll Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                3</div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Poll Settings</h3>
                            <p class="text-sm text-gray-600">Configure how your poll works</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Show Results -->
                        <div class="bg-green-50 p-4 rounded-lg">
                            <label class="flex items-start">
                                <input type="checkbox" name="show_results" value="1"
                                    {{ old('show_results', $poll->show_results) ? 'checked' : '' }}
                                    class="mt-1 rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900">Show Results After Voting</div>
                                    <p class="text-sm text-gray-600">People can see results after they vote</p>
                                    <p class="text-xs text-gray-500 mt-1">📊 Recommended - increases engagement</p>
                                </div>
                            </label>
                        </div>

                        <!-- Allow Comments -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <label class="flex items-start">
                                <input type="checkbox" name="allow_comments" value="1"
                                    {{ old('allow_comments', $poll->allow_comments) ? 'checked' : '' }}
                                    class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900">Allow Comments</div>
                                    <p class="text-sm text-gray-600">People can add optional comments with their votes</p>
                                    <p class="text-xs text-gray-500 mt-1">💬 Great for getting detailed feedback</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Visibility -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                4</div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Who Can See This Poll</h3>
                            <p class="text-sm text-gray-600">Choose who can participate in your poll</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="visibility" class="block text-sm font-medium text-gray-700 mb-2">
                            Visibility <span class="text-red-500">*</span>
                        </label>
                        <select name="visibility" id="visibility"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            required>
                            @foreach (\App\Models\Poll::getVisibilityOptions() as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('visibility', $poll->visibility) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department Selection -->
                    <div id="department-section"
                        style="display: {{ old('visibility', $poll->visibility) === 'department' ? 'block' : 'none' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Select Departments <span class="text-red-500">*</span>
                        </label>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-48 overflow-y-auto">
                                @foreach ($departments as $department)
                                    <label class="flex items-center p-2 hover:bg-blue-100 rounded">
                                        <input type="checkbox" name="visible_departments[]"
                                            value="{{ $department->id }}"
                                            {{ in_array($department->id, old('visible_departments', $poll->visible_to ?? [])) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">{{ $department->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- User Selection -->
                    <div id="user-section"
                        style="display: {{ old('visibility', $poll->visibility) === 'custom' ? 'block' : 'none' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Select Users <span class="text-red-500">*</span>
                        </label>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="max-h-48 overflow-y-auto space-y-1">
                                @foreach ($users->groupBy('department_id') as $departmentId => $departmentUsers)
                                    <div class="mb-3">
                                        <h4 class="font-medium text-gray-700 mb-1 px-2">
                                            @if ($departmentId && $departmentUsers->first()->department)
                                                {{ $departmentUsers->first()->department->name }}
                                            @else
                                                No Department
                                            @endif
                                        </h4>
                                        @foreach ($departmentUsers as $user)
                                            <label class="flex items-center p-2 hover:bg-purple-100 rounded ml-4">
                                                <input type="checkbox" name="visible_users[]"
                                                    value="{{ $user->id }}"
                                                    {{ in_array($user->id, old('visible_users', $poll->visible_to ?? [])) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">{{ $user->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5: Status & Schedule -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                5</div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Status & Schedule</h3>
                            <p class="text-sm text-gray-600">Set poll status and optional schedule</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            required>
                            <option value="draft" {{ old('status', $poll->status) === 'draft' ? 'selected' : '' }}>📝
                                Draft</option>
                            <option value="active" {{ old('status', $poll->status) === 'active' ? 'selected' : '' }}>✅
                                Active</option>
                            <option value="closed" {{ old('status', $poll->status) === 'closed' ? 'selected' : '' }}>🔒
                                Closed</option>
                            <option value="archived" {{ old('status', $poll->status) === 'archived' ? 'selected' : '' }}>
                                📦 Archived</option>
                        </select>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800">
                                    <strong>Optional:</strong> Leave schedule dates blank if you want your poll to be
                                    available based on status only.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">
                                📅 Start Date & Time
                            </label>
                            <input type="datetime-local" name="starts_at" id="starts_at"
                                value="{{ old('starts_at', $poll->starts_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <p class="mt-1 text-xs text-gray-500">When should people be able to start voting?</p>
                        </div>

                        <div>
                            <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-2">
                                🏁 End Date & Time
                            </label>
                            <input type="datetime-local" name="ends_at" id="ends_at"
                                value="{{ old('ends_at', $poll->ends_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <p class="mt-1 text-xs text-gray-500">When should voting close?</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg text-white overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold">Ready to Update Your Poll?</h3>
                            <p class="text-blue-100 mt-1">Review your changes and click update when ready</p>
                        </div>
                        <div class="flex-shrink-0">
                            <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-100 text-blue-600 font-semibold rounded-lg shadow-md transition-all hover:shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Update Poll
                        </button>

                        <a href="{{ route('polls.show', $poll) }}"
                            class="text-blue-100 hover:text-white transition-colors font-medium">
                            Cancel
                        </a>

                        @if ($poll->canManage(auth()->user()))
                            <div class="ml-auto">
                                <form method="POST" action="{{ route('polls.destroy', $poll) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this poll? This action cannot be undone.')"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Delete Poll
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const typeSelect = document.getElementById('type');
                const optionsSection = document.getElementById('options-section');
                const ratingSection = document.getElementById('rating-section');
                const visibilitySelect = document.getElementById('visibility');
                const departmentSection = document.getElementById('department-section');
                const userSection = document.getElementById('user-section');
                const optionsContainer = document.getElementById('options-container');
                const addOptionBtn = document.getElementById('add-option');

                // Handle poll type changes
                function toggleSections() {
                    if (!typeSelect) return;
                    const type = typeSelect.value;
                    if (optionsSection) optionsSection.style.display = ['single_choice', 'multiple_choice'].includes(
                        type) ? 'block' : 'none';
                    if (ratingSection) ratingSection.style.display = type === 'rating' ? 'block' : 'none';
                }

                // Handle visibility changes
                function toggleVisibilitySections() {
                    if (!visibilitySelect) return;
                    const visibility = visibilitySelect.value;
                    if (departmentSection) departmentSection.style.display = visibility === 'department' ? 'block' :
                        'none';
                    if (userSection) userSection.style.display = visibility === 'custom' ? 'block' : 'none';
                }

                // Add option functionality
                if (addOptionBtn && optionsContainer) {
                    addOptionBtn.addEventListener('click', function() {
                        const optionRows = optionsContainer.querySelectorAll('.option-input');
                        const newIndex = optionRows.length;
                        const newRow = document.createElement('div');
                        newRow.className =
                            'option-input bg-gray-50 p-4 rounded-lg border-2 border-dashed border-gray-200';
                        newRow.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-sm font-medium text-blue-600">${newIndex + 1}</span>
                        </div>
                        <input type="text" name="options[]" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Option ${newIndex + 1}" required>
                        <button type="button" class="ml-3 p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors remove-option">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                `;
                        optionsContainer.appendChild(newRow);
                        updateOptionNumbers();
                    });
                }

                // Remove option functionality
                if (optionsContainer) {
                    optionsContainer.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-option')) {
                            const optionRows = optionsContainer.querySelectorAll('.option-input');
                            if (optionRows.length > 2) {
                                e.target.closest('.option-input').remove();
                                updateOptionNumbers();
                            } else {
                                alert('You must have at least 2 options.');
                            }
                        }
                    });
                }

                // Update option numbers
                function updateOptionNumbers() {
                    if (!optionsContainer) return;
                    const optionRows = optionsContainer.querySelectorAll('.option-input');
                    optionRows.forEach((row, index) => {
                        const numberSpan = row.querySelector('.text-blue-600');
                        if (numberSpan) {
                            numberSpan.textContent = index + 1;
                        }
                        const input = row.querySelector('input[name="options[]"]');
                        if (input) {
                            input.placeholder = `Option ${index + 1}`;
                        }
                    });
                }

                // Initialize sections
                if (typeSelect) {
                    typeSelect.addEventListener('change', toggleSections);
                    toggleSections();
                }

                if (visibilitySelect) {
                    visibilitySelect.addEventListener('change', toggleVisibilitySections);
                    toggleVisibilitySections();
                }
            });
        </script>
    @endpush
@endsection
