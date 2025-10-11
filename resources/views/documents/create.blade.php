@extends('layouts.dashboard')
@section('title', 'Upload Document')
@section('page-title', 'Upload Document')
@section('page-subtitle', 'Share a new document with the organization')
@section('content')
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <x-breadcrumbs :items="[
                ['label' => 'Dashboard', 'href' => route('dashboard')],
                ['label' => 'Documents', 'href' => route('documents.index')],
                ['label' => 'Upload Document'],
            ]" />

            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <a href="{{ route('documents.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors mr-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Documents
                    </a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Upload Document</h1>
                <p class="text-gray-600 mt-2">Share a new document with the organization</p>
            </div>

            <!-- Upload Form -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8">
                    @csrf

                    <!-- File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-semibold text-gray-900 mb-3">
                            Document File <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors"
                            id="file-drop-zone">
                            <input type="file" id="file" name="file"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.mp4,.mp3"
                                class="hidden" required>
                            <div class="text-gray-500" id="file-drop-content">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="text-lg font-medium mb-2">Drop your file here or <span
                                        class="text-blue-600 cursor-pointer"
                                        onclick="document.getElementById('file').click()">browse</span></p>
                                <p class="text-sm">Supports: PDF, Word, Excel, PowerPoint, Images, Archives, Videos, Audio
                                </p>
                                <p class="text-sm mt-1">Maximum file size: 50MB</p>
                            </div>
                            <div id="file-selected" class="hidden">
                                <div class="flex items-center justify-center space-x-3">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span id="file-name" class="text-gray-900 font-medium"></span>
                                </div>
                                <button type="button" onclick="clearFile()"
                                    class="mt-3 text-sm text-red-600 hover:text-red-700">
                                    Remove file
                                </button>
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-900 mb-3">
                            Document Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 placeholder-gray-500"
                            placeholder="Enter a descriptive title for the document..." required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-3">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 placeholder-gray-500 resize-vertical"
                            placeholder="Provide a brief description of the document content...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category and Access Level -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category/Department -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-900 mb-3">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select id="category" name="category"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900"
                                required>
                                <option value="">Select a department</option>
                                @foreach ($departments as $key => $name)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Access Level -->
                        <div>
                            <label for="access_level" class="block text-sm font-semibold text-gray-900 mb-3">
                                Access Level <span class="text-red-500">*</span>
                            </label>
                            <select id="access_level" name="access_level"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900"
                                required>
                                <option value="">Select access level</option>
                                <option value="public" {{ old('access_level') === 'public' ? 'selected' : '' }}>Public
                                </option>
                                <option value="restricted" {{ old('access_level') === 'restricted' ? 'selected' : '' }}>
                                    Restricted</option>
                                <option value="confidential"
                                    {{ old('access_level') === 'confidential' ? 'selected' : '' }}>Confidential</option>
                            </select>
                            @error('access_level')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Visibility and Tags -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Visibility Scope -->
                        <div>
                            <label for="visibility_scope" class="block text-sm font-semibold text-gray-900 mb-3">
                                Visibility Scope <span class="text-red-500">*</span>
                            </label>
                            <select id="visibility_scope" name="visibility_scope"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900"
                                required>
                                <option value="all" {{ old('visibility_scope') === 'all' ? 'selected' : '' }}>All NIMR
                                    Staff</option>
                                <option value="headquarters"
                                    {{ old('visibility_scope') === 'headquarters' ? 'selected' : '' }}>Headquarters Only
                                </option>
                                <option value="centres" {{ old('visibility_scope') === 'centres' ? 'selected' : '' }}>All
                                    Centres</option>
                                <option value="stations" {{ old('visibility_scope') === 'stations' ? 'selected' : '' }}>
                                    All Stations</option>
                                <option value="specific" {{ old('visibility_scope') === 'specific' ? 'selected' : '' }}>
                                    Specific Centres/Stations</option>
                            </select>
                            @error('visibility_scope')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div>
                            <label for="tags" class="block text-sm font-semibold text-gray-900 mb-3">
                                Tags
                            </label>
                            <input type="text" id="tags" name="tags" value="{{ old('tags') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 placeholder-gray-500"
                                placeholder="Enter tags separated by commas (e.g., research, guidelines, 2025)">
                            <p class="mt-1 text-xs text-gray-500">Separate multiple tags with commas</p>
                            @error('tags')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Optional Settings -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Optional Settings</h3>
                        <div class="space-y-4">
                            <!-- Expiry Date -->
                            <div>
                                <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">
                                    Expiry Date
                                </label>
                                <input type="datetime-local" id="expires_at" name="expires_at"
                                    value="{{ old('expires_at') }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
                                <p class="mt-1 text-xs text-gray-500">Leave empty for permanent document</p>
                                @error('expires_at')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Download Permission -->
                            <div class="flex items-center">
                                <input type="checkbox" id="requires_download_permission"
                                    name="requires_download_permission" value="1"
                                    {{ old('requires_download_permission') ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="requires_download_permission" class="ml-3 text-sm text-gray-700">
                                    Require special permission to download this document
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit"
                            class="flex-1 sm:flex-initial inline-flex items-center justify-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Upload Document
                        </button>
                        <a href="{{ route('documents.index') }}"
                            class="flex-1 sm:flex-initial inline-flex items-center justify-center px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // File drag and drop functionality
            const fileInput = document.getElementById('file');
            const dropZone = document.getElementById('file-drop-zone');
            const dropContent = document.getElementById('file-drop-content');
            const fileSelected = document.getElementById('file-selected');
            const fileName = document.getElementById('file-name');

            // Handle file selection
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    showSelectedFile(e.target.files[0]);
                }
            });

            // Handle drag and drop
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('border-blue-400', 'bg-blue-50');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    showSelectedFile(files[0]);
                }
            });

            // Click to select file
            dropZone.addEventListener('click', function() {
                fileInput.click();
            });

            function showSelectedFile(file) {
                fileName.textContent = file.name;
                dropContent.classList.add('hidden');
                fileSelected.classList.remove('hidden');
            }

            function clearFile() {
                fileInput.value = '';
                fileName.textContent = '';
                dropContent.classList.remove('hidden');
                fileSelected.classList.add('hidden');
            }
        </script>
    @endpush
@endsection
