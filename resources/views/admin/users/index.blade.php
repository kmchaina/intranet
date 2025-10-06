@extends('layouts.dashboard')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                <p class="text-gray-600 mt-1">Manage user roles and organizational assignments</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors mr-3">
                    <i class="fas fa-plus mr-2"></i>
                    Create User
                </a>
                <a href="{{ route('admin.users.suggestions') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-lightbulb mr-2"></i>
                    Role Suggestions
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach ($roleCounts as $role => $count)
                <div class="bg-white rounded-lg p-4 shadow-card">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $count }}</div>
                        <div class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $role) }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg p-6 shadow-card">
            <form method="GET" action="{{ route('admin.users.index') }}"
                class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <!-- Search -->
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Users</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Search by name or email..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Role Filter -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Filter by Role</label>
                    <select name="role" id="role"
                        class="border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Roles</option>
                        <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin
                        </option>
                        <option value="hq_admin" {{ request('role') === 'hq_admin' ? 'selected' : '' }}>HQ Admin</option>
                        <option value="centre_admin" {{ request('role') === 'centre_admin' ? 'selected' : '' }}>Centre Admin
                        </option>
                        <option value="station_admin" {{ request('role') === 'station_admin' ? 'selected' : '' }}>Station
                            Admin</option>
                        <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <!-- Centre Filter -->
                <div>
                    <label for="centre_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Centre</label>
                    <select name="centre_id" id="centre_id"
                        class="border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Centres</option>
                        @foreach ($centres as $centre)
                            <option value="{{ $centre->id }}"
                                {{ request('centre_id') == $centre->id ? 'selected' : '' }}>
                                {{ $centre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-search mr-1"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-times mr-1"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div class="bg-white rounded-lg p-4 shadow-card" x-data="{ selectedUsers: [], showBulkActions: false }" x-init="$watch('selectedUsers', value => showBulkActions = value.length > 0)">
            <div x-show="showBulkActions" x-transition class="flex items-center justify-between">
                <span class="text-sm text-gray-600">
                    <span x-text="selectedUsers.length"></span> user(s) selected
                </span>
                <form method="POST" action="{{ route('admin.users.bulk-update') }}" class="flex items-center space-x-3"
                    x-on:submit="$event.target.querySelector('input[name=user_ids]').value = JSON.stringify(selectedUsers)">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="user_ids">
                    <select name="bulk_role" required
                        class="border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 text-sm">
                        <option value="">Select new role...</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="hq_admin">HQ Admin</option>
                        <option value="centre_admin">Centre Admin</option>
                        <option value="station_admin">Station Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                    <button type="submit"
                        class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm rounded-lg transition-colors">
                        <i class="fas fa-users-cog mr-1"></i>
                        Update Roles
                    </button>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox"
                                    x-on:change="selectedUsers = $event.target.checked ? {{ $users->pluck('id')->toJson() }} : []"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Organization</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <input type="checkbox" value="{{ $user->id }}" x-model="selectedUsers"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4A90E2&color=fff"
                                            alt="{{ $user->name }}">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $roleColors = [
                                            'super_admin' => 'bg-red-100 text-red-800',
                                            'hq_admin' => 'bg-purple-100 text-purple-800',
                                            'centre_admin' => 'bg-blue-100 text-blue-800',
                                            'station_admin' => 'bg-green-100 text-green-800',
                                            'staff' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ str_replace('_', ' ', ucwords($user->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if ($user->station)
                                        <div>{{ $user->station->name }}</div>
                                        @if ($user->centre)
                                            <div class="text-xs text-gray-500">{{ $user->centre->name }}</div>
                                        @endif
                                    @elseif($user->centre)
                                        <div>{{ $user->centre->name }}</div>
                                    @else
                                        <span class="text-gray-500">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->email_verified_at)
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                                    @else
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors">
                                                <i class="fas fa-trash mr-1"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                                    <p>No users found matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-submit filters on change
            document.addEventListener('DOMContentLoaded', function() {
                const filters = ['role', 'centre_id'];
                filters.forEach(filterId => {
                    const element = document.getElementById(filterId);
                    if (element) {
                        element.addEventListener('change', function() {
                            this.form.submit();
                        });
                    }
                });
            });
        </script>
    @endpush

@endsection
