@extends('layouts.dashboard')

@section('title', 'Create Task')

@section('content')
    <div class="bg-white shadow rounded-lg max-w-2xl mx-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-3xl font-semibold text-gray-900">Add New Task</h1>
            <p class="text-lg text-gray-600 mt-1">Create a new task to remember</p>
        </div>

        <form action="{{ route('todos.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            @if ($parent)
                <input type="hidden" name="parent_task_id" value="{{ $parent->id }}">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-blue-900">Creating subtask for:</h3>
                    <p class="text-lg text-blue-700 mt-1">{{ $parent->title }}</p>
                </div>
            @endif

            <!-- Title -->
            <div>
                <label for="title" class="block text-lg font-medium text-gray-700 mb-2">What do you need to do? *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., Write report, Call John, Review documents">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-lg font-medium text-gray-700 mb-2">More details
                    (Optional)</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Add any extra details about this task...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Priority -->
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-3">How urgent is this?</label>
                    <div class="space-y-2">
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="priority" value="low"
                                {{ old('priority', 'medium') === 'low' ? 'checked' : '' }} class="mr-3">
                            <span class="text-lg">Not urgent</span>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="priority" value="medium"
                                {{ old('priority', 'medium') === 'medium' ? 'checked' : '' }} class="mr-3">
                            <span class="text-lg">Normal</span>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="priority" value="high"
                                {{ old('priority', 'medium') === 'high' ? 'checked' : '' }} class="mr-3">
                            <span class="text-lg">Important</span>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="priority" value="urgent"
                                {{ old('priority', 'medium') === 'urgent' ? 'checked' : '' }} class="mr-3">
                            <span class="text-lg">Very urgent!</span>
                        </label>
                    </div>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-3">What's the status?</label>
                    <div class="space-y-2">
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="todo"
                                {{ old('status', 'todo') === 'todo' ? 'checked' : '' }} class="mr-3">
                            <span class="text-lg">Not started</span>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="in_progress"
                                {{ old('status', 'todo') === 'in_progress' ? 'checked' : '' }} class="mr-3">
                            <span class="text-lg">Working on it</span>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="review"
                                {{ old('status', 'todo') === 'review' ? 'checked' : '' }} class="mr-3">
                            <span class="text-lg">Need review</span>
                        </label>
                        <label
                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="done"
                                {{ old('status', 'todo') === 'done' ? 'checked' : '' }} class="mr-3">
                            <span class="text-lg">Completed</span>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Due Date -->
            <div>
                <label for="due_date" class="block text-lg font-medium text-gray-700 mb-2">When is this due?
                    (Optional)</label>
                <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg focus:ring-blue-500 focus:border-blue-500">
                @error('due_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('todos.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-8 py-3 rounded-lg text-lg">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium">
                    Create Task
                </button>
            </div>
        </form>
    </div>
@endsection
