@extends('layouts.dashboard')

@section('title', 'To-Do Lists')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900">My Tasks</h1>
                    <p class="text-lg text-gray-600 mt-1">Keep track of what you need to do</p>
                </div>
                <a href="{{ route('todos.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center text-lg font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Task
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-48">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search tasks..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                @if ($projects->count() > 0)
                    <div>
                        <select name="project"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Projects</option>
                            @foreach ($projects as $projectName)
                                <option value="{{ $projectName }}" {{ $project === $projectName ? 'selected' : '' }}>
                                    {{ $projectName }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <select name="status"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        @foreach ($statusOptions as $key => $label)
                            <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="priority"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Priorities</option>
                        @foreach ($priorityOptions as $key => $label)
                            <option value="{{ $key }}" {{ $priority === $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Filter
                </button>

                @if ($search || $status || $priority)
                    <a href="{{ route('todos.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="p-6">
            @php
                $todoItems = is_array($todos) ? collect($todos)->flatten() : $todos;
            @endphp
            @if ($todoItems->count() > 0)
                <div class="grid gap-4">
                    @foreach ($todoItems as $todo)
                        <div
                            class="group relative bg-gradient-to-r from-white to-gray-50 border-l-4 
                            @if ($todo->priority === 'urgent') border-red-500 shadow-red-100
                            @elseif($todo->priority === 'high') border-orange-500 shadow-orange-100
                            @elseif($todo->priority === 'medium') border-yellow-500 shadow-yellow-100
                            @else border-gray-300 shadow-gray-100 @endif
                            rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">

                            <div class="p-5">
                                <div class="flex items-start space-x-4">
                                    <!-- Status Toggle Button with Animation -->
                                    <button onclick="toggleComplete({{ $todo->id }})"
                                        class="mt-1 focus:outline-none transform transition-all duration-200 hover:scale-110">
                                        @if ($todo->status === 'done')
                                            <div class="relative">
                                                <svg class="w-8 h-8 text-green-500 drop-shadow-sm" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="10" />
                                                </svg>
                                                <svg class="absolute inset-0 w-8 h-8 text-white animate-pulse"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M9 12l2 2 4-4" />
                                                </svg>
                                            </div>
                                        @else
                                            <svg class="w-8 h-8 text-gray-300 hover:text-green-400 transition-colors duration-200"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" stroke-width="2" />
                                            </svg>
                                        @endif
                                    </button>

                                    <div class="flex-1 min-w-0">
                                        <!-- Priority Badge & Status Badge -->
                                        <div class="flex items-center gap-3 mb-3">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide
                                                @if ($todo->priority === 'urgent') bg-red-100 text-red-800 ring-2 ring-red-200
                                                @elseif($todo->priority === 'high') bg-orange-100 text-orange-800 ring-2 ring-orange-200
                                                @elseif($todo->priority === 'medium') bg-yellow-100 text-yellow-800 ring-2 ring-yellow-200
                                                @else bg-gray-100 text-gray-800 ring-2 ring-gray-200 @endif">
                                                @if ($todo->priority === 'urgent')
                                                    🔥 Urgent
                                                @elseif($todo->priority === 'high')
                                                    ⚡ High Priority
                                                @elseif($todo->priority === 'medium')
                                                    ⚠️ Medium
                                                @else
                                                    📝 Low Priority
                                                @endif
                                            </span>

                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                @if ($todo->status === 'todo') bg-slate-100 text-slate-700
                                                @elseif($todo->status === 'in_progress') bg-blue-100 text-blue-700
                                                @elseif($todo->status === 'review') bg-purple-100 text-purple-700
                                                @else bg-green-100 text-green-700 @endif">
                                                @if ($todo->status === 'todo')
                                                    📋 To Do
                                                @elseif($todo->status === 'in_progress')
                                                    🔄 In Progress
                                                @elseif($todo->status === 'review')
                                                    👁️ Under Review
                                                @else
                                                    ✅ Completed
                                                @endif
                                            </span>
                                        </div>

                                        <!-- Task Title with Hover Effects -->
                                        <h3
                                            class="text-xl font-bold mb-2 {{ $todo->status === 'done' ? 'line-through text-gray-500' : 'text-gray-900' }} 
                                            group-hover:text-blue-600 transition-colors duration-200">
                                            <a href="{{ route('todos.show', $todo) }}" class="block hover:underline">
                                                {{ $todo->title }}
                                            </a>
                                        </h3>

                                        @if ($todo->description)
                                            <p class="text-gray-600 mt-2 leading-relaxed text-sm">
                                                {{ Str::limit($todo->description, 150) }}
                                            </p>
                                        @endif

                                        <!-- Enhanced Meta Info -->
                                        <div class="flex items-center gap-4 mt-4 text-sm">
                                            @if ($todo->due_date)
                                                <div
                                                    class="flex items-center gap-2 px-2 py-1 rounded-lg
                                                    {{ $todo->due_date->isPast() && $todo->status !== 'done' ? 'bg-red-50 text-red-700 ring-1 ring-red-200' : 'bg-gray-50 text-gray-600' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="font-medium">
                                                        Due {{ $todo->due_date->format('M j, Y') }}
                                                        @if ($todo->due_date->isPast() && $todo->status !== 'done')
                                                            <span
                                                                class="text-xs bg-red-200 text-red-800 px-1.5 py-0.5 rounded ml-1 animate-pulse">Overdue!</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="flex items-center gap-2 text-gray-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span class="font-medium">{{ $todo->user->name }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Floating Action Buttons -->
                                    <div
                                        class="flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                                        <!-- Quick Edit Button -->
                                        <a href="{{ route('todos.edit', $todo) }}"
                                            class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-full shadow-lg transition-all duration-200 transform hover:scale-110 hover:rotate-12"
                                            title="Edit Task">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        @if ($todo->user_id === auth()->id())
                                            <!-- Delete Button -->
                                            <form action="{{ route('todos.destroy', $todo) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('🗑️ Are you sure you want to delete this task? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-full shadow-lg transition-all duration-200 transform hover:scale-110 hover:rotate-12"
                                                    title="Delete Task">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <!-- Progress Bar for Visual Appeal -->
                                @if ($todo->status !== 'done')
                                    <div class="mt-4 bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                        <div
                                            class="bg-gradient-to-r 
                                            @if ($todo->status === 'todo') from-gray-400 to-gray-500 w-1/4
                                            @elseif($todo->status === 'in_progress') from-blue-400 to-blue-500 w-1/2
                                            @elseif($todo->status === 'review') from-purple-400 to-purple-500 w-3/4
                                            @else from-green-400 to-green-500 w-full @endif
                                            h-full rounded-full transition-all duration-500">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if ($search || $status || $priority)
                            No tasks match your current filters.
                        @else
                            Get started by creating your first task.
                        @endif
                    </p>
                    @if (!($search || $status || $priority))
                        <div class="mt-6">
                            <a href="{{ route('todos.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Task
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    </div>

    <script>
        function toggleComplete(todoId) {
            // Add a loading state to the button
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;

            // Show loading spinner
            button.innerHTML = `
                <svg class="w-8 h-8 text-gray-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2" stroke-dasharray="31.416" stroke-dashoffset="31.416">
                        <animate attributeName="stroke-dasharray" dur="2s" values="0 31.416;15.708 15.708;0 31.416" repeatCount="indefinite"/>
                        <animate attributeName="stroke-dashoffset" dur="2s" values="0;-15.708;-31.416" repeatCount="indefinite"/>
                    </circle>
                </svg>
            `;
            button.disabled = true;

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
                        // Show success animation before reload
                        button.innerHTML = `
                            <div class="relative">
                                <svg class="w-8 h-8 text-green-500 animate-bounce" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                <svg class="absolute inset-0 w-8 h-8 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4" />
                                </svg>
                            </div>
                        `;

                        // Add confetti effect for completed tasks
                        if (data.completed) {
                            showConfetti();
                        }

                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        // Restore original state on error
                        button.innerHTML = originalContent;
                        button.disabled = false;
                        alert('❌ Something went wrong. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.innerHTML = originalContent;
                    button.disabled = false;
                    alert('❌ Network error. Please check your connection and try again.');
                });
        }

        function showConfetti() {
            // Simple confetti effect
            const confetti = document.createElement('div');
            confetti.innerHTML = '🎉🎊✨🌟💫⭐';
            confetti.style.position = 'fixed';
            confetti.style.top = '50%';
            confetti.style.left = '50%';
            confetti.style.transform = 'translate(-50%, -50%)';
            confetti.style.fontSize = '3rem';
            confetti.style.zIndex = '9999';
            confetti.style.pointerEvents = 'none';
            confetti.style.animation = 'confetti-burst 2s ease-out forwards';

            document.body.appendChild(confetti);

            setTimeout(() => {
                confetti.remove();
            }, 2000);
        }

        // Add CSS animation for confetti
        const style = document.createElement('style');
        style.textContent = `
            @keyframes confetti-burst {
                0% {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(0.5) rotate(0deg);
                }
                50% {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(1.2) rotate(180deg);
                }
                100% {
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(0.8) rotate(360deg);
                }
            }
            
            .task-hover-effect:hover {
                transform: translateY(-4px) !important;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            }
            
            .priority-glow-urgent { box-shadow: 0 0 20px rgba(239, 68, 68, 0.3); }
            .priority-glow-high { box-shadow: 0 0 20px rgba(249, 115, 22, 0.3); }
            .priority-glow-medium { box-shadow: 0 0 20px rgba(245, 158, 11, 0.3); }
        `;
        document.head.appendChild(style);

        // Add hover effects to cards
        document.addEventListener('DOMContentLoaded', function() {
            const taskCards = document.querySelectorAll('.group');
            taskCards.forEach(card => {
                card.classList.add('task-hover-effect');

                // Add priority glow effect
                if (card.classList.contains('border-red-500')) {
                    card.classList.add('priority-glow-urgent');
                } else if (card.classList.contains('border-orange-500')) {
                    card.classList.add('priority-glow-high');
                } else if (card.classList.contains('border-yellow-500')) {
                    card.classList.add('priority-glow-medium');
                }
            });
        });
    </script>
@endsection
