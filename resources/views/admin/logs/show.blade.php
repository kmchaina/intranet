@extends('layouts.dashboard')

@section('title', 'View Log')
@section('page-title', 'Log Viewer')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $file }}</h2>
                <p class="text-sm text-gray-600">Showing last 500 lines (newest last).</p>
            </div>
            <a href="{{ route('admin.logs.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded">
                Back to Logs
            </a>
        </div>

        <div class="bg-black text-green-200 rounded-lg shadow overflow-auto" style="max-height: 600px;">
            <pre class="p-4 text-sm leading-relaxed">{{ $content }}</pre>
        </div>
    </div>
@endsection
