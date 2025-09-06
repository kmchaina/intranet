@extends('layouts.dashboard')

@section('title', 'Polls')

@section('page-title', 'Polls')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Polls</h1>
        @auth
            <a href="{{ route('polls.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Poll
            </a>
        @endauth
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('polls.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-64">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search polls..." 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        <div>
                            <select name="type" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Types</option>
                                <option value="single_choice" {{ request('type') === 'single_choice' ? 'selected' : '' }}>Single Choice</option>
                                <option value="multiple_choice" {{ request('type') === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="rating" {{ request('type') === 'rating' ? 'selected' : '' }}>Rating</option>
                                <option value="yes_no" {{ request('type') === 'yes_no' ? 'selected' : '' }}>Yes/No</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Filter
                        </button>
                        <a href="{{ route('polls.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Clear
                        </a>
                    </form>
                </div>
            </div>

            <!-- Polls List -->
            <div class="space-y-4">
                @forelse($polls as $poll)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold">
                                            <a href="{{ route('polls.show', $poll) }}" class="text-blue-600 hover:text-blue-800">
                                                {{ $poll->title }}
                                            </a>
                                        </h3>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($poll->status === 'active') bg-green-100 text-green-800
                                            @elseif($poll->status === 'draft') bg-gray-100 text-gray-800
                                            @elseif($poll->status === 'closed') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($poll->status) }}
                                        </span>
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            {{ $poll->getTypeLabel() }}
                                        </span>
                                        @if($poll->anonymous)
                                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">
                                                Anonymous
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($poll->description)
                                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($poll->description, 150) }}</p>
                                    @endif
                                    
                                    <div class="flex items-center text-sm text-gray-500 gap-4">
                                        <span>Created by {{ $poll->creator->name }}</span>
                                        <span>{{ $poll->created_at->diffForHumans() }}</span>
                                        <span>{{ $poll->getResponseCount() }} responses</span>
                                        @if($poll->ends_at)
                                            <span>Ends {{ $poll->ends_at->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    @if($poll->canVote(auth()->user()))
                                        <a href="{{ route('polls.show', $poll) }}" 
                                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                            Vote
                                        </a>
                                    @elseif($poll->hasUserVoted(auth()->user()))
                                        <span class="bg-gray-500 text-white font-bold py-1 px-3 rounded text-sm">
                                            Voted
                                        </span>
                                    @endif
                                    
                                    @if($poll->canManage(auth()->user()))
                                        <a href="{{ route('polls.edit', $poll) }}" 
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                            Edit
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center text-gray-500">
                            <p>No polls found.</p>
                            @auth
                                <a href="{{ route('polls.create') }}" class="text-blue-600 hover:text-blue-800">
                                    Create the first poll →
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($polls->hasPages())
                <div class="mt-6">
                    {{ $polls->withQueryString()->links() }}
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Active Polls -->
            @if($activePolls->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Active Polls</h3>
                        <div class="space-y-3">
                            @foreach($activePolls as $activePoll)
                                <div class="border-l-4 border-green-500 pl-3">
                                    <h4 class="font-semibold text-sm">
                                        <a href="{{ route('polls.show', $activePoll) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ Str::limit($activePoll->title, 40) }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-gray-500">
                                        {{ $activePoll->getResponseCount() }} responses
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Poll Stats -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Stats</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Total Polls:</span>
                            <span class="font-semibold">{{ \App\Models\Poll::count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Active:</span>
                            <span class="font-semibold text-green-600">{{ \App\Models\Poll::where('status', 'active')->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>My Polls:</span>
                            <span class="font-semibold">{{ auth()->check() ? \App\Models\Poll::where('created_by', auth()->id())->count() : 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
