@extends('layouts.dashboard')

@section('title', 'Component Lab')
@section('page-title', 'Component Lab (Local Only)')

@section('content')
    <div class="space-y-6">
        <div class="p-6 bg-white rounded shadow">
            <h2 class="text-lg font-semibold">Dropdown Smoke Test</h2>
            <div class="mt-4">
                <x-dropdown>
                    <x-slot name="trigger">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Open Dropdown</button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="py-2">
                            <p class="px-4 py-2 text-sm text-gray-700">Item One</p>
                            <p class="px-4 py-2 text-sm text-gray-700">Item Two</p>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>

        <div class="p-6 bg-white rounded shadow">
            <h2 class="text-lg font-semibold">Layout Check</h2>
            <p class="text-sm text-gray-600">Verify sidebar, header and content area all render correctly on this page.</p>
        </div>
    </div>
@endsection
