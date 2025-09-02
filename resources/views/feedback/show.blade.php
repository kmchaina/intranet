@extends('layouts.dashboard')

@section('title', 'Feedback Details')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Feedback Details</h1>
                    <p class="text-gray-600 mt-1">View feedback submission details and responses</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('feedback.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to List
                    </a>
                    @if (!$feedback->is_anonymous && $feedback->submitted_by === Auth::id())
                        <a href="{{ route('feedback.edit', $feedback) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                    @endif
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <!-- Header Info -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center space-x-4">
                            <span class="text-2xl">{{ $feedback->type_icon }}</span>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ $feedback->subject }}</h2>
                                <div class="flex items-center space-x-4 mt-1 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        @if ($feedback->is_anonymous)
                                            Anonymous
                                        @else
                                            {{ $feedback->submitter->name ?? 'Unknown' }}
                                        @endif
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $feedback->created_at->format('M j, Y g:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $feedback->status_color }}">
                                {{ ucfirst(str_replace('_', ' ', $feedback->status)) }}
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $feedback->priority_color }}">
                                {{ ucfirst($feedback->priority) }} Priority
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-6">
                    <!-- Type and Category -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <p class="text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $feedback->type) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <p class="text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $feedback->category) }}
                            </p>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $feedback->message }}</p>
                        </div>
                    </div>

                    <!-- Attachments -->
                    @if ($feedback->attachments && count($feedback->attachments) > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Attachments</label>
                            <div class="space-y-2">
                                @foreach ($feedback->attachments as $attachment)
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <span class="text-sm text-gray-900 flex-1">{{ basename($attachment) }}</span>
                                        <a href="{{ Storage::url($attachment) }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium ml-3">
                                            Download
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Admin Response -->
                    @if ($feedback->admin_response)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Response</label>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-blue-900 whitespace-pre-wrap">{{ $feedback->admin_response }}</p>
                                        @if ($feedback->responded_at)
                                            <p class="text-xs text-blue-600 mt-2">
                                                Responded on {{ $feedback->responded_at->format('M j, Y g:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-gray-200">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitted</label>
                            <p class="text-sm text-gray-900">{{ $feedback->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                        @if ($feedback->responded_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Responded</label>
                                <p class="text-sm text-gray-900">{{ $feedback->responded_at->format('M j, Y g:i A') }}</p>
                            </div>
                        @endif
                        @if ($feedback->resolved_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Resolved</label>
                                <p class="text-sm text-gray-900">{{ $feedback->resolved_at->format('M j, Y g:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions for Admins -->
            @if (Auth::user()->is_admin && $feedback->status !== 'closed')
                <div class="mt-6 bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">Admin Actions</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form action="{{ route('feedback.update', $feedback) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="status"
                                        class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" id="status"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @foreach ($statuses as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ $feedback->status === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="priority"
                                        class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                    <select name="priority" id="priority"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @foreach (App\Models\Feedback::getPriorities() as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ $feedback->priority === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="admin_response" class="block text-sm font-medium text-gray-700 mb-1">Admin
                                    Response</label>
                                <textarea name="admin_response" id="admin_response" rows="4"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Enter your response...">{{ $feedback->admin_response }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Update Feedback
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
