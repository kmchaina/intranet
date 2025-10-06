@extends('layouts.dashboard')
@section('title', $station->name . ' Station')

@section('page-title')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $station->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Station details and team overview.</p>
        </div>
        <a href="{{ route('admin.stations.index') }}"
            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Stations
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-6 py-5 border-b border-gray-100 flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Station summary</h2>
                    <p class="text-sm text-gray-500">{{ $station->location ?? 'No location set' }}</p>
                </div>
                <a href="{{ route('admin.stations.edit', $station) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm">Edit</a>
            </div>
            <div class="px-6 py-5 grid gap-6 md:grid-cols-2">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Station code</dt>
                        <dd class="text-sm text-gray-900">{{ $station->code ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Centre</dt>
                        <dd class="text-sm text-gray-900">{{ $station->centre?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Centre location</dt>
                        <dd class="text-sm text-gray-900">{{ $station->centre?->location ?? '—' }}</dd>
                    </div>
                </dl>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</dt>
                        <dd>
                            @if ($station->is_active)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Inactive</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Contact</dt>
                        <dd class="text-sm text-gray-900">{{ $station->contact_person ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email / Phone</dt>
                        <dd class="text-sm text-gray-900">
                            <div>{{ $station->contact_email ?? '—' }}</div>
                            <div>{{ $station->contact_phone ?? '—' }}</div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total users</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $station->users->count() }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Verified</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ $station->users->whereNotNull('email_verified_at')->count() }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Admin team</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ $station->users->whereIn('role', ['station_admin'])->count() }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-900">Station staff</h2>
            </div>
            <div class="px-6 py-5 space-y-3">
                @forelse ($station->users as $user)
                    <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                        <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $user->role) }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No staff assigned to this station yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
