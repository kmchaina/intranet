@extends('layouts.dashboard')

@section('title', 'FAQ - Frequently Asked Questions')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">❓ Frequently Asked Questions</h1>
                <p class="text-gray-600 mt-1">Find answers to common questions or suggest new ones</p>
            </div>

            <!-- Search and Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                <div class="p-6">
                    <form method="GET" action="{{ route('faqs.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[300px]">
                            <input type="text" name="search" value="{{ $query }}"
                                placeholder="Search FAQ questions and answers..."
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="min-w-[150px]">
                            <select name="category"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Categories</option>
                                @foreach ($categories as $key => $label)
                                    <option value="{{ $key }}" {{ $category === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Search
                            </button>
                            @if (request()->hasAny(['search', 'category']))
                                <a href="{{ route('faqs.index') }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>

                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ route('faqs.suggest') }}"
                            class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-green-700 bg-green-100 hover:bg-green-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Suggest a Question
                        </a>

                        @if (auth()->user()->canCreateFaqs())
                            <a href="{{ route('faqs.create') }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Create FAQ
                            </a>
                        @endif

                        @if (auth()->user()->canManageFaqs())
                            <a href="{{ route('faqs.suggestions') }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-purple-700 bg-purple-100 hover:bg-purple-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Manage Suggestions
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Featured FAQs -->
                    @if ($featured->count() > 0 && !$query && !$category)
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">⭐ Featured Questions</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($featured as $faq)
                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between mb-2">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $faq->getCategoryLabel() }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $faq->view_count }} views</span>
                                        </div>
                                        <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">
                                            <a href="{{ route('faqs.show', $faq) }}" class="hover:text-blue-600">
                                                {{ $faq->question }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            {{ Str::limit(strip_tags($faq->answer), 100) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- FAQ Results -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">
                                @if ($query || $category)
                                    Search Results
                                @else
                                    All Questions
                                @endif
                            </h2>
                            <div class="text-sm text-gray-500">
                                {{ $faqs->total() }} {{ Str::plural('question', $faqs->total()) }} found
                            </div>
                        </div>

                        @if ($faqs->count() > 0)
                            <div class="space-y-4">
                                @foreach ($faqs as $faq)
                                    <div
                                        class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-sm transition-shadow">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex items-center space-x-3">
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $faq->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $faq->getCategoryLabel() }}
                                                </span>
                                                @if ($faq->is_featured)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        ⭐ Featured
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <span>{{ $faq->view_count }} views</span>
                                                @if ($faq->helpful_count > 0 || $faq->not_helpful_count > 0)
                                                    <span>{{ $faq->getHelpfulPercentage() }}% helpful</span>
                                                @endif
                                            </div>
                                        </div>

                                        <h3 class="text-lg font-medium text-gray-900 mb-3">
                                            <a href="{{ route('faqs.show', $faq) }}" class="hover:text-blue-600">
                                                {{ $faq->question }}
                                            </a>
                                        </h3>

                                        <div class="text-gray-600 mb-4">
                                            {{ Str::limit(strip_tags($faq->answer), 200) }}
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div class="text-sm text-gray-500">
                                                Created by {{ $faq->creator->name }} •
                                                {{ $faq->created_at->diffForHumans() }}
                                            </div>
                                            <a href="{{ route('faqs.show', $faq) }}"
                                                class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                                Read more →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $faqs->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No questions found</h3>
                                <p class="text-gray-500 mb-4">
                                    @if ($query || $category)
                                        No questions match your current search criteria.
                                    @else
                                        No frequently asked questions have been added yet.
                                    @endif
                                </p>
                                <div class="flex justify-center space-x-3">
                                    @if ($query || $category)
                                        <a href="{{ route('faqs.index') }}"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                            Clear search
                                        </a>
                                    @endif
                                    <a href="{{ route('faqs.suggest') }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                        Suggest a question
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Popular Questions -->
                    @if ($popular->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900">🔥 Popular Questions</h3>
                            </div>
                            <div class="p-4 space-y-3">
                                @foreach ($popular as $faq)
                                    <div class="border-b border-gray-100 pb-3 last:border-b-0 last:pb-0">
                                        <a href="{{ route('faqs.show', $faq) }}"
                                            class="block text-sm font-medium text-gray-900 hover:text-blue-600 mb-1 line-clamp-2">
                                            {{ $faq->question }}
                                        </a>
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ $faq->getCategoryLabel() }}</span>
                                            <span>{{ $faq->view_count }} views</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quick Categories -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900">📂 Browse by Category</h3>
                        </div>
                        <div class="p-4 space-y-2">
                            @foreach ($categories as $key => $label)
                                <a href="{{ route('faqs.index', ['category' => $key]) }}"
                                    class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md {{ $category === $key ? 'bg-blue-50 text-blue-700 font-medium' : '' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Help Box -->
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">💡 Can't find what you're looking for?</h3>
                        <p class="text-sm text-gray-600 mb-3">
                            Suggest a new question and our team will provide an answer for everyone to benefit from.
                        </p>
                        <a href="{{ route('faqs.suggest') }}"
                            class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            Suggest Question
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection
