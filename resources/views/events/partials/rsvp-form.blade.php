<!-- RSVP Form -->
<form method="POST" action="{{ route('events.rsvp', $event) }}" class="space-y-4">
    @csrf

    <!-- RSVP Status -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-3">Will you attend? *</label>
        <div class="space-y-3">
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                <input type="radio" name="status" value="attending"
                    {{ $userRsvp && $userRsvp->status === 'attending' ? 'checked' : '' }}
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                <span class="ml-3 text-sm font-medium text-gray-700">Yes, I will attend</span>
            </label>

            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                <input type="radio" name="status" value="maybe"
                    {{ $userRsvp && $userRsvp->status === 'maybe' ? 'checked' : '' }}
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                <span class="ml-3 text-sm font-medium text-gray-700">Maybe</span>
            </label>
        </div>

        @error('status')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit Button -->
    <div class="pt-4">
        <button type="submit"
            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            {{ $userRsvp ? 'Update RSVP' : 'Submit RSVP' }}
        </button>
    </div>
</form>
