{{-- Standard Page Layout Component --}}
<div class="max-w-6xl mx-auto px-4 py-6">
	{{-- Page Header --}}
	<div class="mb-6">
		<div class="flex items-center justify-between">
			<div>
				@isset($title)
					<h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
				@endisset
				@isset($subtitle)
					<p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
				@endisset
			</div>
			@isset($actions)
				<div class="flex items-center space-x-3">
					{{ $actions }}
				</div>
			@endisset
		</div>
	</div>

	{{-- Content Area --}}
	<div class="space-y-6">
		{{ $slot }}
	</div>
</div>
