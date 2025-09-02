<!-- Calendar View -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <!-- Calendar Header -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ \Carbon\Carbon::parse($date)->format('F Y') }}
            </h3>
            <div class="flex space-x-2">
                <a href="{{ route('events.index', ['view' => 'calendar', 'date' => \Carbon\Carbon::parse($date)->subMonth()->toDateString()] + request()->except(['view', 'date'])) }}"
                    class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <a href="{{ route('events.index', ['view' => 'calendar', 'date' => now()->toDateString()] + request()->except(['view', 'date'])) }}"
                    class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                    Today
                </a>
                <a href="{{ route('events.index', ['view' => 'calendar', 'date' => \Carbon\Carbon::parse($date)->addMonth()->toDateString()] + request()->except(['view', 'date'])) }}"
                    class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="p-4">
        <!-- Day Labels -->
        <div class="grid grid-cols-7 gap-1 mb-2">
            @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="p-2 text-xs font-medium text-gray-500 dark:text-gray-400 text-center">
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

            // Group events by date
            $eventsByDate = $events->groupBy(function ($event) {
                return $event->start_datetime->toDateString();
            });
        @endphp

        <div class="grid grid-cols-7 gap-1">
            @foreach ($calendarDays as $day)
                @php
                    $isCurrentMonth = $day->month === $currentDate->month;
                    $isToday = $day->isToday();
                    $dayEvents = $eventsByDate->get($day->toDateString(), collect());
                @endphp

                <div
                    class="min-h-24 p-1 border border-gray-100 dark:border-gray-700 rounded 
                            {{ $isCurrentMonth ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-900' }}
                            {{ $isToday ? 'ring-2 ring-indigo-500' : '' }}">

                    <!-- Day Number -->
                    <div
                        class="text-xs font-medium mb-1 
                                {{ $isCurrentMonth ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-600' }}
                                {{ $isToday ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                        {{ $day->day }}
                    </div>

                    <!-- Events -->
                    @foreach ($dayEvents->take(3) as $event)
                        <div class="mb-1">
                            <a href="{{ route('events.show', $event) }}"
                                class="block text-xs p-1 rounded text-white truncate {{ $event->category_color }}"
                                title="{{ $event->title }} - {{ $event->start_datetime->format('g:i A') }}">
                                {{ $event->title }}
                            </a>
                        </div>
                    @endforeach

                    @if ($dayEvents->count() > 3)
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            +{{ $dayEvents->count() - 3 }} more
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
