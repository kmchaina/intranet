@extends('layouts.dashboard')

@section('title', $poll->title)

@section('page-title', $poll->title)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $poll->title }}</h1>
        <div class="flex items-center gap-2">
            @if ($canManage)
                <a href="{{ route('polls.edit', $poll) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Poll
                </a>
            @endif
            <a href="{{ route('polls.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Polls
            </a>
        </div>
    </div>

    <!-- Poll Info -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <span
                    class="px-3 py-1 text-sm font-semibold rounded-full
                    @if ($poll->status === 'active') bg-green-100 text-green-800
                    @elseif($poll->status === 'draft') bg-gray-100 text-gray-800
                    @elseif($poll->status === 'closed') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($poll->status) }}
                </span>
                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                    {{ $poll->getTypeLabel() }}
                </span>
                @if ($poll->anonymous)
                    <span class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-full">
                        Anonymous
                    </span>
                @endif
            </div>

            @if ($poll->description)
                <p class="text-gray-700 mb-4">{{ $poll->description }}</p>
            @endif

            <div class="flex items-center text-sm text-gray-500 gap-4">
                <span>Created by {{ $poll->creator->name }}</span>
                <span>{{ $poll->created_at->format('M j, Y') }}</span>
                <span>{{ $poll->getResponseCount() }} responses</span>
                @if ($poll->starts_at)
                    <span>Started {{ $poll->starts_at->diffForHumans() }}</span>
                @endif
                @if ($poll->ends_at)
                    <span>Ends {{ $poll->ends_at->diffForHumans() }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Voting Form -->
    @if ($canVote)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Cast Your Vote</h3>

                <form method="POST" action="{{ route('polls.vote', $poll) }}">
                    @csrf

                    @if ($poll->type === 'single_choice')
                        <div class="space-y-3">
                            @foreach ($poll->options as $index => $option)
                                <label class="flex items-center">
                                    <input type="radio" name="selected_option" value="{{ $index }}" class="mr-3"
                                        required>
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    @elseif($poll->type === 'multiple_choice')
                        <div class="space-y-3">
                            @foreach ($poll->options as $index => $option)
                                <label class="flex items-center">
                                    <input type="checkbox" name="selected_options[]" value="{{ $index }}"
                                        class="mr-3">
                                    <span>{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    @elseif($poll->type === 'rating')
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600">Rate from 1 to {{ $poll->max_rating }}:</p>
                            <div class="flex gap-2">
                                @for ($i = 1; $i <= $poll->max_rating; $i++)
                                    <label class="flex items-center">
                                        <input type="radio" name="rating" value="{{ $i }}" class="mr-1"
                                            required>
                                        <span>{{ $i }}</span>
                                    </label>
                                @endfor
                            </div>
                        </div>
                    @elseif($poll->type === 'yes_no')
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="answer" value="yes" class="mr-3" required>
                                <span>Yes</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="answer" value="no" class="mr-3" required>
                                <span>No</span>
                            </label>
                        </div>
                    @endif

                    @if ($poll->allow_comments)
                        <div class="mt-6">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                                Comment (optional)
                            </label>
                            <textarea name="comment" id="comment" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="Share your thoughts..."></textarea>
                        </div>
                    @endif

                    <div class="mt-6">
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                            Submit Vote
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @elseif($hasVoted)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-800">
                ✓ You have already voted on this poll. Thank you for your participation!
            </p>
        </div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <p class="text-gray-800">
                This poll is not available for voting at the moment.
            </p>
        </div>
    @endif

    <!-- Results -->
    @if ($results)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Results</h3>
                    <a href="{{ route('polls.results', $poll) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View Detailed Results →
                    </a>
                </div>

                @if ($poll->type === 'rating')
                    <div class="mb-4">
                        <div class="text-2xl font-bold text-center">
                            {{ $results['average'] }}/{{ $poll->max_rating }}
                        </div>
                        <div class="text-sm text-gray-600 text-center">
                            Average rating from {{ $results['total_responses'] }} responses
                        </div>
                    </div>

                    <div class="space-y-2">
                        @foreach ($results['breakdown'] as $ratingData)
                            <div class="flex items-center">
                                <span class="w-8 text-sm">{{ $ratingData['rating'] }}</span>
                                <div class="flex-1 mx-3 bg-gray-200 rounded-full h-4 relative">
                                    <div class="bg-blue-500 h-4 rounded-full"
                                        style="width: {{ $ratingData['percentage'] }}%"></div>
                                </div>
                                <span class="w-12 text-sm text-right">{{ $ratingData['count'] }}</span>
                                <span class="w-12 text-xs text-gray-500 text-right">{{ $ratingData['percentage'] }}%</span>
                            </div>
                        @endforeach
                    </div>
                @elseif($poll->type === 'yes_no')
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <span class="w-16 text-sm font-medium">Yes</span>
                            <div class="flex-1 mx-3 bg-gray-200 rounded-full h-6 relative">
                                <div class="bg-green-500 h-6 rounded-full"
                                    style="width: {{ $results['yes']['percentage'] }}%"></div>
                            </div>
                            <span class="w-12 text-sm text-right">{{ $results['yes']['count'] }}</span>
                            <span class="w-12 text-xs text-gray-500 text-right">{{ $results['yes']['percentage'] }}%</span>
                        </div>

                        <div class="flex items-center">
                            <span class="w-16 text-sm font-medium">No</span>
                            <div class="flex-1 mx-3 bg-gray-200 rounded-full h-6 relative">
                                <div class="bg-red-500 h-6 rounded-full"
                                    style="width: {{ $results['no']['percentage'] }}%"></div>
                            </div>
                            <span class="w-12 text-sm text-right">{{ $results['no']['count'] }}</span>
                            <span class="w-12 text-xs text-gray-500 text-right">{{ $results['no']['percentage'] }}%</span>
                        </div>
                    </div>
                @else
                    <!-- Choice-based results -->
                    <div class="space-y-3">
                        @foreach ($results as $result)
                            <div class="flex items-center">
                                <span class="w-1/3 text-sm">{{ Str::limit($result['option'], 30) }}</span>
                                <div class="flex-1 mx-3 bg-gray-200 rounded-full h-6 relative">
                                    <div class="bg-blue-500 h-6 rounded-full"
                                        style="width: {{ $result['percentage'] }}%"></div>
                                </div>
                                <span class="w-12 text-sm text-right">{{ $result['count'] }}</span>
                                <span class="w-12 text-xs text-gray-500 text-right">{{ $result['percentage'] }}%</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Comments -->
    @if ($comments && $comments->count() > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Comments</h3>
                <div class="space-y-4">
                    @foreach ($comments as $comment)
                        <div class="border-l-4 border-gray-300 pl-4">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-sm">
                                    @if ($poll->anonymous || !$comment->user)
                                        Anonymous
                                    @else
                                        {{ $comment->user->name }}
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-gray-700 text-sm">{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection
