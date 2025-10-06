@extends('layouts.dashboard')
@section('title', 'Feedback & Suggestions')

@section('content')
    <div class="space-y-6">
        <x-breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Feedback & Suggestions']]" />

        <!-- Header Card -->
        <div class="card-premium overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-8 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">Suggestions & Ideas</h1>
                            <p class="text-white/90 mt-1">Share your ideas to make NIMR better</p>
                        </div>
                    </div>
                    <a href="{{ route('feedback.create') }}" class="btn btn-ghost text-white hover:bg-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Share an Idea
                    </a>
                </div>
            </div>
        </div>

        @php
            $totalFeedback = $feedback->total();
            $newIdeasCount = App\Models\Feedback::where('status', 'new')
                ->when(
                    !auth()->user()->isAdmin(),
                    fn($q) => $q->where(function ($q) {
                        $q->where('submitted_by', auth()->id())->orWhere('is_public', true);
                    }),
                )
                ->count();
            $reviewedCount = App\Models\Feedback::where('status', 'reviewed')
                ->when(
                    !auth()->user()->isAdmin(),
                    fn($q) => $q->where(function ($q) {
                        $q->where('submitted_by', auth()->id())->orWhere('is_public', true);
                    }),
                )
                ->count();
            $implementedCount = App\Models\Feedback::where('status', 'implemented')
                ->when(
                    !auth()->user()->isAdmin(),
                    fn($q) => $q->where(function ($q) {
                        $q->where('submitted_by', auth()->id())->orWhere('is_public', true);
                    }),
                )
                ->count();
        @endphp

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="stat-card">
                <div class="stat-card-icon bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $totalFeedback }}</div>
                <div class="stat-card-label">Total Ideas</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $newIdeasCount }}</div>
                <div class="stat-card-label">New Ideas</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $reviewedCount }}</div>
                <div class="stat-card-label">Under Review</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="stat-card-value">{{ $implementedCount }}</div>
                <div class="stat-card-label">Implemented</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-premium p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search feedback..."
                        class="input">
                    <select name="status" class="input">
                        <option value="">All Statuses</option>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <select name="type" class="input">
                        <option value="">All Types</option>
                        @foreach ($types as $key => $label)
                            <option value="{{ $key }}" {{ $type === $key ? 'selected' : '' }}>{{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    @if ($search || $status || $type)
                        <a href="{{ route('feedback.index') }}" class="btn btn-outline">Clear Filters</a>
                    @endif
                </div>
            </form>
        </div>

        @if ($feedback->count() > 0)
            <div class="space-y-4">
                @foreach ($feedback as $item)
                    <div class="card-premium p-6 hover:shadow-lg transition-shadow">
                        <div class="flex gap-4">
                            <div class="text-4xl">{{ $item->type_icon }}</div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-nimr-neutral-900 mb-2">{{ $item->subject }}</h3>
                                <div class="flex gap-2 mb-3">
                                    <span
                                        class="badge {{ $item->status_color }}">{{ $statuses[$item->status] ?? ucfirst($item->status) }}</span>
                                    <span
                                        class="badge badge-info">{{ $types[$item->type] ?? ucfirst($item->type) }}</span>
                                    @if ($item->upvotes_count > 0)
                                        <span class="badge bg-yellow-100 text-yellow-800">👍
                                            {{ $item->upvotes_count }}</span>
                                    @endif
                                </div>
                                <p class="text-nimr-neutral-700 mb-4 line-clamp-3">{{ Str::limit($item->message, 200) }}
                                </p>
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex gap-4 text-nimr-neutral-500">
                                        <span>{{ $item->created_at->format('M j, Y') }}</span>
                                        @if ($item->admin_response_text)
                                            <span class="text-green-600 font-semibold">✓ Admin Response</span>
                                        @endif
                                        @if ($item->is_public)
                                            <span class="text-nimr-primary-600 font-semibold">🌐 Public</span>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('feedback.show', $item) }}"
                                            class="btn btn-sm btn-primary">View</a>
                                        @if (auth()->user()->isAdmin() || $item->submitted_by === auth()->id())
                                            <a href="{{ route('feedback.edit', $item) }}"
                                                class="btn btn-sm btn-outline">Edit</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($feedback->hasPages())
                <div class="card-premium p-6">{{ $feedback->appends(request()->query())->links() }}</div>
            @endif
        @else
            <div class="card-premium p-12">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.681L3 21l2.681-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                        </svg>
                    </div>
                    <p class="empty-state-title">No Feedback Found</p>
                    <p class="empty-state-description">Share your thoughts and help us improve!</p>
                    <a href="{{ route('feedback.create') }}" class="btn btn-primary mt-4">Submit Feedback</a>
                </div>
            </div>
        @endif
    </div>
@endsection
