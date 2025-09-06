@extends('layouts.dashboard')

@section('title', 'Feedback & Suggestions')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900">Feedback & Suggestions</h1>
                    <p class="text-lg text-gray-600 mt-1">Share your ideas and help us improve</p>
                </div>
                <a href="{{ route('feedback.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center text-lg font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Submit Feedback
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mx-6 mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        <!-- Filters -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-48">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search feedback..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <select name="status"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Statuses</option>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="type"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        @foreach ($types as $key => $label)
                            <option value="{{ $key }}" {{ $type === $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="priority"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Priorities</option>
                        @foreach ($priorities as $key => $label)
                            <option value="{{ $key }}" {{ $priority === $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Search
                </button>

                @if ($search || $status || $type || $priority)
                    <a href="{{ route('feedback.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Feedback List -->
        <div class="p-6">
            @if ($feedback->count() > 0)
                <div class="space-y-4">
                    @foreach ($feedback as $item)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            <a href="{{ route('feedback.show', $item) }}" class="hover:text-blue-600">
                                                {{ $item->subject }}
                                            </a>
                                        </h3>

                                        <!-- Status Badge -->
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($item->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($item->status === 'under_review') bg-blue-100 text-blue-800
                                            @elseif($item->status === 'in_progress') bg-purple-100 text-purple-800
                                            @elseif($item->status === 'resolved') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $statuses[$item->status] }}
                                        </span>

                                        <!-- Priority Badge -->
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($item->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($item->priority === 'high') bg-orange-100 text-orange-800
                                            @elseif($item->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ $priorities[$item->priority] }}
                                        </span>

                                        <!-- Type Badge -->
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $types[$item->type] }}
                                        </span>
                                    </div>

                                    <p class="text-gray-600 mb-3 line-clamp-2">{{ Str::limit($item->message, 150) }}
                                    </p>

                                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            @if ($item->is_anonymous)
                                                Anonymous
                                            @else
                                                {{ $item->submitter->name ?? 'Unknown' }}
                                            @endif
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $item->created_at->format('M j, Y g:i A') }}
                                        </span>
                                        @if ($item->attachment_path)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                Has Attachment
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('feedback.show', $item) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View Details
                                    </a>
                                    @if (!$item->is_anonymous && $item->submitted_by === Auth::id())
                                        <a href="{{ route('feedback.edit', $item) }}"
                                            class="text-gray-400 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if ($item->admin_response)
                                <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-400 rounded-r">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        <span class="text-blue-800 font-medium text-sm">Admin Response:</span>
                                    </div>
                                    <p class="text-blue-700 text-sm mt-1">{{ $item->admin_response }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $feedback->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No feedback found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if ($search || $status || $type || $priority)
                            No feedback matches your current filters.
                        @else
                            Be the first to share your feedback and help us improve.
                        @endif
                    </p>
                    @if (!($search || $status || $type || $priority))
                        <div class="mt-6">
                            <a href="{{ route('feedback.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Submit Feedback
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
