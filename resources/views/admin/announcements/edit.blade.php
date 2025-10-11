@extends('layouts.dashboard')
@section('title', 'Edit Announcement')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Announcement</h1>
                <p class="text-gray-600 mt-1">Update announcement details</p>
            </div>
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Management
            </a>
        </div>
        <div class="card-premium p-8">
            <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $announcement->title) }}"
                        class="input" required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-900 mb-2">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="10" class="input resize-y" required>{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category and Priority --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                        <select id="category" name="category" class="select">
                            <option value="general" @selected(old('category', $announcement->category) === 'general')>General</option>
                            <option value="urgent" @selected(old('category', $announcement->category) === 'urgent')>Urgent</option>
                            <option value="event" @selected(old('category', $announcement->category) === 'event')>Event</option>
                            <option value="policy" @selected(old('category', $announcement->category) === 'policy')>Policy</option>
                            <option value="training" @selected(old('category', $announcement->category) === 'training')>Training</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-900 mb-2">Priority</label>
                        <select id="priority" name="priority" class="select">
                            <option value="low" @selected(old('priority', $announcement->priority) === 'low')>Low</option>
                            <option value="medium" @selected(old('priority', $announcement->priority) === 'medium')>Medium</option>
                            <option value="high" @selected(old('priority', $announcement->priority) === 'high')>High</option>
                        </select>
                    </div>
                </div>

                {{-- Target Audience (simplified for admin edit) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Target Audience</label>
                    <select name="target_scope" class="select">
                        @foreach ($allowedScopes as $scope)
                            <option value="{{ $scope }}" @selected(old('target_scope', $announcement->target_scope) === $scope)>
                                {{ match ($scope) {
                                    'all' => 'All NIMR Staff',
                                    'headquarters' => 'Headquarters Only',
                                    'my_centre' => 'Centre Level',
                                    'my_centre_stations' => 'Centre and Its Stations',
                                    'my_station' => 'Station Level',
                                    'all_centres' => 'All Centres',
                                    'all_stations' => 'All Stations',
                                    'specific' => 'Specific Centres/Stations',
                                    default => ucfirst(str_replace('_', ' ', $scope)),
                                } }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Publishing Options --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="published_at" class="block text-sm font-semibold text-gray-900 mb-2">Publish
                            Date</label>
                        <input type="datetime-local" id="published_at" name="published_at"
                            value="{{ old('published_at', $announcement->published_at?->format('Y-m-d\TH:i')) }}"
                            class="input">
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-semibold text-gray-900 mb-2">Expiry Date</label>
                        <input type="datetime-local" id="expires_at" name="expires_at"
                            value="{{ old('expires_at', $announcement->expires_at?->format('Y-m-d\TH:i')) }}"
                            class="input">
                    </div>
                </div>

                {{-- Email Notification --}}
                <div class="flex items-start bg-blue-50 p-4 rounded-xl">
                    <input type="checkbox" id="email_notification" name="email_notification" value="1"
                        @checked(old('email_notification', $announcement->email_notification)) class="checkbox mt-0.5">
                    <label for="email_notification" class="ml-3 text-sm font-medium text-gray-900">
                        Send email notification to target audience
                    </label>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Announcement
                    </button>
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
