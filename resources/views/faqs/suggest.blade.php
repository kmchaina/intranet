@extends('layouts.app')

@section('title', 'Suggest FAQ')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('faqs.index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">FAQ</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Suggest FAQ</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Suggest a New FAQ</h1>
                <p class="mt-2 text-gray-600">Help improve our knowledge base by suggesting a new frequently asked question.
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Good to know</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Your suggestion will be reviewed by our team</li>
                                <li>If approved, it will be added to the FAQ section</li>
                                <li>You'll be notified once it's been reviewed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <form action="{{ route('faqs.suggest.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Question -->
                    <div>
                        <label for="question" class="block text-sm font-medium text-gray-700 mb-2">
                            Question <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="question" name="question" value="{{ old('question') }}"
                            class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('question') ? 'border-red-300' : 'border-gray-300' }}"
                            placeholder="What question would you like to see answered?" required>
                        @error('question')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Suggested Answer (Optional) -->
                    <div>
                        <label for="suggested_answer" class="block text-sm font-medium text-gray-700 mb-2">
                            Suggested Answer <span class="text-gray-400">(Optional)</span>
                        </label>
                        <textarea id="suggested_answer" name="suggested_answer" rows="6"
                            class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('suggested_answer') ? 'border-red-300' : 'border-gray-300' }}"
                            placeholder="If you know the answer, you can provide it here...">{{ old('suggested_answer') }}</textarea>
                        @error('suggested_answer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">If you don't know the answer, leave this blank and our team
                            will research it.</p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category"
                            class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('category') ? 'border-red-300' : 'border-gray-300' }}"
                            required>
                            <option value="">Choose the most relevant category</option>
                            <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="hr" {{ old('category') == 'hr' ? 'selected' : '' }}>HR & Benefits</option>
                            <option value="it" {{ old('category') == 'it' ? 'selected' : '' }}>IT & Technology</option>
                            <option value="procedures" {{ old('category') == 'procedures' ? 'selected' : '' }}>Procedures &
                                Policies</option>
                            <option value="finance" {{ old('category') == 'finance' ? 'selected' : '' }}>Finance & Expenses
                            </option>
                            <option value="facilities" {{ old('category') == 'facilities' ? 'selected' : '' }}>Facilities &
                                Office</option>
                            <option value="training" {{ old('category') == 'training' ? 'selected' : '' }}>Training &
                                Development</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Additional Context -->
                    <div>
                        <label for="context" class="block text-sm font-medium text-gray-700 mb-2">
                            Additional Context <span class="text-gray-400">(Optional)</span>
                        </label>
                        <textarea id="context" name="context" rows="4"
                            class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('context') ? 'border-red-300' : 'border-gray-300' }}"
                            placeholder="Any additional context or background information that might be helpful...">{{ old('context') }}</textarea>
                        @error('context')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('faqs.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Back to FAQ
                        </a>

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                </path>
                            </svg>
                            Submit Suggestion
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recent Suggestions -->
            @if (auth()->user()->faqSuggestions()->latest()->limit(3)->exists())
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Recent Suggestions</h3>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="divide-y divide-gray-200">
                            @foreach (auth()->user()->faqSuggestions()->latest()->limit(3)->get() as $suggestion)
                                <div class="p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $suggestion->question }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ ucfirst(str_replace('_', ' ', $suggestion->category)) }} •
                                                Submitted {{ $suggestion->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        @php
                                            $statusClasses = match ($suggestion->status) {
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                default => 'bg-red-100 text-red-800',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses }}">
                                            {{ ucfirst($suggestion->status) }}
                                        </span>
                                    </div>
                                    @if ($suggestion->admin_notes && $suggestion->status !== 'pending')
                                        <div class="mt-2 text-sm text-gray-600">
                                            <strong>Note:</strong> {{ $suggestion->admin_notes }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
