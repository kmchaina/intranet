@php
    $colorClasses = $link->getColorClasses();
@endphp

<tr class="hover:bg-gray-50">
    <!-- Link Info -->
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            @if ($link->icon)
                <div class="flex-shrink-0 mr-3">
                    <span class="text-lg">{{ $link->icon }}</span>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-2">
                    <a href="#"
                        onclick="handleLinkClick(event, '{{ $link->url }}', {{ $link->id }}, {{ $link->opens_new_tab ? 'true' : 'false' }})"
                        class="text-sm font-medium text-blue-600 hover:text-blue-800 truncate max-w-xs">
                        {{ $link->title }}
                    </a>
                    @if ($link->is_featured)
                        <span
                            class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                            ⭐
                        </span>
                    @endif
                    @if ($link->requires_vpn)
                        <span
                            class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">
                            🔒
                        </span>
                    @endif
                </div>
                @if ($link->description)
                    <p class="text-xs text-gray-500 truncate max-w-sm">{{ $link->description }}</p>
                @endif
            </div>
        </div>
    </td>

    <!-- Category -->
    <td class="px-6 py-4 whitespace-nowrap">
        <span
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClasses[0] }} {{ $colorClasses[1] }}">
            {{ \App\Models\SystemLink::getCategories()[$link->category] ?? $link->category }}
        </span>
    </td>

    <!-- Access Level -->
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
            {{ \App\Models\SystemLink::getAccessLevels()[$link->access_level] ?? $link->access_level }}
        </span>
    </td>

    <!-- Click Count -->
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            {{ $link->click_count }}
        </div>
    </td>

    <!-- Added Date -->
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $link->created_at->diffForHumans() }}
        </div>
    </td>

    <!-- Actions -->
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <div class="relative">
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                onclick="toggleDropdown('dropdown-row-{{ $link->id }}')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01" />
                </svg>
            </button>
            <div id="dropdown-row-{{ $link->id }}"
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
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    </td>
</tr>
