@extends('layouts.dashboard')

@section('title', 'Staff Directory')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto p-6">
            <x-breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Staff Directory']]" />
            <x-page.header title="Staff Directory">
                <x-slot:actions>
                    @php($view = request('view', 'grid'))
                    <div class="inline-flex rounded-md shadow-sm" role="group">
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
                            class="px-3 py-1.5 text-sm border border-gray-300 first:rounded-l-md last:rounded-r-none {{ $view === 'grid' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">Grid</a>
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
                            class="px-3 py-1.5 text-sm border border-gray-300 -ml-px last:rounded-r-md {{ $view === 'list' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">List</a>
                    </div>
                </x-slot:actions>
                <x-slot:sub>
                    <form action="{{ route('staff.index') }}" method="GET" data-auto-submit
                        class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="md:col-span-2">
                            <input id="search" name="search" type="text" value="{{ request('search') }}"
                                placeholder="Search name or email"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label for="centre" class="sr-only">Centre</label>
                            <select id="centre" name="centre"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Centres</option>
                                @foreach ($centres as $centre)
                                    <option value="{{ $centre->id }}" @selected(request('centre') == $centre->id)>{{ $centre->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">Apply</button>
                            <a href="{{ route('staff.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Reset</a>
                        </div>
                    </form>
                </x-slot:sub>
            </x-page.header>

            @if ($staff->count() === 0)
                <div class="mt-6 rounded-lg border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">
                    No staff found. Try adjusting your filters.
                </div>
            @else
                @php($view = request('view', 'grid'))
                @if ($view === 'list')
                    <div class="mt-6 overflow-hidden rounded-lg shadow-sm ring-1 ring-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Phone</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location</th>
                                    <th
                                        class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($staff as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">
                                            <div class="flex items-center gap-3">
                                                @if ($user->profile_picture)
                                                    <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                                        alt="{{ $user->name }}"
                                                        class="w-8 h-8 rounded-full object-cover">
                                                @else
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">
                                                        {{ \Illuminate\Support\Str::of($user->name)->trim()->explode(' ')->map(fn($p) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($p, 0, 1)))->take(2)->implode('') }}
                                                    </div>
                                                @endif
                                                <a class="font-medium hover:text-blue-700"
                                                    href="{{ route('staff.show', $user) }}">{{ $user->name }}</a>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-600">{{ $user->email }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-600">{{ $user->phone ?? '—' }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            @if ($user->centre)
                                                {{ $user->centre->name }}
                                            @elseif($user->station)
                                                {{ $user->station->name }}
                                            @elseif($user->headquarters)
                                                {{ $user->headquarters->name }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm text-right">
                                            <a href="{{ route('staff.show', $user) }}"
                                                class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach ($staff as $user)
                            <a href="{{ route('staff.show', $user) }}"
                                class="group bg-white rounded-lg shadow-sm ring-1 ring-gray-200 hover:shadow-md transition p-4 flex flex-col items-center text-center">
                                @if ($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                                        class="w-16 h-16 rounded-full object-cover border-2 border-gray-100 mb-3">
                                @else
                                    <div
                                        class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-lg mb-3">
                                        {{ \Illuminate\Support\Str::of($user->name)->trim()->explode(' ')->map(fn($p) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($p, 0, 1)))->take(2)->implode('') }}
                                    </div>
                                @endif

                                <div class="w-full">
                                    <p class="font-medium text-gray-900 truncate group-hover:text-blue-700">
                                        {{ $user->name }}</p>

                                    <p class="text-sm text-gray-600 truncate mt-1">{{ $user->email }}</p>

                                    @if ($user->phone)
                                        <p class="text-sm text-gray-500 mt-1 flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $user->phone }}
                                        </p>
                                    @endif

                                    <div class="mt-2 flex flex-wrap justify-center gap-1 text-xs text-gray-600">
                                        @if ($user->centre)
                                            <span
                                                class="inline-flex items-center gap-1 bg-blue-50 px-2 py-0.5 rounded truncate max-w-full">
                                                <svg class="w-3 h-3 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z">
                                                    </path>
                                                </svg>
                                                {{ $user->centre->name }}
                                            </span>
                                        @endif
                                        @if ($user->station)
                                            <span
                                                class="inline-flex items-center gap-1 bg-green-50 px-2 py-0.5 rounded truncate max-w-full">
                                                <svg class="w-3 h-3 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                    </path>
                                                </svg>
                                                {{ $user->station->name }}
                                            </span>
                                        @endif
                                        @if (!$user->centre && !$user->station)
                                            <span class="inline-flex items-center gap-1 bg-purple-50 px-2 py-0.5 rounded">
                                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                    </path>
                                                </svg>
                                                Headquarters
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
