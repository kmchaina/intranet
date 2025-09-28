@extends('layouts.dashboard')

@section('title', 'Staff Directory')

@section('content')
    <div class="max-w-7xl mx-auto p-6">
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Staff Directory'],
        ]" />
        <x-page.header title="Staff Directory">
            <x-slot:actions>
                @php($view = request('view', 'grid'))
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="px-3 py-1.5 text-sm border border-gray-300 first:rounded-l-md last:rounded-r-none {{ $view==='grid' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">Grid</a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="px-3 py-1.5 text-sm border border-gray-300 -ml-px last:rounded-r-md {{ $view==='list' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">List</a>
                </div>
            </x-slot:actions>
            <x-slot:sub>
                <form action="{{ route('staff.index') }}" method="GET" data-auto-submit class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="md:col-span-2">
                        <input id="search" name="search" type="text" value="{{ request('search') }}" placeholder="Search name or email" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label for="centre" class="sr-only">Centre</label>
                        <select id="centre" name="centre" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Centres</option>
                            @foreach($centres as $centre)
                                <option value="{{ $centre->id }}" @selected(request('centre') == $centre->id)>{{ $centre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">Apply</button>
                        <a href="{{ route('staff.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </div>
                </form>
            </x-slot:sub>
        </x-page.header>

        @if($staff->count() === 0)
            <div class="mt-6 rounded-lg border border-dashed border-gray-300 p-8 text-center text-sm text-gray-600">
                No staff found. Try adjusting your filters.
            </div>
        @else
            @php($view = request('view', 'grid'))
            @if($view === 'list')
                <div class="mt-6 overflow-hidden rounded-lg shadow-sm ring-1 ring-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Centre</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Station</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($staff as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        <a class="font-medium hover:text-indigo-700" href="{{ route('staff.show', $user) }}">{{ $user->name }}</a>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user->role ? \Illuminate\Support\Str::title(str_replace('_', ' ', $user->role)) : '—' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user->centre->name ?? '—' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $user->station->name ?? '—' }}</td>
                                    <td class="px-4 py-2 text-sm text-right">
                                        <a href="{{ route('staff.show', $user) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($staff as $user)
                        <a href="{{ route('staff.show', $user) }}" class="group bg-white rounded-lg shadow-sm ring-1 ring-gray-200 hover:shadow-md transition p-4 flex items-start gap-3">
                            <div class="shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-700 flex items-center justify-center font-semibold">
                                {{ \Illuminate\Support\Str::of($user->name)->trim()->explode(' ')->map(fn($p)=>\Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($p,0,1)))->take(2)->implode('') }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-gray-900 truncate group-hover:text-indigo-700">{{ $user->name }}</p>
                                    @if($user->role)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $user->role)) }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 truncate">{{ $user->email }}</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-gray-600">
                                    @if($user->headquarters)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7V3m0 0L8 7m4-4l4 4m0 0v14M8 21h8"></path></svg>
                                            {{ $user->headquarters->name }}
                                        </span>
                                    @endif
                                    @if($user->centre)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.5l6 4.5-6 4.5-6-4.5 6-4.5z"></path></svg>
                                            {{ $user->centre->name }}
                                        </span>
                                    @endif
                                    @if($user->station)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4"></path></svg>
                                            {{ $user->station->name }}
                                        </span>
                                    @endif
                                    @if($user->department)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $user->department->name }}
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
@endsection
