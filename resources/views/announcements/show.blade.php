@extends('layouts.dashboard')
@section('title', 'Announcements')
@php
	use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="p-6">
	<div class="max-w-4xl mx-auto">
		<x-breadcrumbs :items="[
			['label' => 'Dashboard', 'href' => route('dashboard')],
			['label' => 'Announcements', 'href' => route('announcements.index')],
			['label' => Str::limit($announcement->title, 50)],
		]" />

		<!-- Announcement -->
		<article class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 md:p-8">
			<!-- Minimal header -->
			<header class="mb-6 pb-5 border-b border-gray-200">
				<div class="flex items-start justify-between gap-3">
					<div class="min-w-0">
						<div class="flex items-center gap-2 mb-2">
							<span class="inline-block w-2.5 h-2.5 rounded-full @if($announcement->priority==='high') bg-red-500 @elseif($announcement->priority==='medium') bg-yellow-500 @else bg-green-500 @endif"></span>
							<span class="text-xs font-medium px-2 py-0.5 rounded-full @if ($announcement->category === 'urgent') bg-red-100 text-red-800 @elseif($announcement->category === 'event') bg-purple-100 text-purple-800 @elseif($announcement->category === 'policy') bg-blue-100 text-blue-800 @elseif($announcement->category === 'training') bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
								{{ ucfirst($announcement->category) }}
							</span>
						</div>
						<h1 class="text-xl md:text-2xl font-semibold text-gray-900 truncate">{{ $announcement->title }}</h1>
					</div>

					@if (auth()->user()->can('update', $announcement) || auth()->user()->can('delete', $announcement))
						<div class="flex items-center gap-2 shrink-0">
							@can('update', $announcement)
								<a href="{{ route('announcements.edit', $announcement) }}" class="inline-flex items-center px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
									<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
									</svg>
									Edit
								</a>
							@endcan
							@can('delete', $announcement)
								<form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('Delete this announcement?')">
									@csrf
									@method('DELETE')
									<button type="submit" class="inline-flex items-center px-3 py-2 text-sm bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
										<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
										</svg>
										Delete
									</button>
								</form>
							@endcan
						</div>
					@endif
				</div>

				<div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-600">
					<div class="flex items-center">
						<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
						</svg>
						<span class="font-medium">{{ $announcement->creator->name }}</span>
					</div>
					<div class="flex items-center">
						<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
						</svg>
						<span>{{ $announcement->published_at->format('M j, Y \a\t g:i A') }}</span>
					</div>
					@if ($announcement->target_scope !== 'all')
						<div class="flex items-center">
							<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
							</svg>
							<span class="capitalize">{{ str_replace('_', ' ', $announcement->target_scope) }}</span>
						</div>
					@endif
					<span class="inline-flex px-3 py-1 rounded-full text-xs font-medium @if($announcement->priority==='high') bg-red-100 text-red-700 @elseif($announcement->priority==='medium') bg-yellow-100 text-yellow-700 @else bg-green-100 text-green-700 @endif">
						{{ ucfirst($announcement->priority) }} priority
					</span>
					@if ($announcement->expires_at)
						<div class="flex items-center text-orange-600">
							<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
							</svg>
							<span>Expires {{ $announcement->expires_at->format('M j, Y') }}</span>
						</div>
					@endif
				</div>
			</header>

			<!-- Content -->
			<div class="prose prose-lg max-w-none">
				{!! nl2br(e($announcement->content)) !!}
			</div>

			<!-- Attachments -->
			@if ($announcement->attachments->count() > 0)
				<div class="mt-8 pt-6 border-t border-gray-200">
					<h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
						<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
						</svg>
						Attachments ({{ $announcement->attachments->count() }})
					</h3>
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
						@foreach ($announcement->attachments as $attachment)
							<a href="{{ route('announcements.download-attachment', $attachment) }}" class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 hover:border-gray-300 transition-colors group">
								<div class="flex-shrink-0 mr-3">
									<div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
										<i class="{{ $attachment->getFileIcon() }} text-blue-600"></i>
									</div>
								</div>
								<div class="flex-1 min-w-0">
									<p class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors truncate">{{ $attachment->original_name }}</p>
									<p class="text-xs text-gray-500">{{ $attachment->getFormattedSize() }}</p>
								</div>
								<div class="flex-shrink-0 ml-2">
									<svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
									</svg>
								</div>
							</a>
						@endforeach
					</div>
				</div>
			@endif

            

			<!-- Views Counter -->
			<div class="mt-8 pt-6 border-t border-gray-200">
				<div class="flex items-center text-sm text-gray-600">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
					</svg>
					<span>{{ $announcement->views_count }} {{ Str::plural('view', $announcement->views_count) }}</span>
				</div>
			</div>
		</article>
	</div>
	</div>

@push('scripts')
<script>
	// Mark announcement as read when page loads
	document.addEventListener('DOMContentLoaded', function() {
		fetch(`{{ route('announcements.mark-read', $announcement) }}`, {
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
				'Content-Type': 'application/json',
			}
		}).catch(console.error);
	});
	</script>
@endpush
@endsection
