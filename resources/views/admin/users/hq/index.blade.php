@extends('layouts.dashboard')

@section('title', 'HQ Staff')

@section('page-title')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Headquarters Staff</h1>
            <p class="text-sm text-gray-500 mt-1">Manage staff assigned directly to HQ.</p>
        </div>
        <a href="{{ route('admin.hq.users.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition">Add
            staff</a>
    </div>
@endsection

@section('content')
    <div class="space-y-4">
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-700 text-sm rounded-lg">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="p-3 bg-red-100 text-red-700 text-sm rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Staff list</h2>
                <p class="text-xs text-gray-500 mt-1">Only HQ-level users appear here.</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.hq.users.edit', $user) }}"
                                class="px-3 py-1 text-xs font-semibold text-blue-600 hover:text-blue-700">Edit</a>
                            <form action="{{ route('admin.hq.users.destroy', $user) }}" method="POST"
                                onsubmit="return confirm('Delete this staff member?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 text-xs font-semibold text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-8 text-sm text-gray-500 text-center">No HQ staff yet. Add someone to get started.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
