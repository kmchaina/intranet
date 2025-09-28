@props([
  'title' => '',
])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
  <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
    <div class="flex items-start gap-2 min-w-0">
      <div class="w-5 h-5 text-blue-600">
        {!! isset($icon) && trim((string) $icon) !== ''
            ? $icon
            : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>' !!}
      </div>
      <div class="truncate">
        <h1 class="text-base sm:text-lg font-semibold text-gray-900 truncate">{{ $title }}</h1>
        @isset($meta)
          @if(trim((string) $meta) !== '')
            <div class="mt-0.5 text-xs text-gray-500">{!! $meta !!}</div>
          @endif
        @endisset
      </div>
    </div>
    <div class="flex items-center gap-2">
      {{ $actions ?? '' }}
    </div>
  </div>
  @isset($sub)
    @if(trim((string) $sub) !== '')
      <div class="px-4 py-3">{!! $sub !!}</div>
    @endif
  @endisset
  {{ $slot }}
</div>
