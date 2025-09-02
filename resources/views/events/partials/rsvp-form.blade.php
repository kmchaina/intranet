<!-- RSVP Form -->
<form method="POST" action="{{ route('events.rsvp', $event) }}" class="space-y-4">
    @csrf

    <!-- RSVP Status -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
            Will you attend? *
        </label>
        <div class="space-y-3">
            <label
                class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                <input type="radio" name="status" value="attending"
                    {{ $userRsvp && $userRsvp->status === 'attending' ? 'checked' : '' }}
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Yes, I will attend</span>
            </label>
            <label
                class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                <input type="radio" name="status" value="maybe"
                    {{ $userRsvp && $userRsvp->status === 'maybe' ? 'checked' : '' }}
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Maybe</span>
            </label>
            <label
                class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                <input type="radio" name="status" value="declined"
                    {{ $userRsvp && $userRsvp->status === 'declined' ? 'checked' : '' }}
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">No, I can't attend</span>
            </label>
        </div>
        @error('status')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit Button -->
    <div class="pt-4">
        <button type="submit"
            class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            {{ $userRsvp ? 'Update RSVP' : 'Submit RSVP' }}
        </button>
    </div>
</form>
