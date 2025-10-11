@extends('layouts.dashboard')
@section('title', 'Create News')

@section('content')
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'News Feed', 'href' => route('news.index')],
            ['label' => 'Create News'],
        ]" />

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create News</h1>
                    <p class="mt-2 text-gray-600">Share news and activities with the NIMR community</p>
                </div>
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

        <!-- Form -->
        <form id="news-form" action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        placeholder="Enter a compelling news title..."
                        class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('title') border-red-300 @enderror">
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
                        class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('content') border-red-300 @enderror">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Tip: Use clear paragraphs to make your content easy to read.</p>
                </div>

                <!-- Featured Image -->
                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-semibold text-gray-900 mb-2">
                        Featured Image (Cover Photo)
                    </label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="featured_image"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload cover image</span>
                                    <input id="featured_image" name="featured_image" type="file" accept="image/*"
                                        class="sr-only" onchange="previewImage(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 2MB</p>
                        </div>
                    </div>

                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-4 hidden">
                        <img id="preview-img" src="" alt="Preview" class="max-w-full h-64 object-cover rounded-lg">
                        <button type="button" onclick="removeImage()"
                            class="mt-2 text-sm text-red-600 hover:text-red-800">Remove image</button>
                    </div>

                    @error('featured_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Attachments -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Additional Attachments - Images, Documents, PDFs (Multiple files allowed)
                    </label>

                    {{-- Info Banner --}}
                    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800 font-bold mb-2">
                            ⚡ MULTIPLE FILES SUPPORTED - Upload as many images/documents as you need!
                        </p>
                        <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                            <li><strong>HOLD Ctrl</strong> (Windows) or <strong>Cmd</strong> (Mac) + click each file</li>
                            <li>OR use "Add More Files" button to keep adding more</li>
                        </ul>
                    </div>

                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors bg-gray-50/50"
                        id="uploadArea">
                        <input type="file" id="attachments" name="attachments[]" multiple
                            accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.xls,.xlsx,.ppt,.pptx"
                            class="hidden" onchange="updateFileList(this)">
                        <label for="attachments" class="cursor-pointer block">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-xl text-gray-900 font-bold mb-2">
                                <span class="text-blue-600">Click to upload MULTIPLE FILES</span>
                            </p>
                            <p class="text-base text-gray-700 font-medium">
                                ⚡ Select MULTIPLE FILES at once (hold Ctrl/Cmd)
                            </p>
                            <p class="text-sm text-gray-500 mt-2">
                                Images, PDFs, Documents - up to 10MB each
                            </p>
                        </label>
                    </div>

                    {{-- File List with Add More Button --}}
                    <div id="fileListContainer" class="mt-4 hidden">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-700">
                                Selected Files (<span id="fileCount">0</span>)
                            </h4>
                            <button type="button" onclick="document.getElementById('attachments').click()"
                                class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add More Files
                            </button>
                        </div>
                        <div id="fileList" class="space-y-2"></div>
                    </div>

                    @error('attachments.*')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Settings Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select name="priority" id="priority"
                            class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Normal
                            </option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    <!-- Status (Hidden - will be set by button) -->
                    <input type="hidden" name="status" id="status" value="published">
                </div>

                <!-- Additional Options -->
                <div class="space-y-4 pt-6 border-t border-gray-200">
                    <div class="flex items-center">
                        <input id="is_featured" name="is_featured" type="checkbox" value="1"
                            {{ old('is_featured') ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                            <span class="font-medium">Featured News</span>
                            <span class="text-gray-500 block">Display this news prominently on the news page</span>
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input id="allow_comments" name="allow_comments" type="checkbox" value="1"
                            {{ old('allow_comments', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="allow_comments" class="ml-2 block text-sm text-gray-700">
                            <span class="font-medium">Allow Comments</span>
                            <span class="text-gray-500 block">Let people comment on this news post</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span class="font-medium">Note:</span> All users across NIMR locations will be able to see published
                    news.
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('news.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="button" onclick="saveDraft()"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                        Save as Draft
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Publish News
                    </button>
                </div>
            </div>
        </form>
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

            function saveDraft() {
                document.getElementById('status').value = 'draft';
                document.getElementById('news-form').submit();
            }

            // File upload handling with accumulation
            let accumulatedFiles = [];

            function updateFileList(input) {
                const fileList = document.getElementById('fileList');
                const fileListContainer = document.getElementById('fileListContainer');
                const fileCount = document.getElementById('fileCount');
                const uploadArea = document.getElementById('uploadArea');

                // Add new files to accumulated list (avoid duplicates)
                if (input.files && input.files.length > 0) {
                    const newFiles = Array.from(input.files);
                    newFiles.forEach(file => {
                        const isDuplicate = accumulatedFiles.some(f =>
                            f.name === file.name && f.size === file.size
                        );
                        if (!isDuplicate) {
                            accumulatedFiles.push(file);
                        }
                    });
                }

                // Update the actual input with accumulated files
                const dt = new DataTransfer();
                accumulatedFiles.forEach(file => dt.items.add(file));
                input.files = dt.files;

                // Refresh display
                refreshFileDisplay();
            }

            function removeFile(index) {
                accumulatedFiles.splice(index, 1);
                refreshFileDisplay();
            }

            function refreshFileDisplay() {
                const fileList = document.getElementById('fileList');
                const fileListContainer = document.getElementById('fileListContainer');
                const fileCount = document.getElementById('fileCount');
                const uploadArea = document.getElementById('uploadArea');
                const input = document.getElementById('attachments');

                // Update the input with accumulated files
                const dt = new DataTransfer();
                accumulatedFiles.forEach(file => dt.items.add(file));
                input.files = dt.files;

                // Clear file list
                fileList.innerHTML = '';

                if (accumulatedFiles.length > 0) {
                    // Show file list container
                    fileListContainer.classList.remove('hidden');
                    fileCount.textContent = accumulatedFiles.length;

                    // Update upload area styling
                    uploadArea.className =
                        'border-2 border-dashed border-green-400 rounded-xl p-6 text-center bg-green-50/50 transition-colors';
                    uploadArea.querySelector('label').innerHTML = `
                        <svg class="w-12 h-12 mx-auto text-green-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-lg text-green-700 font-bold">${accumulatedFiles.length} file(s) selected</p>
                        <p class="text-sm text-gray-600 mt-2">Click "Add More Files" below to add more</p>
                    `;

                    accumulatedFiles.forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        const isImage = file.type.startsWith('image/');
                        fileItem.className =
                            'flex items-center justify-between bg-white border border-gray-200 rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow';
                        fileItem.innerHTML = `
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 ${isImage ? 'bg-purple-100' : 'bg-blue-100'} rounded-lg flex items-center justify-center">
                                    ${isImage ? 
                                        `<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>` :
                                        `<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>`
                                    }
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 truncate max-w-xs">${file.name}</p>
                                    <p class="text-xs text-gray-600">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                                </div>
                            </div>
                            <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700 p-1 hover:bg-red-50 rounded transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        `;
                        fileList.appendChild(fileItem);
                    });
                } else {
                    // Hide file list container
                    fileListContainer.classList.add('hidden');
                }
            }
        </script>
    @endpush
@endsection
