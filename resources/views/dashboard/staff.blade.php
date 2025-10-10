@extends('layouts.dashboard')
@section('title', 'Dashboard')

@section('page-title')
    <div class="flex items-center justify-between animate-fade-in">
        <div>
            <p class="text-lg text-nimr-neutral-700 font-medium">{{ now()->format('l, F j, Y') }} •
                {{ now()->format('g:i A') }}</p>
        </div>

        @if (($todaysBirthdays->count() ?? 0) > 0 || ($todaysAnniversaries->count() ?? 0) > 0)
            <a href="{{ route('birthdays.index') }}"
                class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 hover:shadow-lg transition-all duration-200 group"
                style="border-color: rgba(38, 100, 235, 0.3); background-color: rgba(38, 100, 235, 0.05);">
                <div class="flex items-center gap-3">
                    <span class="text-2xl group-hover:scale-110 transition-transform">🎂</span>
                    <div>
                        <p class="text-sm font-semibold" style="color: #2664eb;">
                            @if ($todaysBirthdays->count() > 0 && $todaysAnniversaries->count() > 0)
                                {{ $todaysBirthdays->count() + $todaysAnniversaries->count() }} Celebrations Today!
                            @elseif($todaysBirthdays->count() > 0)
                                {{ $todaysBirthdays->count() }} Birthday{{ $todaysBirthdays->count() > 1 ? 's' : '' }}
                                Today!
                            @else
                                {{ $todaysAnniversaries->count() }} Work Anniversary Today!
                            @endif
                        </p>
                        <p class="text-xs" style="color: #2664eb;">Click to send your wishes →</p>
                    </div>
                </div>
            </a>
        @endif
    </div>
@endsection

@section('content')
    <div class="flex flex-col lg:flex-row gap-6 w-full">
        {{-- Main Content Area --}}
        <div class="flex-1 space-y-8 min-w-0">

            {{-- Top Row: Welcome Card + Quick Links + Birthdays --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Welcome Card --}}
                <div class="card-premium overflow-hidden relative bg-white border border-gray-200 min-h-[280px]">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none">
                        <img src="{{ asset('images/svg/people.svg') }}" alt="Welcome"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="relative z-10 p-6 h-full flex items-start justify-end">
                        @php
                            $firstName = explode(' ', auth()->user()->name)[0];
                        @endphp
                        <div class="text-right">
                            <h2 class="text-4xl lg:text-5xl font-extrabold leading-tight" style="color: #2664eb;">
                                Welcome<br>{{ $firstName }} 👋
                            </h2>
                        </div>
                    </div>
                </div>

                {{-- Quick Links Card (Actual Links) --}}
                <div class="card-premium overflow-hidden h-full">
                    <div class="p-6 bg-blue-50 border-b border-blue-200"
                        style="background-color: rgba(38, 100, 235, 0.05);">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-lg"
                                    style="background-color: #2664eb;">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-nimr-neutral-900">Quick Links</h3>
                                    <p class="text-xs text-nimr-neutral-600">Fast access</p>
                                </div>
                            </div>
                            <a href="{{ route('system-links.index') }}"
                                class="text-sm font-semibold flex items-center gap-1" style="color: #2664eb;">
                                View all
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="p-4">
                        @if (isset($quickAccessLinks) && $quickAccessLinks->count())
                            <div class="space-y-2">
                                @foreach ($quickAccessLinks->take(4) as $link)
                                    @php $colorClasses = $link->getColorClasses(); @endphp
                                    <a href="{{ $link->url }}" target="{{ $link->opens_new_tab ? '_blank' : '_self' }}"
                                        onclick="trackLinkClick({{ $link->id }})"
                                        class="flex items-center gap-3 p-3 rounded-lg border border-nimr-neutral-200 hover:bg-blue-50 transition-all duration-200 group"
                                        style="hover:border-color: rgba(38, 100, 235, 0.3);">
                                        <div
                                            class="w-10 h-10 {{ $colorClasses[0] }} rounded-lg flex items-center justify-center flex-shrink-0">
                                            @if ($link->icon)
                                                @if (str_starts_with($link->icon, 'fa'))
                                                    <i class="{{ $link->icon }} {{ $colorClasses[1] }} text-sm"></i>
                                                @else
                                                    <span class="text-lg">{{ $link->icon }}</span>
                                                @endif
                                            @else
                                                <svg class="w-5 h-5 text-nimr-neutral-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="font-semibold text-nimr-neutral-900 group-hover:text-indigo-700 truncate text-sm">
                                                {{ $link->title }}
                                            </p>
                                        </div>
                                        @if ($link->opens_new_tab)
                                            <svg class="w-4 h-4 text-nimr-neutral-400 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-sm text-nimr-neutral-500">No quick links available</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Today's Birthdays Card --}}
                <div class="card-premium overflow-hidden h-full">
                    <div class="p-6 border-b border-indigo-100" style="background-color: rgba(37, 99, 235, 0.1);">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg flex items-center justify-center shadow-lg" style="background-color: #2563eb;">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-nimr-neutral-900">🎂 Birthdays</h3>
                                    <p class="text-xs text-nimr-neutral-600">Today's celebrations</p>
                                </div>
                            </div>
                            <a href="{{ route('birthdays.index') }}"
                                class="text-sm font-semibold flex items-center gap-1" style="color: #2563eb;">
                                View all
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="p-4">
                        @if (($todaysBirthdays->count() ?? 0) > 0)
                            <div class="space-y-2">
                                @foreach ($todaysBirthdays->take(4) as $user)
                                    <a href="{{ route('birthdays.wishes', $user) }}"
                                        class="flex items-center gap-3 p-3 rounded-lg border border-nimr-neutral-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200 group">
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0 group-hover:scale-110 transition-transform shadow-md" style="background-color: #2563eb;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="font-semibold text-nimr-neutral-900 group-hover:text-indigo-700 truncate text-sm">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-xs text-nimr-neutral-600">
                                                Happy Birthday! 🎉
                                            </p>
                                        </div>
                                        <svg class="w-4 h-4 text-indigo-400 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-4xl mb-2">🎂</p>
                                <p class="text-sm text-nimr-neutral-500 mb-2">No birthdays today</p>
                                @if (!auth()->user()->birth_date)
                                    <div
                                        class="border border-indigo-200 rounded-lg p-4 mb-4" style="background-color: rgba(37, 99, 235, 0.1);">
                                        <p class="text-sm text-indigo-700 font-medium mb-2">🎉 Want us to celebrate your
                                            birthday?</p>
                                        <p class="text-xs text-indigo-600 mb-3">Add your birth date so we can celebrate
                                            with you!</p>
                                        <a href="{{ route('birthdays.index') }}"
                                            class="inline-flex items-center px-3 py-1.5 text-white text-xs font-medium rounded-md transition-all duration-200" style="background-color: #2563eb;" onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">
                                            Add My Birthday
                                        </a>
                                    </div>
                                @endif
                                <a href="{{ route('birthdays.index') }}"
                                    class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-all duration-200" style="background-color: #2563eb;" onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">
                                    View All Birthdays
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <script>
                function trackLinkClick(linkId) {
                    fetch(`/system-links/${linkId}/increment-click`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    }).catch(console.error);
                }
            </script>

            {{-- Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Latest Announcements --}}
                <div class="card-premium overflow-hidden">
                    <div class="p-6 border-b border-indigo-100" style="background-color: rgba(37, 99, 235, 0.1);">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg flex items-center justify-center shadow-lg" style="background-color: #2563eb;">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-nimr-neutral-900">Latest Announcements</h3>
                                    <p class="text-xs text-nimr-neutral-600">Stay updated with news</p>
                                </div>
                            </div>
                            <a href="{{ route('announcements.index') }}"
                                class="text-sm font-semibold flex items-center gap-1" style="color: #2563eb;">
                                View all
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        @if (isset($recentAnnouncements) && $recentAnnouncements->count())
                            <div class="space-y-4">
                                @foreach ($recentAnnouncements->take(4) as $announcement)
                                    <a href="{{ route('announcements.show', $announcement) }}"
                                        class="block p-4 rounded-xl border border-nimr-neutral-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200 hover:shadow-md group">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="w-2 h-2 rounded-full mt-2 group-hover:scale-125 transition-transform" style="background-color: #2563eb;">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="font-semibold text-nimr-neutral-900 group-hover:text-indigo-700 line-clamp-2">
                                                    {{ $announcement->title }}</p>
                                                <div class="flex items-center gap-2 mt-2 text-xs text-nimr-neutral-500">
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        {{ $announcement->creator->name ?? 'Unknown' }}
                                                    </span>
                                                    <span class="text-nimr-neutral-300">•</span>
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $announcement->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state py-8">
                                <div class="empty-state-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <p class="empty-state-title">No announcements yet</p>
                                <p class="empty-state-description">Check back later for important updates and news</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Upcoming Events --}}
                <div class="card-premium overflow-hidden">
                    <div class="p-6 border-b border-indigo-100" style="background-color: rgba(37, 99, 235, 0.1);">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg flex items-center justify-center shadow-lg" style="background-color: #2563eb;">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 012-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-nimr-neutral-900">Upcoming Events</h3>
                                    <p class="text-xs text-nimr-neutral-600">Don't miss what's happening</p>
                                </div>
                            </div>
                            <a href="{{ route('events.index') }}"
                                class="text-sm font-semibold flex items-center gap-1" style="color: #2563eb;">
                                View all
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        @if (isset($upcomingEvents) && $upcomingEvents->count())
                            <div class="space-y-4">
                                @foreach ($upcomingEvents->take(5) as $event)
                                    <div
                                        class="flex items-start gap-4 p-4 rounded-xl border border-nimr-neutral-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200 hover:shadow-md group">
                                        <div
                                            class="w-14 h-14 rounded-xl flex flex-col items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform" style="background-color: rgba(37, 99, 235, 0.1);">
                                            <span
                                                class="text-xs font-semibold uppercase" style="color: #2563eb;">{{ $event->start_datetime->format('M') }}</span>
                                            <span
                                                class="text-lg font-bold" style="color: #1d4ed8;">{{ $event->start_datetime->format('d') }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="font-semibold text-nimr-neutral-900 group-hover:text-indigo-700 truncate">
                                                {{ $event->title }}</p>
                                            <div class="flex items-center gap-2 mt-1 text-xs text-nimr-neutral-600">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $event->start_datetime->format('g:i A') }}
                                                </span>
                                                @if ($event->location)
                                                    <span class="text-nimr-neutral-300">•</span>
                                                    <span class="inline-flex items-center gap-1 truncate">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        {{ $event->location }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <a href="{{ route('events.show', $event) }}"
                                            class="btn btn-sm flex-shrink-0" style="background-color: #2563eb; color: white; border: 1px solid #2563eb;">
                                            View
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state py-8">
                                <div class="empty-state-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 012-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="empty-state-title">No upcoming events</p>
                                <p class="empty-state-description">We'll notify you when new events are scheduled</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
        {{-- End Main Content Area --}}

        {{-- Static Quick Access Side Panel --}}
        <aside class="w-64 flex-shrink-0 hidden lg:block">
            <div class="space-y-3">
                {{-- Panel Header --}}
                <div class="text-white p-4 rounded-xl shadow-lg" style="background-color: #2563eb;">
                    <h3 class="text-sm font-bold">⚡ Quick Access</h3>
                    <p class="text-xs text-white/80 mt-1">Your shortcuts</p>
                </div>

                {{-- Panel Links --}}
                <div class="space-y-1.5">
                    {{-- Documents --}}
                    <a href="{{ route('documents.index') }}"
                        class="flex items-center gap-2.5 p-2.5 rounded-lg border border-nimr-neutral-200 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm" style="background-color: #3b82f6;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span
                            class="text-xs font-semibold text-nimr-neutral-900 group-hover:text-indigo-700">Documents</span>
                    </a>

                    {{-- Announcements --}}
                    <a href="{{ route('announcements.index') }}"
                        class="flex items-center gap-2.5 p-2.5 rounded-lg border border-nimr-neutral-200 hover:border-purple-400 hover:bg-purple-50 transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm" style="background-color: #8b5cf6;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <span
                            class="text-xs font-semibold text-nimr-neutral-900 group-hover:text-purple-700">Announcements</span>
                    </a>

                    {{-- News --}}
                    <a href="{{ route('news.index') }}"
                        class="flex items-center gap-2.5 p-2.5 rounded-lg border border-nimr-neutral-200 hover:border-pink-400 hover:bg-pink-50 transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm" style="background-color: #ec4899;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-nimr-neutral-900 group-hover:text-pink-700">News</span>
                    </a>

                    {{-- Events --}}
                    <a href="{{ route('events.index') }}"
                        class="flex items-center gap-2.5 p-2.5 rounded-lg border border-nimr-neutral-200 hover:border-green-400 hover:bg-green-50 transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm" style="background-color: #10b981;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 012-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-nimr-neutral-900 group-hover:text-green-700">Events</span>
                    </a>

                    {{-- Polls --}}
                    <a href="{{ route('polls.index') }}"
                        class="flex items-center gap-2.5 p-2.5 rounded-lg border border-nimr-neutral-200 hover:border-yellow-400 hover:bg-yellow-50 transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm" style="background-color: #f59e0b;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-nimr-neutral-900 group-hover:text-yellow-700">Polls</span>
                    </a>

                    {{-- Messages --}}
                    <a href="{{ route('messages.index') }}"
                        class="flex items-center gap-2.5 p-2.5 rounded-lg border border-nimr-neutral-200 hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm" style="background-color: #6366f1;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <span
                            class="text-xs font-semibold text-nimr-neutral-900 group-hover:text-indigo-700">Messages</span>
                    </a>

                    {{-- Feedback --}}
                    <a href="{{ route('feedback.index') }}"
                        class="flex items-center gap-2.5 p-2.5 rounded-lg border border-nimr-neutral-200 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm" style="background-color: #0ea5e9;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-nimr-neutral-900 group-hover:text-blue-700">Feedback</span>
                    </a>

                    {{-- System Links --}}
                    <a href="{{ route('system-links.index') }}"
                        class="flex items-center gap-2.5 p-2.5 rounded-lg border border-nimr-neutral-200 hover:border-teal-400 hover:bg-teal-50 transition-all duration-200 group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm" style="background-color: #14b8a6;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-nimr-neutral-900 group-hover:text-teal-700">System
                            Links</span>
                    </a>
                </div>

                {{-- Online People Section --}}
                <div class="border border-green-200 p-4 rounded-xl mt-3" style="background-color: rgba(37, 99, 235, 0.05);">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <h3 class="text-xs font-bold text-nimr-neutral-900">Online Now</h3>
                    </div>

                    <div class="space-y-2">
                        @forelse($onlineUsers as $onlineUser)
                            <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-white/50 transition-colors">
                                <div class="relative flex-shrink-0">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm" style="background-color: #2563eb;">
                                        {{ substr($onlineUser->name, 0, 1) }}
                                    </div>
                                    <div
                                        class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-nimr-neutral-900 truncate">
                                        {{ $onlineUser->name }}</p>
                                    <p class="text-[10px] text-nimr-neutral-500 truncate">
                                        {{ ucfirst(str_replace('_', ' ', $onlineUser->role)) }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <svg class="w-8 h-8 text-nimr-neutral-300 mx-auto mb-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-xs text-nimr-neutral-500">No one else online</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </aside>
        {{-- End Static Quick Access Side Panel --}}

    </div>

    @push('scripts')
        <script>
            function trackLinkClick(linkId) {
                fetch(`/system-links/${linkId}/increment-click`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                }).catch(error => console.error('Error tracking click:', error));
            }
        </script>
    @endpush
@endsection
