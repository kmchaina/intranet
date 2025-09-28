@extends('layouts.dashboard')

@section('title', $staff->name)

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Staff Directory', 'href' => route('staff.index')],
            ['label' => $staff->name],
        ]" />
        <x-page.header :title="$staff->name">
            <x-slot:sub>
                <div class="text-sm text-gray-600">{{ $staff->email }}</div>
            </x-slot:sub>
        </x-page.header>

        <div class="mt-6 bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="text-gray-500">Role</dt>
                    <dd class="text-gray-900">{{ $staff->role ? \Illuminate\Support\Str::title(str_replace('_', ' ', $staff->role)) : '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Headquarters</dt>
                    <dd class="text-gray-900">{{ $staff->headquarters->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Centre</dt>
                    <dd class="text-gray-900">{{ $staff->centre->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Station</dt>
                    <dd class="text-gray-900">{{ $staff->station->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Department</dt>
                    <dd class="text-gray-900">{{ $staff->department->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Joined</dt>
                    <dd class="text-gray-900">{{ optional($staff->hire_date)->toFormattedDateString() ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-6">
            <a href="{{ route('staff.index') }}" class="text-sm text-indigo-700 hover:text-indigo-900">← Back to directory</a>
        </div>
    </div>
@endsection
