@extends('layouts.app')

@section('title', 'Manage FAQ Suggestions')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">FAQ Suggestions</h1>
                <p class="mt-2 text-gray-600">Review and manage user-submitted FAQ suggestions.</p>
            </div>
            <a href="{{ route('faqs.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd"></path>
                </svg>
                Back to FAQ
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Filters</h3>
            </div>
            <div class="p-4">
                <form method="GET" class="flex flex-wrap gap-4">
                    <!-- Status Filter -->
                    <div class="flex-1 min-w-48">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="flex-1 min-w-48">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" id="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Categories</option>
                            <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>General
                            </option>
                            <option value="hr" {{ request('category') === 'hr' ? 'selected' : '' }}>HR & Benefits
                            </option>
                            <option value="it" {{ request('category') === 'it' ? 'selected' : '' }}>IT & Technology
                            </option>
                            <option value="procedures" {{ request('category') === 'procedures' ? 'selected' : '' }}>
                                Procedures & Policies</option>
                            <option value="finance" {{ request('category') === 'finance' ? 'selected' : '' }}>Finance &
                                Expenses</option>
                            <option value="facilities" {{ request('category') === 'facilities' ? 'selected' : '' }}>
                                Facilities & Office</option>
                            <option value="training" {{ request('category') === 'training' ? 'selected' : '' }}>Training &
                                Development</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Apply Filters
                        </button>
                        @if (request()->hasAny(['status', 'category']))
                            <a href="{{ route('faqs.suggestions') }}"
                                class="ml-2 px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Approved</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Rejected</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $suggestions->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suggestions List -->
        @if ($suggestions->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Suggestions
                        @if (request()->hasAny(['status', 'category']))
                            <span class="text-sm font-normal text-gray-500">(filtered)</span>
                        @endif
                    </h3>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach ($suggestions as $suggestion)
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $suggestion->question }}</h4>
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
                                    </div>

                                    <!-- Meta Information -->
                                    <div class="flex items-center text-sm text-gray-500 space-x-4 mb-3">
                                        <span>{{ ucfirst(str_replace('_', ' ', $suggestion->category)) }}</span>
                                        <span>By {{ $suggestion->user->name }}</span>
                                        <span>{{ $suggestion->created_at->format('M j, Y g:i A') }}</span>
                                    </div>

                                    <!-- Suggested Answer -->
                                    @if ($suggestion->suggested_answer)
                                        <div class="mb-3">
                                            <p class="text-sm font-medium text-gray-700 mb-1">Suggested Answer:</p>
                                            <p class="text-sm text-gray-600 bg-gray-50 rounded-md p-3">
                                                {{ $suggestion->suggested_answer }}</p>
                                        </div>
                                    @endif

                                    <!-- Context -->
                                    @if ($suggestion->context)
                                        <div class="mb-3">
                                            <p class="text-sm font-medium text-gray-700 mb-1">Additional Context:</p>
                                            <p class="text-sm text-gray-600">{{ $suggestion->context }}</p>
                                        </div>
                                    @endif

                                    <!-- Admin Notes -->
                                    @if ($suggestion->admin_notes)
                                        <div class="mb-3">
                                            <p class="text-sm font-medium text-gray-700 mb-1">Review Notes:</p>
                                            <p class="text-sm text-gray-600 bg-blue-50 rounded-md p-3">
                                                {{ $suggestion->admin_notes }}</p>
                                        </div>
                                    @endif

                                    <!-- Action Form -->
                                    @if ($suggestion->status === 'pending')
                                        <form action="{{ route('faqs.suggestions.review', $suggestion) }}" method="POST"
                                            class="mt-4" id="review-form-{{ $suggestion->id }}">
                                            @csrf
                                            @method('PATCH')

                                            <div class="space-y-3">
                                                <div>
                                                    <label for="admin_notes_{{ $suggestion->id }}"
                                                        class="block text-sm font-medium text-gray-700 mb-1">
                                                        Review Notes
                                                    </label>
                                                    <textarea id="admin_notes_{{ $suggestion->id }}" name="admin_notes" rows="2"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                        placeholder="Add any notes about your decision..."></textarea>
                                                </div>

                                                <div class="flex space-x-3">
                                                    <button type="submit" name="status" value="approved"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Approve
                                                    </button>

                                                    <button type="submit" name="status" value="rejected"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Reject
                                                    </button>

                                                    <a href="{{ route('faqs.create') }}?suggestion={{ $suggestion->id }}"
                                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Create FAQ
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if ($suggestions->hasPages())
                <div class="mt-6">
                    {{ $suggestions->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No suggestions found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if (request()->hasAny(['status', 'category']))
                            Try adjusting your filters to see more results.
                        @else
                            No FAQ suggestions have been submitted yet.
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>
@endsection
