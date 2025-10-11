@extends('layouts.dashboard')
@section('title', 'Edit News')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit News Article</h1>
                <p class="text-gray-600 mt-1">Update news article details</p>
            </div>
            <a href="{{ route('admin.news.index') }}" class="btn btn-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Management
            </a>
        </div>
        <div class="card-premium p-8">
            <form action="{{ route('admin.news.update', $news) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $news->title) }}"
                        class="input" required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Excerpt --}}
                <div>
                    <label for="excerpt" class="block text-sm font-semibold text-gray-900 mb-2">
                        Excerpt
                    </label>
                    <textarea id="excerpt" name="excerpt" rows="3" class="input resize-y">{{ old('excerpt', $news->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-900 mb-2">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="15" class="input resize-y" required>{{ old('content', $news->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status and Published Date --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                        <select id="status" name="status" class="select">
                            <option value="draft" @selected(old('status', $news->status) === 'draft')>Draft</option>
                            <option value="published" @selected(old('status', $news->status) === 'published')>Published</option>
                        </select>
                    </div>

                    <div>
                        <label for="published_at" class="block text-sm font-semibold text-gray-900 mb-2">Published
                            Date</label>
                        <input type="datetime-local" id="published_at" name="published_at"
                            value="{{ old('published_at', $news->published_at?->format('Y-m-d\TH:i')) }}" class="input">
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update News Article
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
