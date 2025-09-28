@extends('layouts.dashboard')
@section('title')
@endsection
@extends('layouts.dashboard')
@section('title', 'Staff Dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Main (3/4) -->
    <div class="lg:col-span-3 space-y-8">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                    <p class="text-sm text-gray-600">Common tasks you may need</p>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                    <a href="{{ route('documents.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Documents</span>
                    </a>
                    <a href="{{ route('announcements.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Announcements</span>
                    </a>
                    <a href="{{ route('news.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-800">News</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50 transition-colors">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Events</span>
                    </a>
                    <a href="{{ route('polls.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-amber-300 hover:bg-amber-50 transition-colors">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5v8m8 0V9a2 2 0 012-2h2v12M5 21h14"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Polls</span>
                    </a>
                    <a href="{{ route('password-vault.index') }}" class="group flex flex-col items-center p-4 rounded-lg border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Password Vault</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Announcements and Events -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Latest Announcements -->
            <div class="nimr-card">
                <div class="nimr-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="nimr-icon-primary">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Latest Announcements</h3>
                    </div>
                    <a href="{{ route('announcements.index') }}" class="nimr-link">View all</a>
                </div>
                <div class="nimr-card-body">
                    @if(isset($recentAnnouncements) && $recentAnnouncements->count())
                        <div class="space-y-4">
                            @foreach($recentAnnouncements->take(4) as $a)
                                <a href="{{ route('announcements.show', $a) }}" class="block p-4 rounded-xl border border-gray-100 hover:bg-gray-50 hover:border-gray-200 transition-colors">
                                    <p class="font-semibold text-gray-900 line-clamp-2">{{ $a->title }}</p>
                                    <div class="flex items-center mt-2 text-xs text-gray-500">
                                        <span>{{ $a->creator->name }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $a->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-6">No announcements yet</p>
                    @endif
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="nimr-card">
                <div class="nimr-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="nimr-icon-secondary">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Upcoming Events</h3>
                    </div>
                    <a href="{{ route('events.index') }}" class="nimr-link">View all</a>
                </div>
                <div class="nimr-card-body divide-y divide-gray-100">
                    @if(isset($upcomingEvents) && $upcomingEvents->count())
                        @foreach($upcomingEvents->take(4) as $event)
                            <div class="py-3 flex items-start space-x-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex flex-col items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">{{ $event->start_datetime->format('M') }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $event->start_datetime->format('d') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $event->title }}</p>
                                    <p class="text-xs text-gray-600">{{ $event->location ?? 'Location TBD' }} • {{ $event->start_datetime->format('g:i A') }}</p>
                                </div>
                                <a href="{{ route('events.show', $event) }}" class="text-xs text-blue-600 hover:text-blue-700">View</a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500 text-center py-6">No upcoming events</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar (1/4) -->
    <div class="space-y-6">
        <!-- My Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">My Tasks</h3>
                <a href="{{ route('todos.index') }}" class="text-xs text-blue-600 hover:text-blue-700">Open</a>
            </div>
            <div class="p-4">
                @if(isset($myTodos) && \Illuminate\Support\Arr::accessible($myTodos) && count($myTodos))
                    <ul class="space-y-2">
                        @foreach($myTodos->take(5) as $todo)
                            <li class="flex items-start justify-between p-2 rounded-lg border border-gray-200 hover:bg-gray-50">
                                <div class="flex items-start space-x-2">
                                    @if(\Illuminate\Support\Facades\Route::has('todos.update'))
                                        <form method="POST" action="{{ route('todos.update', $todo) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="completed" value="{{ $todo->completed ? 0 : 1 }}">
                                            <button type="submit" class="mt-0.5 w-4 h-4 rounded border {{ $todo->completed ? 'bg-green-500 border-green-500' : 'border-gray-300' }} flex items-center justify-center">
                                                @if($todo->completed)
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                @endif
                                            </button>
                                        </form>
                                    @else
                                        <div class="mt-0.5 w-4 h-4 rounded border {{ $todo->completed ? 'bg-green-500 border-green-500' : 'border-gray-300' }}"></div>
                                    @endif
                                    <div>
                                        <p class="text-sm {{ $todo->completed ? 'line-through text-gray-400' : 'text-gray-800' }}">{{ $todo->title }}</p>
                                        @if(!empty($todo->due_date))
                                            <p class="text-[11px] text-gray-500 mt-0.5">Due {{ \Carbon\Carbon::parse($todo->due_date)->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if(\Illuminate\Support\Facades\Route::has('todos.destroy'))
                                    <form method="POST" action="{{ route('todos.destroy', $todo) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V6a2 2 0 012-2h2a2 2 0 012 2v1"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500 text-center">No tasks yet</p>
                @endif

                @if(\Illuminate\Support\Facades\Route::has('todos.store'))
                    <form method="POST" action="{{ route('todos.store') }}" class="mt-3 flex items-center space-x-2">
                        @csrf
                        <input name="title" type="text" required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add a new task...">
                        <button type="submit" class="px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700">Add</button>
                    </form>
                @endif
            </div>
        </div>
        <!-- Active Polls -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Active Polls</h3>
                <a href="{{ route('polls.index') }}" class="text-xs text-blue-600 hover:text-blue-700">View all</a>
            </div>
            <div class="p-4">
                @if(isset($activePolls) && $activePolls->count())
                    <div class="space-y-3">
                        @foreach($activePolls->take(3) as $poll)
                            <div class="p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                <p class="text-sm font-medium text-gray-900 line-clamp-2">{{ $poll->title }}</p>
                                @php $hasVoted = $poll->responses()->where('user_id', auth()->id())->exists(); @endphp
                                @if(!$hasVoted)
                                    @if($poll->type === 'yes_no')
                                        <div class="flex space-x-2 mt-2">
                                            <form method="POST" action="{{ route('polls.vote', $poll) }}" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="response" value="Yes">
                                                <button type="submit" class="w-full px-2 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600">Yes</button>
                                            </form>
                                            <form method="POST" action="{{ route('polls.vote', $poll) }}" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="response" value="No">
                                                <button type="submit" class="w-full px-2 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600">No</button>
                                            </form>
                                        </div>
                                    @else
                                        <a href="{{ route('polls.show', $poll) }}" class="inline-flex items-center text-xs text-blue-600 hover:text-blue-700 mt-2">Vote now →</a>
                                    @endif
                                @else
                                    <p class="text-xs text-gray-500 mt-1">You voted</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center">No active polls</p>
                @endif
            </div>
        </div>

        <!-- Quick Links (Systems) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Quick Links</h3>
                <a href="{{ url('/system-links') }}" class="text-xs text-blue-600 hover:text-blue-700">All</a>
            </div>
            <div class="p-4">
                @if(isset($quickAccessLinks) && $quickAccessLinks->count())
                    <div class="space-y-2">
                        @foreach($quickAccessLinks->take(5) as $link)
                            <a href="{{ $link->url }}" target="{{ $link->opens_new_tab ? '_blank' : '_self' }}" class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                <span class="text-sm font-medium text-gray-800 truncate">{{ $link->title }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center">No quick links</p>
                @endif
            </div>
        </div>

        <!-- Birthdays Today (lightweight) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">Birthdays Today</h3>
            </div>
            <div class="p-4">
                @php($bCount = $birthdaysTodayCount ?? ($birthdaysToday ?? (isset($birthdays)? count($birthdays):0)))
                @if($bCount > 0)
                    <p class="text-sm text-gray-700">🎉 {{ $bCount }} colleague{{ $bCount > 1 ? 's' : '' }} celebrating today</p>
                @else
                    <p class="text-sm text-gray-500 text-center">No birthdays today</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection