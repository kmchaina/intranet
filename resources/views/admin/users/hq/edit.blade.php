@extends('layouts.dashboard')

@section('title', 'Edit HQ Staff')

@section('page-title')
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Edit Headquarters Staff</h1>
        <p class="text-sm text-gray-500 mt-1">Update profile details for {{ $user->name }}.</p>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm px-6 py-6">
            <form method="POST" action="{{ route('admin.hq.users.update', $user) }}" class="space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone (optional)</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                        @error('phone')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Employee ID (optional)</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                        @error('employee_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                        @error('password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.hq.users.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow">Save
                        changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
