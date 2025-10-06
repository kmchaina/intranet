<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $centre->name }} - Centre Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.centres.index') }}"
                    class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Centres
                </a>
            </div>

            <!-- Centre Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $centre->name }}</h1>
                            <p class="text-gray-600">{{ $centre->location }}</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.centres.edit', $centre) }}"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Edit Centre
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Centre Code</dt>
                                    <dd class="text-sm text-gray-900">{{ $centre->code }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd>
                                        @if ($centre->is_active)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                @if ($centre->description)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="text-sm text-gray-900">{{ $centre->description }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                            <dl class="space-y-3">
                                @if ($centre->contact_person)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Contact Person</dt>
                                        <dd class="text-sm text-gray-900">{{ $centre->contact_person }}</dd>
                                    </div>
                                @endif
                                @if ($centre->contact_email)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="text-sm text-gray-900">
                                            <a href="mailto:{{ $centre->contact_email }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                {{ $centre->contact_email }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                                @if ($centre->contact_phone)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                        <dd class="text-sm text-gray-900">
                                            <a href="tel:{{ $centre->contact_phone }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                {{ $centre->contact_phone }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $centre->users->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Stations</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $centre->stations->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Announcements</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $centre->users->sum(function ($user) {return $user->announcements()->count();}) }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Documents</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $centre->users->sum(function ($user) {return $user->documents()->count();}) }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Users in Centre -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Users in Centre</h3>

                        @if ($centre->users->count() > 0)
                            <div class="space-y-3">
                                @foreach ($centre->users as $user)
                                    <div
                                        class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            <div class="text-xs text-gray-400 capitalize">
                                                {{ str_replace('_', ' ', $user->role) }}</div>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            @if ($user->station)
                                                {{ $user->station->name }}
                                            @else
                                                No Station
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No users assigned to this centre</p>
                        @endif
                    </div>
                </div>

                <!-- Stations in Centre -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Stations in Centre</h3>

                        @if ($centre->stations->count() > 0)
                            <div class="space-y-3">
                                @foreach ($centre->stations as $station)
                                    <div
                                        class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $station->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $station->location ?? 'No location' }}</div>
                                            <div class="text-xs text-gray-400">
                                                {{ $station->users->count() }} users
                                            </div>
                                        </div>
                                        <div>
                                            @if ($station->is_active)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No stations in this centre</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

