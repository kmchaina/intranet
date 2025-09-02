@extends('layouts.dashboard')

@section('title', 'Events & Calendar')
@section('page-title', 'Events & Calendar')
@section('page-subtitle', 'Discover and manage NIMR events and activities')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Create Event Button -->
                <div class="flex justify-end mb-6">
                    <a href="{{ route('events.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Event
                    </a>
                </div>

                <!-- View Toggle and Filters -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div class="flex border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                        <a href="{{ route('events.index', ['view' => 'calendar'] + request()->except('view')) }}"
                            class="px-4 py-2 text-sm font-medium {{ $view === 'calendar' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            Calendar
                        </a>
                        <a href="{{ route('events.index', ['view' => 'list'] + request()->except('view')) }}"
                            class="px-4 py-2 text-sm font-medium border-l border-gray-300 dark:border-gray-600 {{ $view === 'list' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            List
                        </a>
                        <a href="{{ route('events.index', ['view' => 'agenda'] + request()->except('view')) }}"
                            class="px-4 py-2 text-sm font-medium border-l border-gray-300 dark:border-gray-600 {{ $view === 'agenda' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            Agenda
                        </a>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Category Filter -->
                        <select name="category"
                            onchange="window.location.href = '{{ route('events.index') }}?{{ http_build_query(request()->except('category')) }}&category=' + this.value"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                            <option value="">All Categories</option>
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}" {{ $category === $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Events Content -->
                @if ($view === 'calendar')
                    @include('events.partials.calendar', ['events' => $events, 'date' => $date])
                @elseif($view === 'agenda')
                    @include('events.partials.agenda', ['events' => $events])
                @else
                    @include('events.partials.list', ['events' => $events])
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Calendar navigation and event filtering
            document.addEventListener('DOMContentLoaded', function() {
                // Add any JavaScript for calendar interaction here
            });
        </script>
    @endpush
@endsection
