@extends('layouts.dashboard')

@section('title', 'Station Staff')

@section('page-title')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Station Staff</h1>
            <p class="text-sm text-gray-500 mt-1">Manage staff assigned to your station.</p>
        </div>
        <a href="{{ route('admin.station.users.create') }}"
            class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg shadow-sm transition">Add
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
                <h2 class="text-sm font-semibold text-gray-900">Station staff list</h2>
                <p class="text-xs text-gray-500 mt-1">Only staff from your station appear here.</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($stationStaff as $user)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.station.users.edit', $user) }}"
                                class="px-3 py-1 text-xs font-semibold text-orange-600 hover:text-orange-700">Edit</a>
                            <form action="{{ route('admin.station.users.destroy', $user) }}" method="POST"
                                onsubmit="return confirm('Remove this staff member?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 text-xs font-semibold text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-8 text-sm text-gray-500 text-center">No station staff yet. Add someone to get started.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
