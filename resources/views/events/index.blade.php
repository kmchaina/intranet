@extends('layouts.dashboard')

@section('title', 'Upcoming Events')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto p-6">
            <x-breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Events']]" />

            <!-- Header Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                <div class="bg-blue-600 p-6 rounded-t-xl">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center border border-white/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Upcoming Events</h1>
                            <p class="text-blue-100 mt-1">Stay informed about NIMR events and activities</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Toggle and Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <!-- View Toggle -->
                    <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                        <a href="{{ route('events.index', ['view' => 'list'] + request()->except('view')) }}"
                            class="px-4 py-2 text-sm font-medium {{ $view === 'list' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            List View
                        </a>
                        <a href="{{ route('events.index', ['view' => 'calendar'] + request()->except('view')) }}"
                            class="px-4 py-2 text-sm font-medium border-l border-gray-300 {{ $view === 'calendar' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Calendar
                        </a>
                    </div>

                    <!-- Category Filter -->
                    <div class="flex items-center space-x-3">
                        <label for="category" class="text-sm font-medium text-gray-700">Filter by Category:</label>
                        <select name="category" id="category"
                            onchange="window.location.href = '{{ route('events.index') }}?{{ http_build_query(request()->except('category')) }}&category=' + this.value"
                            class="px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Categories</option>
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}" {{ $category === $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Events Content -->
            @if ($view === 'calendar')
                @include('events.partials.calendar', ['events' => $events, 'date' => $date])
            @else
                <!-- Events List View -->
                @if ($events->count() > 0)
                    <div class="space-y-4">
                        @foreach ($events as $event)
                            @php
                                $userRsvp = $event->rsvps->where('user_id', auth()->id())->first();
                            @endphp
                            <div
                                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                <div class="p-6">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                        <!-- Event Details -->
                                        <div class="flex-grow">
                                            <div class="flex items-start gap-4">
                                                <!-- Date Badge -->
                                                <div
                                                    class="flex-shrink-0 bg-blue-600 text-white rounded-lg p-3 text-center">
                                                    <div class="text-xs font-medium uppercase">
                                                        {{ $event->start_datetime->format('M') }}</div>
                                                    <div class="text-lg font-bold">
                                                        {{ $event->start_datetime->format('d') }}</div>
                                                </div>

                                                <!-- Event Info -->
                                                <div class="flex-grow">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                        {{ $event->title }}</h3>

                                                    <!-- Event Meta -->
                                                    <div
                                                        class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            {{ $event->start_datetime->format('M j, Y') }}
                                                        </div>
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            @if ($event->all_day)
                                                                All Day
                                                            @else
                                                                {{ $event->start_datetime->format('g:i A') }}
                                                                @if ($event->end_datetime && $event->end_datetime != $event->start_datetime)
                                                                    - {{ $event->end_datetime->format('g:i A') }}
                                                                @endif
                                                            @endif
                                                        </div>
                                                        @if ($event->location)
                                                            <div class="flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                                    </path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                </svg>
                                                                {{ $event->location }}
                                                            </div>
                                                        @endif
                                                        @if ($event->category)
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                {{ $categories[$event->category] ?? $event->category }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    @if ($event->description)
                                                        <p class="text-gray-700 text-sm leading-relaxed">
                                                            {{ Str::limit($event->description, 150) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <a href="{{ route('events.show', $event) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                View Details
                                            </a>

                                            <!-- RSVP Button with Status -->
                                            @if ($event->requires_rsvp)
                                                @if ($userRsvp)
                                                    @if ($userRsvp->status === 'attending')
                                                        <span
                                                            class="inline-flex items-center justify-center px-4 py-2 bg-green-100 text-green-700 text-sm font-medium rounded-lg">
                                                            <svg class="w-4 h-4 mr-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                            Attending
                                                        </span>
                                                    @elseif ($userRsvp->status === 'maybe')
                                                        <span
                                                            class="inline-flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-lg">
                                                            <svg class="w-4 h-4 mr-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Maybe
                                                        </span>
                                                    @endif
                                                @else
                                                    <a href="{{ route('events.show', $event) }}"
                                                        class="inline-flex items-center justify-center px-4 py-2 bg-green-50 hover:bg-green-100 text-green-700 text-sm font-medium rounded-lg transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        RSVP
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($events->hasPages())
                        <div class="mt-8">
                            {{ $events->links() }}
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No upcoming events</h3>
                            <p class="text-gray-600">There are currently no upcoming events scheduled. Check back later for
                                updates.</p>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
