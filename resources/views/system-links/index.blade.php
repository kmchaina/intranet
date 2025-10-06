@extends('layouts.dashboard')
@section('title', 'Quick Links')

@section('content')
    <div class="space-y-6">
        <x-breadcrumbs :items="[['label' => 'Dashboard', 'href' => route('dashboard')], ['label' => 'Quick Links']]" />

        <!-- Premium Header -->
        <div class="card-premium overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-8 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">Quick Links</h1>
                            <p class="text-white/90 mt-1">Fast access to important organizational systems</p>
                        </div>
                    </div>
                    @if (auth()->user()->isSuperAdmin())
                        <a href="{{ route('system-links.create') }}" class="btn btn-ghost text-white hover:bg-white/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Link
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Links Grid -->
        <div class="card-premium p-8">
            @if ($allLinks->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                    @foreach ($allLinks as $link)
                        @php
                            $colorClasses = $link->getColorClasses();
                        @endphp
                        <div class="group relative">
                            <!-- Main Clickable Link -->
                            <a href="{{ $link->url }}" target="{{ $link->opens_new_tab ? '_blank' : '_self' }}"
                                onclick="trackLinkClick({{ $link->id }})"
                                class="block p-6 bg-gradient-to-br from-white to-nimr-neutral-50 border-2 border-nimr-neutral-200 rounded-2xl hover:border-nimr-primary-400 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">

                                <!-- Icon -->
                                <div
                                    class="w-16 h-16 {{ $colorClasses[0] }} rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                    @if ($link->icon)
                                        @if (str_starts_with($link->icon, 'fa'))
                                            <i class="{{ $link->icon }} {{ $colorClasses[1] }} text-2xl"></i>
                                        @else
                                            <span class="text-3xl">{{ $link->icon }}</span>
                                        @endif
                                    @else
                                        <svg class="w-8 h-8 text-nimr-neutral-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    @endif
                                </div>

                                <!-- Title -->
                                <h3 class="text-center text-sm font-bold text-nimr-neutral-900 line-clamp-2 mb-2">
                                    {{ $link->title }}
                                </h3>

                                <!-- Description (if exists) -->
                                @if ($link->description)
                                    <p class="text-center text-xs text-nimr-neutral-600 line-clamp-2">
                                        {{ $link->description }}
                                    </p>
                                @endif

                                <!-- New Tab Indicator -->
                                @if ($link->opens_new_tab)
                                    <div class="flex items-center justify-center gap-1 mt-2">
                                        <svg class="w-3 h-3 text-nimr-neutral-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        <span class="text-xs text-nimr-neutral-500">Opens in new tab</span>
                                    </div>
                                @endif
                            </a>

                            <!-- Admin Actions (only visible on hover for Super Admin) -->
                            @if (auth()->user()->isSuperAdmin())
                                <div
                                    class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                    <a href="{{ route('system-links.edit', $link) }}"
                                        class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 shadow-lg"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('system-links.destroy', $link) }}" method="POST"
                                        onsubmit="return confirm('Delete this link? This will remove it for all users.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 bg-red-600 text-white rounded-lg flex items-center justify-center hover:bg-red-700 shadow-lg"
                                            title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="py-20 text-center">
                    <svg class="w-20 h-20 mx-auto text-nimr-neutral-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <h3 class="text-xl font-bold text-nimr-neutral-900 mb-2">No Quick Links Yet</h3>
                    <p class="text-nimr-neutral-600 mb-6">Get started by adding your first link</p>
                    @if (auth()->user()->isSuperAdmin())
                        <a href="{{ route('system-links.create') }}" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add First Link
                        </a>
                    @endif
                </div>
            @endif
        </div>
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
