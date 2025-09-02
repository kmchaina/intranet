@extends('layouts.dashboard')

@section('title', 'Password Vault')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900">My Passwords</h1>
                    <p class="text-lg text-gray-600 mt-1">Keep your passwords safe and organized</p>
                </div>
                <a href="{{ route('password-vault.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center text-lg font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Password
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-48">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search passwords..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select name="category"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}" {{ $category === $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($folders->count() > 0)
                    <div>
                        <select name="folder"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Folders</option>
                            @foreach ($folders as $folderName)
                                <option value="{{ $folderName }}" {{ $folder === $folderName ? 'selected' : '' }}>
                                    {{ $folderName }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Filter
                </button>
                @if ($search || $category || $folder)
                    <a href="{{ route('password-vault.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="p-6">
            @if ($passwords->count() > 0)
                <div class="space-y-3">
                    @foreach ($passwords as $password)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <!-- Category Color Dot -->
                                        <span
                                            class="w-3 h-3 rounded-full mr-3
                                            @if ($password->category === 'work') bg-blue-500
                                            @elseif($password->category === 'personal') bg-green-500
                                            @elseif($password->category === 'banking') bg-red-500
                                            @elseif($password->category === 'social') bg-purple-500
                                            @else bg-gray-400 @endif"></span>

                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $password->title }}</h3>
                                            @if ($password->username)
                                                <p class="text-sm text-gray-600">{{ $password->username }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <!-- Category Badge -->
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                        {{ $categories[$password->category] ?? $password->category }}
                                    </span>

                                    <!-- View Button with Eye Icon -->
                                    <a href="{{ route('password-vault.show', $password) }}"
                                        class="flex items-center bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm transition-colors"
                                        title="View password">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>

                                    <!-- Edit Button with Pencil Icon -->
                                    <a href="{{ route('password-vault.edit', $password) }}"
                                        class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm transition-colors"
                                        title="Edit password">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No passwords found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if ($search || $category || $folder)
                            No passwords match your current filters.
                        @else
                            Get started by adding your first password.
                        @endif
                    </p>
                    @if (!($search || $category || $folder))
                        <div class="mt-6">
                            <a href="{{ route('password-vault.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Password
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
