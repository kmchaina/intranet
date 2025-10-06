@extends('layouts.dashboard')

@section('title', 'Training Management')
@section('page-title', 'Training Management')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Training Modules</h2>
                    <p class="text-sm text-gray-600">Manage training content for staff.</p>
                </div>
                <button onclick="document.getElementById('training-modal').classList.remove('hidden')"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                    Add Training
                </button>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <form method="GET" data-auto-submit class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <select name="category"
                    class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All categories</option>
                    @foreach ($categories as $key => $label)
                        <option value="{{ $key }}" @selected(request('category') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="audience"
                    class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All audiences</option>
                    @foreach ($audiences as $key => $label)
                        <option value="{{ $key }}" @selected(request('audience') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Audience
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($modules as $module)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">{{ $module->title }}</span>
                                    <span class="text-xs text-gray-500">{{ $module->description }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $categories[$module->category] ?? ucfirst($module->category) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $audiences[$module->target_audience] ?? ucfirst($module->target_audience) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($module->delivery_mode) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $module->duration_minutes ? $module->duration_minutes . ' min' : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                @if ($module->resource_link)
                                    <a href="{{ $module->resource_link }}" target="_blank"
                                        class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                        Open
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('admin.training.destroy', $module) }}"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded"
                                        onclick="return confirm('Delete this training module?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No training modules found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $modules->links() }}
    </div>

    <!-- Add Training Modal -->
    <div id="training-modal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-xl">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Add Training Module</h3>
                <button class="text-gray-400 hover:text-gray-600"
                    onclick="this.closest('#training-modal').classList.add('hidden')">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.training.store') }}" enctype="multipart/form-data"
                class="px-4 py-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" required
                        class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Delivery Mode</label>
                        <input type="text" name="delivery_mode" value="self-paced"
                            class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                        <input type="number" name="duration_minutes" min="1"
                            class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Target Audience</label>
                        <select name="target_audience"
                            class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @foreach ($audiences as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Resource Link</label>
                    <input type="url" name="resource_link"
                        class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Attachment (optional)</label>
                    <input type="file" name="attachment"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg"
                        onclick="this.closest('#training-modal').classList.add('hidden')">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
