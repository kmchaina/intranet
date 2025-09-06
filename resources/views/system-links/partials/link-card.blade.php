@php
    $colorClasses = $link->getColorClasses();
@endphp

<div class="relative group">
    <div
        class="bg-white rounded-lg border border-gray-200 {{ $colorClasses[0] }} {{ $colorClasses[2] }} hover:{{ $colorClasses[3] }} transition-colors duration-200 p-4 h-full">
        <!-- Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center space-x-3">
                @if ($link->icon)
                    <div class="flex-shrink-0">
                        @if (str_starts_with($link->icon, 'fa'))
                            <i class="{{ $link->icon }} text-2xl {{ $colorClasses[1] }}"></i>
                        @else
                            <span class="text-2xl">{{ $link->icon }}</span>
                        @endif
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold {{ $colorClasses[1] }} truncate">
                        {{ $link->title }}
                    </h3>
                    @if ($featured)
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                            ⭐ Featured
                        </span>
                    @endif
                </div>
            </div>

            <!-- Actions Dropdown -->
            <div class="relative">
                <button type="button" class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="toggleDropdown('dropdown-{{ $link->id }}')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v.01M12 12v.01M12 19v.01" />
                    </svg>
                </button>
                <div id="dropdown-{{ $link->id }}"
                    class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                    <div class="py-1">
                        <a href="{{ route('system-links.show', $link) }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Details
                        </a>
                        @if ($link->added_by === Auth::id() || Auth::user()->is_admin)
                            <a href="{{ route('system-links.edit', $link) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('system-links.destroy', $link) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this link?')"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        @if ($link->description)
            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $link->description }}</p>
        @endif

        <!-- Badges -->
        <div class="flex flex-wrap gap-2 mb-4">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                {{ \App\Models\SystemLink::getCategories()[$link->category] ?? $link->category }}
            </span>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                {{ \App\Models\SystemLink::getAccessLevels()[$link->access_level] ?? $link->access_level }}
            </span>
            @if ($link->requires_vpn)
                <span
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                    🔒 VPN Required
                </span>
            @endif
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between text-xs text-gray-500">
            <div class="flex items-center space-x-4">
                <span class="flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ $link->click_count }} {{ Str::plural('click', $link->click_count) }}
                </span>
                <span>Added {{ $link->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <!-- Link Overlay -->
        <a href="#"
            onclick="handleLinkClick(event, '{{ $link->url }}', {{ $link->id }}, {{ $link->opens_new_tab ? 'true' : 'false' }})"
            class="absolute inset-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <span class="sr-only">Open {{ $link->title }}</span>
        </a>
    </div>
</div>

@push('scripts')
    <script>
        function toggleDropdown(id) {
            event.stopPropagation();
            const dropdown = document.getElementById(id);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');

            // Close all other dropdowns
            allDropdowns.forEach(d => {
                if (d.id !== id) {
                    d.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        function handleLinkClick(event, url, linkId, opensNewTab) {
            // Don't trigger if clicking on dropdown or form elements
            if (event.target.closest('[onclick*="toggleDropdown"]') ||
                event.target.closest('form') ||
                event.target.closest('button')) {
                return;
            }

            event.preventDefault();

            // Increment click count
            fetch(`/system-links/${linkId}/increment-click`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).catch(error => console.error('Error incrementing click count:', error));

            // Open the link
            if (opensNewTab) {
                window.open(url, '_blank');
            } else {
                window.location.href = url;
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
            allDropdowns.forEach(d => d.classList.add('hidden'));
        });
    </script>
@endpush
