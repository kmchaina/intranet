@extends('layouts.dashboard')

@section('title', 'Edit News')

@section('content')
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit News</h1>
                    <p class="mt-2 text-gray-600">Update your news post</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('news.show', $news) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        View News
                    </a>
                    <a href="{{ route('news.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to News
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('news.update', $news) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title"
                        value="{{ old('title', $news && is_object($news) ? $news->title : '') }}" required
                        placeholder="Enter a compelling news title..."
                        class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('title') border-red-300 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" id="content" rows="12" required placeholder="Write your news content here..."
                        class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('content') border-red-300 @enderror">{{ old('content', $news && is_object($news) ? $news->content : '') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Tip: Use clear paragraphs to make your content easy to read.</p>
                </div>

                <!-- Current Featured Image -->
                @if ($news && is_object($news) && $news->featured_image)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Featured Image</label>
                        <div class="flex items-start space-x-4">
                            <img src="{{ asset('storage/' . $news->featured_image) }}" alt="Current featured image"
                                class="w-32 h-24 object-cover rounded-lg border">
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-2">Current image will be replaced if you upload a new
                                    one.</p>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="remove_image" value="1"
                                        class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Remove current image</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Featured Image Upload -->
                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $news && is_object($news) && $news->featured_image ? 'Replace Featured Image' : 'Featured Image' }}
                    </label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="featured_image"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                                    <span>Upload a file</span>
                                    <input id="featured_image" name="featured_image" type="file" accept="image/*"
                                        class="sr-only" onchange="previewImage(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                        </div>
                    </div>

                    <!-- New Image Preview -->
                    <div id="image-preview" class="mt-4 hidden">
                        <img id="preview-img" src="" alt="Preview" class="max-w-full h-64 object-cover rounded-lg">
                        <button type="button" onclick="removeImage()"
                            class="mt-2 text-sm text-red-600 hover:text-red-800">Remove new image</button>
                    </div>

                    @error('featured_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Settings Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Location <span class="text-red-500">*</span>
                        </label>
                        <select name="location" id="location" required
                            class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('location') border-red-300 @enderror">
                            <option value="">Select Location</option>
                            <option value="headquarters"
                                {{ old('location', $news->location) == 'headquarters' ? 'selected' : '' }}>Headquarters
                            </option>
                            <option value="mwanza" {{ old('location', $news->location) == 'mwanza' ? 'selected' : '' }}>
                                Mwanza</option>
                            <option value="mbeya" {{ old('location', $news->location) == 'mbeya' ? 'selected' : '' }}>
                                Mbeya</option>
                            <option value="tanga" {{ old('location', $news->location) == 'tanga' ? 'selected' : '' }}>
                                Tanga</option>
                            <option value="tabora" {{ old('location', $news->location) == 'tabora' ? 'selected' : '' }}>
                                Tabora</option>
                            <option value="dodoma" {{ old('location', $news->location) == 'dodoma' ? 'selected' : '' }}>
                                Dodoma</option>
                            <option value="amani" {{ old('location', $news->location) == 'amani' ? 'selected' : '' }}>
                                Amani</option>
                            <option value="mpwapwa" {{ old('location', $news->location) == 'mpwapwa' ? 'selected' : '' }}>
                                Mpwapwa</option>
                        </select>
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select name="priority" id="priority"
                            class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('priority') border-red-300 @enderror">
                            <option value="normal"
                                {{ old('priority', $news && is_object($news) ? $news->priority : 'normal') == 'normal' ? 'selected' : '' }}>
                                Normal</option>
                            <option value="high"
                                {{ old('priority', $news && is_object($news) ? $news->priority : 'normal') == 'high' ? 'selected' : '' }}>
                                High
                            </option>
                            <option value="urgent"
                                {{ old('priority', $news && is_object($news) ? $news->priority : 'normal') == 'urgent' ? 'selected' : '' }}>
                                Urgent</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status"
                            class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('status') border-red-300 @enderror">
                            <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>
                                Published</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Drafts are only visible to you until published.</p>
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="space-y-4 pt-6 border-t border-gray-200">
                    <div class="flex items-center">
                        <input id="is_featured" name="is_featured" type="checkbox" value="1"
                            {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                            <span class="font-medium">Featured News</span>
                            <span class="text-gray-500 block">Display this news prominently on the news page</span>
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input id="allow_comments" name="allow_comments" type="checkbox" value="1"
                            {{ old('allow_comments', $news->allow_comments) ? 'checked' : '' }}
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <label for="allow_comments" class="ml-2 block text-sm text-gray-700">
                            <span class="font-medium">Allow Comments</span>
                            <span class="text-gray-500 block">Let people comment on this news post</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Article Statistics -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Article Statistics</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $news && is_object($news) ? $news->views_count : 0 }}</div>
                        <div class="text-sm text-gray-600">Views</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $news && is_object($news) ? $news->likes_count : 0 }}</div>
                        <div class="text-sm text-gray-600">Likes</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $news && is_object($news) && $news->comments_count ? $news->comments_count : 0 }}</div>
                        <div class="text-sm text-gray-600">Comments</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $news && is_object($news) ? $news->reading_time : '' }}</div>
                        <div class="text-sm text-gray-600">Reading Time</div>
                    </div>
                </div>

                <div class="mt-4 text-sm text-gray-600">
                    <p><strong>Created:</strong>
                        {{ $news && is_object($news) && $news->created_at ? $news->created_at->format('F j, Y \a\t g:i A') : 'Unknown' }}
                    </p>
                    <p><strong>Last Updated:</strong>
                        {{ $news && is_object($news) && $news->updated_at ? $news->updated_at->format('F j, Y \a\t g:i A') : 'Unknown' }}
                    </p>
                    @if ($news && is_object($news) && $news->published_at)
                        <p><strong>Published:</strong> {{ $news->published_at->format('F j, Y \a\t g:i A') }}</p>
                    @endif
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Delete Button -->
                    @can('delete', $news)
                        <button type="button" onclick="confirmDelete()"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                            Delete News
                        </button>
                    @endcan
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('news.show', $news) }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        Cancel
                    </a>

                    <button type="submit" name="action" value="draft"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                        Save as Draft
                    </button>

                    <button type="submit" name="action" value="publish"
                        class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        Update & Publish
                    </button>
                </div>
            </div>
        </form>

        <!-- Delete Form (hidden) -->
        @can('delete', $news)
            <form id="delete-form" action="{{ route('news.destroy', $news) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endcan
    </div>

    @push('scripts')
        <script>
            function previewImage(input) {
                const preview = document.getElementById('image-preview');
                const previewImg = document.getElementById('preview-img');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        preview.classList.remove('hidden');
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            function removeImage() {
                const input = document.getElementById('featured_image');
                const preview = document.getElementById('image-preview');

                input.value = '';
                preview.classList.add('hidden');
            }

            function confirmDelete() {
                if (confirm('Are you sure you want to delete this news post? This action cannot be undone.')) {
                    document.getElementById('delete-form').submit();
                }
            }

            // Handle form submission based on button clicked
            document.querySelectorAll('button[type="submit"]').forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.getAttribute('name');
                    if (action === 'action') {
                        const statusSelect = document.getElementById('status');
                        if (this.value === 'draft') {
                            statusSelect.value = 'draft';
                        } else if (this.value === 'publish') {
                            statusSelect.value = 'published';
                        }
                    }
                });
            });

            // Auto-save functionality (optional)
            let autoSaveTimeout;

            function autoSave() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    // Implement auto-save logic here if needed
                    console.log('Auto-save triggered');
                }, 30000); // Auto-save every 30 seconds
            }

            document.getElementById('content').addEventListener('input', autoSave);
            document.getElementById('title').addEventListener('input', autoSave);
        </script>
    @endpush
@endsection
