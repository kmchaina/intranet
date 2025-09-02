<!-- Todo List Item for List View -->
<div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
    <div class="flex items-start space-x-4">

        <!-- Complete Toggle -->
        <button onclick="toggleComplete({{ $todo->id }})"
            class="mt-1 flex-shrink-0 text-gray-400 hover:text-green-600">
            @if ($todo->status === 'done')
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                </svg>
            @endif
        </button>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between">
                <div class="flex-1">

                    <!-- Title and Priority -->
                    <div class="flex items-center mb-1">
                        @if ($todo->priority === 'urgent')
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                        @elseif($todo->priority === 'high')
                            <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                        @elseif($todo->priority === 'medium')
                            <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                        @else
                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                        @endif

                        <h3
                            class="text-lg font-medium text-gray-900 {{ $todo->status === 'done' ? 'line-through text-gray-500' : '' }}">
                            <a href="{{ route('todos.show', $todo) }}" class="hover:text-blue-600">
                                {{ $todo->title }}
                            </a>
                        </h3>
                    </div>

                    <!-- Description -->
                    @if ($todo->description)
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($todo->description, 150) }}</p>
                    @endif

                    <!-- Progress Bar -->
                    @if ($todo->progress_percentage > 0)
                        <div class="mb-3 max-w-md">
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-gray-600">Progress</span>
                                <span class="font-medium text-gray-900">{{ $todo->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full"
                                    style="width: {{ $todo->progress_percentage }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Meta Information -->
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">

                        <!-- Status Badge -->
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if ($todo->status === 'todo') bg-gray-100 text-gray-800
                            @elseif($todo->status === 'in_progress') bg-blue-100 text-blue-800
                            @elseif($todo->status === 'review') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            @if ($todo->status === 'todo')
                                To Do
                            @elseif($todo->status === 'in_progress')
                                In Progress
                            @elseif($todo->status === 'review')
                                Review
                            @else
                                Done
                            @endif
                        </span>

                        <!-- Project -->
                        @if ($todo->project)
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                {{ $todo->project }}
                            </span>
                        @endif

                        <!-- Due Date -->
                        @if ($todo->due_date)
                            <span
                                class="flex items-center {{ $todo->due_date->isPast() && $todo->status !== 'done' ? 'text-red-600 font-medium' : '' }}">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Due {{ $todo->due_date->format('M j, Y') }}
                                @if ($todo->due_date->isPast() && $todo->status !== 'done')
                                    (Overdue)
                                @endif
                            </span>
                        @endif

                        <!-- Assigned User -->
                        @if ($todo->assignedUser && $todo->assignedUser->id !== auth()->id())
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $todo->assignedUser->name }}
                            </span>
                        @endif

                        <!-- Estimated Hours -->
                        @if ($todo->estimated_hours)
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $todo->estimated_hours }}h estimated
                            </span>
                        @endif

                        <!-- Created Date -->
                        <span>Created {{ $todo->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2 ml-4">
                    <a href="{{ route('todos.edit', $todo) }}" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>

                    @if ($todo->user_id === auth()->id())
                        <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
