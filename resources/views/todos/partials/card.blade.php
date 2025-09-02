<!-- Todo Card for Board View -->
<div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer"
    onclick="window.location.href='{{ route('todos.show', $todo) }}'">

    <!-- Priority Indicator -->
    <div class="flex items-start justify-between mb-2">
        <div class="flex items-center">
            @if ($todo->priority === 'urgent')
                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
            @elseif($todo->priority === 'high')
                <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
            @elseif($todo->priority === 'medium')
                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
            @else
                <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
            @endif
        </div>

        <!-- Quick Complete Toggle -->
        <button onclick="event.stopPropagation(); toggleComplete({{ $todo->id }})"
            class="text-gray-400 hover:text-green-600">
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
    </div>

    <!-- Title -->
    <h4 class="font-medium text-gray-900 mb-2 text-sm">{{ $todo->title }}</h4>

    <!-- Description (truncated) -->
    @if ($todo->description)
        <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ Str::limit($todo->description, 80) }}</p>
    @endif

    <!-- Progress Bar -->
    @if ($todo->progress_percentage > 0)
        <div class="mb-3">
            <div class="flex items-center justify-between text-xs mb-1">
                <span class="text-gray-600">Progress</span>
                <span class="font-medium text-gray-900">{{ $todo->progress_percentage }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5">
                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $todo->progress_percentage }}%"></div>
            </div>
        </div>
    @endif

    <!-- Footer Info -->
    <div class="flex items-center justify-between text-xs text-gray-500">
        <div class="flex items-center space-x-2">
            @if ($todo->project)
                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $todo->project }}</span>
            @endif

            @if ($todo->due_date)
                <span
                    class="flex items-center {{ $todo->due_date->isPast() && $todo->status !== 'done' ? 'text-red-600' : '' }}">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $todo->due_date->format('M j') }}
                </span>
            @endif
        </div>

        @if ($todo->assignedUser)
            <span class="text-gray-600">{{ $todo->assignedUser->name }}</span>
        @endif
    </div>
</div>

<script>
    function toggleComplete(todoId) {
        fetch(`/todos/${todoId}/toggle-complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>
