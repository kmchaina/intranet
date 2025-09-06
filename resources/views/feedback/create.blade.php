@extends('layouts.dashboard')

@section('title'                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                            placeholder="Brief description of your feedback"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('subject') border-red-500 @enderror">
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>edback')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('feedback.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">Submit Feedback</h1>
                        <p class="text-lg text-gray-600 mt-1">Help us improve by sharing your thoughts</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mx-6 mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mx-6 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Please fix the following errors:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('feedback.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            placeholder="Brief summary of your feedback..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                            <option value="">Select feedback type</option>
                            @foreach ($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Help us categorize your feedback appropriately
                        </p>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select id="priority" name="priority" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('priority') border-red-500 @enderror">
                            <option value="">Select priority level</option>
                            @foreach ($priorities as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('priority', 'medium') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-1 text-sm text-gray-500">
                            <strong>Urgent:</strong> Critical issues affecting work<br>
                            <strong>High:</strong> Important improvements needed<br>
                            <strong>Medium:</strong> General suggestions<br>
                            <strong>Low:</strong> Nice-to-have features
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="8" required
                            placeholder="Provide detailed information about your feedback, suggestion, or issue..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Be as specific as possible to help us understand and address your feedback
                        </p>
                    </div>

                    <!-- File Attachment -->
                    <div>
                        <label for="attachment_path" class="block text-sm font-medium text-gray-700 mb-2">
                            Attachment (Optional)
                        </label>
                        <input type="file" id="attachment_path" name="attachment_path"
                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('attachment_path') border-red-500 @enderror">
                        @error('attachment_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Supported formats: JPG, PNG, PDF, DOC, DOCX (Max: 10MB)<br>
                            Screenshots, documents, or any relevant files to support your feedback
                        </p>
                    </div>

                    <!-- Anonymous Option -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-start">
                            <input type="checkbox" id="is_anonymous" name="is_anonymous" value="1"
                                {{ old('is_anonymous') ? 'checked' : '' }}
                                class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <div class="ml-3">
                                <label for="is_anonymous" class="block text-sm font-medium text-gray-700">
                                    Submit anonymously
                                </label>
                                <p class="text-sm text-gray-500 mt-1">
                                    Your name will not be visible to others, but administrators can still see who submitted
                                    the feedback for follow-up purposes.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('feedback.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Submit Feedback
                </button>
            </div>
        </form>

        <!-- Help Section -->
        <div class="bg-blue-50 border-t border-blue-200 px-6 py-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h4 class="text-blue-800 font-medium">Tips for effective feedback:</h4>
                    <ul class="text-blue-700 text-sm mt-1 space-y-1">
                        <li>• Be specific about what you experienced or what you'd like to see</li>
                        <li>• Include steps to reproduce any issues you encountered</li>
                        <li>• Attach screenshots or documents when relevant</li>
                        <li>• Choose the appropriate priority level for your feedback</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
