<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Calendar Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">
                {{ \Carbon\Carbon::parse($date)->format('F Y') }}
            </h3>
            <div class="flex items-center space-x-2">
                <a href="{{ route('events.index', ['view' => 'calendar', 'date' => \Carbon\Carbon::parse($date)->subMonth()->toDateString()] + request()->except(['view', 'date'])) }}"
                   class="p-2 text-blue-200 hover:text-white hover:bg-white/20 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <a href="{{ route('events.index', ['view' => 'calendar', 'date' => now()->toDateString()] + request()->except(['view', 'date'])) }}"
                   class="px-4 py-2 text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 rounded-lg transition-colors duration-200">
                    Today
                </a>
                <a href="{{ route('events.index', ['view' => 'calendar', 'date' => \Carbon\Carbon::parse($date)->addMonth()->toDateString()] + request()->except(['view', 'date'])) }}"
                   class="p-2 text-blue-200 hover:text-white hover:bg-white/20 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="p-6">
        <!-- Day Labels -->
        <div class="grid grid-cols-7 gap-1 mb-4">
            @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                <div class="p-3 text-sm font-semibold text-gray-700 text-center bg-gray-50 rounded-lg">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        @php
            $currentDate = \Carbon\Carbon::parse($date);
            $startOfMonth = $currentDate->copy()->startOfMonth();
            $endOfMonth = $currentDate->copy()->endOfMonth();
            $startOfCalendar = $startOfMonth->copy()->startOfWeek();
            $endOfCalendar = $endOfMonth->copy()->endOfWeek();
            $calendarDays = [];
            $day = $startOfCalendar->copy();
            while ($day <= $endOfCalendar) {
                $calendarDays[] = $day->copy();
                $day->addDay();
            }

            // Group events by date - ensure $events is defined and is a collection
            $eventsByDate = collect();
            if (isset($events) && $events) {
                $eventsByDate = $events->groupBy(function ($event) {
                    return $event->start_datetime->toDateString();
                });
            }
        @endphp

        <div class="grid grid-cols-7 gap-1">
            @foreach ($calendarDays as $day)
                @php
                    $isCurrentMonth = $day->month === $currentDate->month;
                    $isToday = $day->isToday();
                    $isWeekend = $day->isWeekend();
                    $dayEvents = $eventsByDate->get($day->toDateString(), collect());
                    $hasEvents = $dayEvents->count() > 0;
                @endphp

                <div class="relative overflow-hidden min-h-32 p-2 border rounded-lg transition-all duration-200 hover:shadow-lg
                    {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-100' }}
                    {{ $isToday ? 'ring-4 ring-blue-500 bg-blue-200 border-blue-500' : 'border-gray-200' }}
                    {{ $hasEvents && $isCurrentMonth && !$isToday ? 'bg-blue-300 border-blue-500 shadow-md' : '' }}
                    {{ $hasEvents && !$isCurrentMonth ? 'bg-gray-300 border-gray-500' : '' }}">

                    @if($isWeekend && $isCurrentMonth && !$isToday)
                        <div class="absolute inset-0 bg-gray-50/70 pointer-events-none"></div>
                    @endif

                    <!-- Day Number -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm font-bold
                            {{ $isToday ? 'text-white bg-blue-600 rounded-full w-7 h-7 flex items-center justify-center text-xs' : '' }}
                            {{ !$isToday && $isCurrentMonth && $hasEvents ? 'text-blue-900' : '' }}
                            {{ !$isToday && $isCurrentMonth && !$hasEvents ? 'text-gray-900' : '' }}
                            {{ !$isToday && !$isCurrentMonth ? 'text-gray-500' : '' }}">
                            {{ $day->day }}
                        </div>

                        <!-- Event Count Badge -->
                        @if ($hasEvents && $isCurrentMonth)
                            <div class="bg-blue-800 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg border-2 border-white">
                                {{ $dayEvents->count() }}
                            </div>
                        @endif
                    </div>

                    <!-- Events -->
                    <div class="space-y-1 relative">
                        @foreach ($dayEvents->take(3) as $event)
                            <div class="group">
                                <a href="{{ route('events.show', $event) }}"
                                   class="block text-xs p-2 rounded-md font-medium transition-all duration-200 hover:scale-105 hover:shadow-lg
                                   @if($event->category === 'meeting') bg-blue-600 text-white
                                   @elseif($event->category === 'training') bg-green-600 text-white
                                   @elseif($event->category === 'conference') bg-purple-600 text-white
                                   @elseif($event->category === 'workshop') bg-orange-600 text-white
                                   @elseif($event->category === 'seminar') bg-yellow-600 text-white
                                   @elseif($event->category === 'fieldwork') bg-emerald-600 text-white
                                   @elseif($event->category === 'social') bg-pink-600 text-white
                                   @else bg-gray-600 text-white
                                   @endif
                                   {{ (($event->end_datetime ?? $event->start_datetime)->isPast()) ? ' line-through opacity-80' : '' }}"
                                   title="{{ $event->title }} - {{ $event->start_datetime->format('g:i A') }}">
                                    <div class="font-semibold">{{ Str::limit($event->title, 20) }}</div>
                                    <div class="text-xs opacity-90 mt-1">
                                        @if($event->all_day)
                                            All Day
                                        @else
                                            {{ $event->start_datetime->format('g:i A') }}
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach

                        @if ($dayEvents->count() > 3)
                            <div class="text-xs text-white font-bold bg-gray-800 rounded-md px-2 py-1 text-center hover:bg-gray-900 cursor-pointer shadow-lg border border-gray-600">
                                +{{ $dayEvents->count() - 3 }} more
                            </div>
                        @endif
                    </div>

                    <!-- Empty State for Event Days -->
                    @if ($hasEvents && $dayEvents->count() === 0)
                        <div class="flex items-center justify-center h-full">
                            <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Calendar Legend -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex flex-wrap items-center justify-center gap-6 text-sm">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-200 border border-blue-500 rounded"></div>
                    <span class="text-gray-700 font-medium">Today</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-300 border border-blue-500 rounded"></div>
                    <span class="text-gray-700 font-medium">Has Events</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-800 text-white text-xs rounded-full flex items-center justify-center font-bold">3</div>
                    <span class="text-gray-700 font-medium">Event Count</span>
                </div>
            </div>
        </div>
    </div>
</div>