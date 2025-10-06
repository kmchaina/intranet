@extends('layouts.dashboard')
@section('title', 'Create Event')
@section('page-title', 'Create Event')
@section('page-subtitle', 'Share upcoming activities with everyone at NIMR')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-6 py-6">
                <form method="POST" action="{{ route('events.store') }}" class="space-y-6">
                    @csrf

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Give the event a clear name" />
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select category</option>
                                @foreach ($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                            <select name="priority" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($priorities as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('priority', 'medium') === $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Describe what will happen, who should attend, and why it matters.">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time *</label>
                            <input type="datetime-local" name="start_datetime" value="{{ old('start_datetime') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('start_datetime')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Date & Time *</label>
                            <input type="datetime-local" name="end_datetime" value="{{ old('end_datetime') }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('end_datetime')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="all_day" type="checkbox" name="all_day" value="1"
                            {{ old('all_day') ? 'checked' : '' }}
                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                        <label for="all_day" class="ml-2 text-sm text-gray-700">All day event</label>
                    </div>

                    <!-- Location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="e.g., Mwanza Centre" />
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Venue / Room</label>
                            <input type="text" name="venue" value="{{ old('venue') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="e.g., Conference Hall A" />
                            @error('venue')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Venue Details</label>
                        <textarea name="venue_details" rows="2"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Directions, parking notes, or equipment needs.">{{ old('venue_details') }}</textarea>
                        @error('venue_details')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RSVP -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">RSVP (optional)</h3>
                        <div class="flex items-center mb-4">
                            <input id="requires_rsvp" type="checkbox" name="requires_rsvp" value="1"
                                {{ old('requires_rsvp') ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                onchange="toggleRsvpFields()" />
                            <label for="requires_rsvp" class="ml-2 text-sm text-gray-700">Require RSVPs for this
                                event</label>
                        </div>
                        <div id="rsvp-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display:none;">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Attendees</label>
                                <input type="number" name="max_attendees" value="{{ old('max_attendees') }}"
                                    min="1"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Leave empty for unlimited" />
                                @error('max_attendees')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">RSVP Deadline</label>
                                <input type="datetime-local" name="rsvp_deadline" value="{{ old('rsvp_deadline') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                                @error('rsvp_deadline')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Visibility -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Visibility</h3>
                        <p class="text-xs text-gray-500 mb-4">Events are visible to everyone by default so centres can see
                            each other’s activities.</p>
                        <select name="visibility_scope"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @php($fallbackScope = old('visibility_scope', 'all'))
                            <option value="all" {{ $fallbackScope === 'all' ? 'selected' : '' }}>All NIMR staff
                            </option>
                            <option value="specific" {{ $fallbackScope === 'specific' ? 'selected' : '' }}>Specific
                                centres/stations</option>
                            <option value="headquarters" {{ $fallbackScope === 'headquarters' ? 'selected' : '' }}>
                                Headquarters only</option>
                            <option value="centres" {{ $fallbackScope === 'centres' ? 'selected' : '' }}>All centres &
                                their stations</option>
                            <option value="stations" {{ $fallbackScope === 'stations' ? 'selected' : '' }}>All stations
                            </option>
                            <option value="my_centre" {{ $fallbackScope === 'my_centre' ? 'selected' : '' }}>My centre
                                only</option>
                            <option value="my_station" {{ $fallbackScope === 'my_station' ? 'selected' : '' }}>My station
                                only</option>
                        </select>

                        <div id="targeting-fields" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6"
                            style="display:none;">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Target Centres</label>
                                <select name="target_centres[]" multiple
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($centres as $centre)
                                        <option value="{{ $centre->id }}"
                                            {{ in_array($centre->id, old('target_centres', [])) ? 'selected' : '' }}>
                                            {{ $centre->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select many.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Target Stations</label>
                                <select name="target_stations[]" multiple
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ in_array($station->id, old('target_stations', [])) ? 'selected' : '' }}>
                                            {{ $station->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select many.</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Publication Status *</label>
                        <select name="status" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Save as
                                draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publish now
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('events.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Cancel</a>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">Create
                            event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleRsvpFields() {
            const checkbox = document.getElementById('requires_rsvp');
            const fields = document.getElementById('rsvp-fields');
            fields.style.display = checkbox.checked ? 'grid' : 'none';
        }

        function toggleTargetingFields() {
            const select = document.querySelector('select[name="visibility_scope"]');
            const fields = document.getElementById('targeting-fields');
            fields.style.display = select.value === 'specific' ? 'grid' : 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleRsvpFields();
            toggleTargetingFields();
            document.querySelector('select[name="visibility_scope"]').addEventListener('change',
                toggleTargetingFields);
        });
    </script>
@endsection
