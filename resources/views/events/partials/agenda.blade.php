<!-- Agenda View -->
<div class="space-y-6">
    <div class="text-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upcoming Events</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Your personalized event agenda</p>
    </div>

    @php
        $groupedEvents = $events->groupBy(function ($event) {
            return $event->start_datetime->format('Y-m-d');
        });
    @endphp

    @forelse($groupedEvents as $date => $dayEvents)
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Date Header -->
            <div class="bg-gray-50 dark:bg-gray-900 px-6 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                    </h4>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $dayEvents->count() }} event{{ $dayEvents->count() !== 1 ? 's' : '' }}
                    </span>
                </div>
            </div>

            <!-- Day Events -->
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($dayEvents->sortBy('start_datetime') as $event)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <div class="flex items-start space-x-4">
                            <!-- Time -->
                            <div class="flex-shrink-0 text-center">
                                @if ($event->all_day)
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">All</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Day</div>
                                @else
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $event->start_datetime->format('g:i') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $event->start_datetime->format('A') }}
                                    </div>
                                @endif
                            </div>

                            <!-- Event Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <h5 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('events.show', $event) }}"
                                            class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                            {{ $event->title }}
                                        </a>
                                    </h5>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $event->category_color }}">
                                        {{ ucfirst($event->category) }}
                                    </span>
                                    @if ($event->priority !== 'medium')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $event->priority_color }}">
                                            {{ ucfirst($event->priority) }}
                                        </span>
                                    @endif
                                </div>

                                <div
                                    class="flex flex-wrap items-center text-sm text-gray-500 dark:text-gray-400 space-x-4 mb-2">
                                    @if (!$event->all_day)
                                        <span>{{ $event->duration }}</span>
                                    @endif
                                    @if ($event->location)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $event->location }}
                                        </div>
                                    @endif
                                    @if ($event->requires_rsvp)
                                        <span>{{ $event->attendees_count }} attending</span>
                                    @endif
                                </div>

                                @if ($event->description)
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                                        {{ Str::limit($event->description, 120) }}
                                    </p>
                                @endif
                            </div>

                            <!-- RSVP Status -->
                            @if ($event->requires_rsvp)
                                <div class="flex-shrink-0">
                                    @php
                                        $userRsvp = $event->getRsvpStatusForUser(auth()->user());
                                    @endphp

                                    @if ($userRsvp)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                     {{ $userRsvp === 'attending' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                                     {{ $userRsvp === 'declined' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                                                     {{ $userRsvp === 'maybe' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}">
                                            {{ ucfirst($userRsvp) }}
                                        </span>
                                    @elseif($event->can_rsvp)
                                        <a href="{{ route('events.show', $event) }}"
                                            class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded transition-colors duration-200">
                                            RSVP
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-gray-400">RSVP Closed</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No upcoming events</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Check back later for new events or create one
                yourself.</p>
            <div class="mt-6">
                <a href="{{ route('events.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Event
                </a>
            </div>
        </div>
    @endforelse
</div>
