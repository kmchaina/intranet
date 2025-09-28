@extends('layouts.dashboard')
@section('title', 'Document Details')
@section('content')
<div class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-100/40 min-h-screen">
  <div class="max-w-7xl mx-auto p-6">
    <x-breadcrumbs :items="[
        ['label' => 'Dashboard', 'href' => route('dashboard')],
        ['label' => 'Documents', 'href' => route('documents.index')],
        ['label' => Str::limit($document->title ?? 'Untitled Document', 50)],
    ]" />
    <x-page.header :title="$document->title ?? 'Untitled Document'">
      <x-slot:icon>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      </x-slot:icon>
      <x-slot:meta>
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z"/></svg>{{ ucfirst($document->category ?? 'general') }}</span>
          <span class="inline-flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $document->created_at->format('M j, Y') }}</span>
          @if($document->file_size ?? false)
            <span class="inline-flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>{{ number_format($document->file_size / 1024, 1) }} KB</span>
          @endif
        </div>
      </x-slot:meta>
      <x-slot:actions>
        <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200">
          <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Download
        </a>
        @can('update', $document)
        <a href="{{ route('documents.edit', $document) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
          <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit
        </a>
        @endcan
      </x-slot:actions>
    </x-page.header>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Preview -->
      <div class="lg:col-span-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Preview</h2>
            @php $ext = strtolower(pathinfo($document->file_path ?? '', PATHINFO_EXTENSION)); @endphp
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ strtoupper($ext) }}</span>
          </div>
          <div class="p-6">
            @php $publicUrl = isset($document->file_path) ? asset('storage/' . $document->file_path) : null; @endphp
            @if(isset($document->file_path) && $ext === 'pdf')
              <iframe src="{{ $publicUrl }}" class="w-full h-[70vh] rounded-lg border"></iframe>
            @elseif(isset($document->file_path) && in_array($ext, ['jpg','jpeg','png','gif','webp']))
              <img src="{{ $publicUrl }}" alt="{{ $document->title }}" class="w-full rounded-lg border" />
            @elseif(isset($document->file_path) && in_array($ext, ['txt','md']))
              <div class="prose max-w-none">
                <pre class="whitespace-pre-wrap">{{ file_get_contents(storage_path('app/public/' . $document->file_path)) }}</pre>
              </div>
            @else
              <div class="text-center py-16">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-gray-600">No inline preview available. Use the Download button to view this document.</p>
              </div>
            @endif
          </div>
        </div>
      </div>
      <!-- Sidebar Info -->
      <div class="lg:col-span-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Document Info</h2>
          </div>
          <div class="p-6 space-y-4">
            <div>
              <h4 class="text-sm font-medium text-gray-900">Description</h4>
              <p class="text-sm text-gray-600 mt-1">{{ $document->description ?? 'No description provided.' }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-500">Uploaded</span>
                <div class="text-gray-900">{{ $document->created_at->format('M j, Y') }}</div>
              </div>
              <div>
                <span class="text-gray-500">Updated</span>
                <div class="text-gray-900">{{ $document->updated_at->format('M j, Y') }}</div>
              </div>
              <div>
                <span class="text-gray-500">Category</span>
                <div class="text-gray-900">{{ ucfirst($document->category ?? 'general') }}</div>
              </div>
              <div>
                <span class="text-gray-500">Access</span>
                <div class="text-gray-900">{{ ucfirst($document->access_level ?? 'public') }}</div>
              </div>
            </div>
            @if(!empty($document->tags))
              <div>
                <h4 class="text-sm font-medium text-gray-900 mb-1">Tags</h4>
                <div class="flex flex-wrap gap-2">
                  @foreach((array)$document->tags as $tag)
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">{{ $tag }}</span>
                  @endforeach
                </div>
              </div>
            @endif
            <div class="pt-2 flex items-center gap-2">
              <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download
              </a>
              @can('update', $document)
              <a href="{{ route('documents.edit', $document) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit
              </a>
              @endcan
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
