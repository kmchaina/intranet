@extends('layouts.dashboard')

@section('title', 'Task Details')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900">Task Details</h1>
                    <p class="text-lg text-gray-600 mt-1">View and manage your task</p>
                </div>
                <div class="flex gap-3">
                    @if (auth()->id() === $todo->user_id)
                        <a href="{{ route('todos.edit', $todo) }}"
                            class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Task
                        </a>
                    @endif
                    <a href="{{ route('todos.index') }}"
                        class="flex items-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="p-8">
            <!-- Task Info Card -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="flex items-center mb-4">
                    <!-- Priority Dot -->
                    <span
                        class="w-4 h-4 rounded-full mr-3
                                @if ($todo->priority === 'high') bg-red-500
                                @elseif($todo->priority === 'medium') bg-yellow-500
                                @else bg-green-500 @endif"></span>

                    <!-- Task Title -->
                    <h3 class="text-xl font-semibold text-gray-800 {{ $todo->completed ? 'line-through' : '' }}">
                        {{ $todo->title }}
                    </h3>

                    <!-- Completion Status -->
                    @if ($todo->completed)
                        <span class="ml-3 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            ✓ Completed
                        </span>
                    @else
                        <span class="ml-3 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                            Pending
                        </span>
                    @endif
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200 capitalize flex items-center">
                            <span
                                class="w-3 h-3 rounded-full mr-2
                                        @if ($todo->priority === 'high') bg-red-500
                                        @elseif($todo->priority === 'medium') bg-yellow-500
                                        @else bg-green-500 @endif"></span>
                            {{ $todo->priority }}
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            @if ($todo->due_date)
                                {{ \Carbon\Carbon::parse($todo->due_date)->format('M j, Y') }}
                                @if (\Carbon\Carbon::parse($todo->due_date)->isPast() && !$todo->completed)
                                    <span class="ml-2 text-red-600 font-medium">(Overdue)</span>
                                @endif
                            @else
                                <span class="text-gray-500">No due date set</span>
                            @endif
                        </div>
                    </div>

                    <!-- Created By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Created By</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            {{ $todo->user->name }}
                        </div>
                    </div>

                    <!-- Created Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            {{ $todo->created_at->format('M j, Y g:i A') }}
                        </div>
                    </div>

                    @if ($todo->completed)
                        <!-- Completion Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Completed</label>
                            <div class="bg-white p-3 rounded-lg border border-gray-200">
                                {{ $todo->updated_at->format('M j, Y g:i A') }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                @if ($todo->description)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            {{ $todo->description }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            @if (auth()->id() === $todo->user_id)
                <div class="flex justify-center gap-4">
                    @if (!$todo->completed)
                        <!-- Mark as Complete -->
                        <form method="POST" action="{{ route('todos.update', $todo) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="completed" value="1">
                            <input type="hidden" name="title" value="{{ $todo->title }}">
                            <input type="hidden" name="description" value="{{ $todo->description }}">
                            <input type="hidden" name="priority" value="{{ $todo->priority }}">
                            <input type="hidden" name="due_date" value="{{ $todo->due_date }}">
                            <button type="submit"
                                class="flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg font-medium transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Mark as Complete
                            </button>
                        </form>
                    @else
                        <!-- Mark as Incomplete -->
                        <form method="POST" action="{{ route('todos.update', $todo) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="completed" value="0">
                            <input type="hidden" name="title" value="{{ $todo->title }}">
                            <input type="hidden" name="description" value="{{ $todo->description }}">
                            <input type="hidden" name="priority" value="{{ $todo->priority }}">
                            <input type="hidden" name="due_date" value="{{ $todo->due_date }}">
                            <button type="submit"
                                class="flex items-center bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg text-lg font-medium transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Mark as Incomplete
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
