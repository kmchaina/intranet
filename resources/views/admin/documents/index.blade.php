@extends('layouts.dashboard')
@section('title', 'Manage Documents')

@section('content')
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Manage Documents
                    </span>
                </h1>
                <p class="text-gray-600 mt-1">View and manage all documents in the library</p>
            </div>
            <a href="{{ route('documents.create') }}"
                class="btn btn-primary shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                </svg>
                Upload Document
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium uppercase">Total Documents</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium uppercase">Total Downloads</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_downloads']) }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium uppercase">Total Size</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_size'] / 1048576, 1) }} MB</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Filters & Search --}}
        <div class="card-premium p-6">
            <form method="GET" action="{{ route('admin.documents.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-semibold text-gray-900 mb-2">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search by title, description, or filename..." class="input">
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                        <select id="category" name="category" class="select">
                            <option value="">All Categories</option>
                            <option value="policy" @selected(request('category') === 'policy')>Policy</option>
                            <option value="procedure" @selected(request('category') === 'procedure')>Procedure</option>
                            <option value="form" @selected(request('category') === 'form')>Form</option>
                            <option value="report" @selected(request('category') === 'report')>Report</option>
                            <option value="other" @selected(request('category') === 'other')>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-900 mb-2">Type</label>
                        <select id="type" name="type" class="select">
                            <option value="">All Types</option>
                            <option value="pdf" @selected(request('type') === 'pdf')>PDF</option>
                            <option value="word" @selected(request('type') === 'word')>Word</option>
                            <option value="excel" @selected(request('type') === 'excel')>Excel</option>
                            <option value="other" @selected(request('type') === 'other')>Other</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        <label for="sort_by" class="text-sm font-medium text-gray-700">Sort by:</label>
                        <select name="sort_by" id="sort_by" class="select text-sm">
                            <option value="created_at" @selected(request('sort_by', 'created_at') === 'created_at')>Upload Date</option>
                            <option value="title" @selected(request('sort_by') === 'title')>Title</option>
                            <option value="download_count" @selected(request('sort_by') === 'download_count')>Downloads</option>
                            <option value="file_size" @selected(request('sort_by') === 'file_size')>Size</option>
                        </select>

                        <select name="sort_order" class="select text-sm">
                            <option value="desc" @selected(request('sort_order', 'desc') === 'desc')>Descending</option>
                            <option value="asc" @selected(request('sort_order') === 'asc')>Ascending</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline btn-sm">Reset</a>
                        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Documents Table --}}
        @if ($documents->isNotEmpty())
            <div class="card-premium overflow-hidden">
                <form id="bulkActionForm" method="POST" action="{{ route('admin.documents.bulk-delete') }}">
                    @csrf
                    @method('DELETE')

                    {{-- Bulk Actions Bar --}}
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="selectAll" class="checkbox"
                                onclick="document.querySelectorAll('.document-checkbox').forEach(cb => cb.checked = this.checked)">
                            <label for="selectAll" class="text-sm font-medium text-gray-700">Select All</label>
                        </div>

                        <button type="button"
                            onclick="showConfirmModal({
                                type: 'danger',
                                title: 'Delete Documents',
                                message: 'Are you sure you want to delete the selected documents? This action cannot be undone.',
                                confirmText: 'Delete',
                                onConfirm: () => document.getElementById('bulkActionForm').submit()
                            })"
                            class="btn btn-sm bg-red-600 hover:bg-red-700 text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Selected
                        </button>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase w-12"></th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Document</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Category</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Uploaded By
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Size</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Downloads
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Uploaded</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($documents as $document)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <input type="checkbox" name="document_ids[]" value="{{ $document->id }}"
                                                class="document-checkbox checkbox">
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex items-start gap-3">
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-gray-900 truncate">{{ $document->title }}
                                                    </p>
                                                    @if ($document->description)
                                                        <p class="text-sm text-gray-500 line-clamp-1">
                                                            {{ Str::limit($document->description, 60) }}
                                                        </p>
                                                    @endif
                                                    <p class="text-xs text-gray-400 mt-1">{{ $document->file_name }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            <span
                                                class="inline-flex px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full capitalize">
                                                {{ $document->category }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="text-sm text-gray-600 uppercase">{{ $document->type }}</span>
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $document->uploader->name }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ number_format($document->file_size / 1048576, 2) }} MB
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                {{ $document->download_count }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $document->created_at->format('M j, Y') }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('documents.download', $document) }}"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Download">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>

                                                <form action="{{ route('admin.documents.destroy', $document) }}"
                                                    method="POST" id="deleteForm{{ $document->id }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        onclick="showConfirmModal({
                                                            type: 'danger',
                                                            title: 'Delete Document',
                                                            message: 'Are you sure you want to delete this document? The file will be permanently removed.',
                                                            confirmText: 'Delete',
                                                            onConfirm: () => document.getElementById('deleteForm{{ $document->id }}').submit()
                                                        })"
                                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                        title="Delete">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            {{-- Pagination --}}
            @if ($documents->hasPages())
                <div class="card-premium p-4">
                    {{ $documents->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-600 text-lg">No documents found</p>
            </div>
        @endif

    </div>

    {{-- Confirmation Modal --}}
    <x-confirm-modal />
@endsection
