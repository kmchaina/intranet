@extends('layouts.dashboard')
@section('title', 'Create Announcement')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create New Announcement</h1>
                <p class="text-gray-600 mt-2">Share important information with your colleagues</p>
            </div>
            <a href="{{ route('announcements.index') }}" class="btn btn-outline inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Announcements
            </a>
        </div>

        {{-- Form --}}
        <div class="card-premium p-8">
            <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" class="input"
                        placeholder="Enter announcement title..." required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-900 mb-2">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="10" class="input resize-y"
                        placeholder="Write your announcement content here..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Attachments --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Attachments - Images, Documents, PDFs (Multiple files allowed)
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

                    {{-- Hidden file input - NEVER destroy this! --}}
                    <input type="file" id="attachments" name="attachments[]" multiple
                        accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.xls,.xlsx,.ppt,.pptx" class="hidden"
                        onchange="updateFileList(this)">

                    {{-- Upload Area --}}
                    <div id="uploadArea">
                        <div id="uploadPrompt"
                            class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors bg-gray-50/50">
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

                        {{-- Success state (shown when files are selected) --}}
                        <div id="uploadSuccess"
                            class="hidden border-2 border-dashed border-green-400 rounded-xl p-6 text-center bg-green-50/50">
                            <div class="flex items-center justify-center gap-2 text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium"><span id="successFileCount">0</span> file(s) selected</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Click "Add More Files" button below to add more</p>
                        </div>
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

                {{-- Category and Priority --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                        <select id="category" name="category" class="select">
                            <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General</option>
                            <option value="urgent" {{ old('category') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="event" {{ old('category') === 'event' ? 'selected' : '' }}>Event</option>
                            <option value="policy" {{ old('category') === 'policy' ? 'selected' : '' }}>Policy</option>
                            <option value="training" {{ old('category') === 'training' ? 'selected' : '' }}>Training
                            </option>
                        </select>
                        @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-900 mb-2">Priority</label>
                        <select id="priority" name="priority" class="select">
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Target Audience --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Target Audience <span
                            class="text-red-500">*</span></label>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-xl">
                        @if (in_array('all', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_all" name="target_scope" value="all"
                                    {{ old('target_scope', $allowedScopes[0] ?? 'all') === 'all' ? 'checked' : '' }}
                                    class="radio mt-0.5">
                                <label for="target_all" class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">All NIMR Staff</span>
                                    <span class="block text-xs text-gray-600 mt-0.5">Everyone in the organization
                                        will see this</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('headquarters', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_headquarters" name="target_scope" value="headquarters"
                                    {{ old('target_scope') === 'headquarters' ? 'checked' : '' }} class="radio mt-0.5">
                                <label for="target_headquarters" class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">Headquarters Only</span>
                                    <span class="block text-xs text-gray-600 mt-0.5">Only HQ-level staff will see
                                        this</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('my_centre', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_my_centre" name="target_scope" value="my_centre"
                                    {{ old('target_scope', !in_array('all', $allowedScopes) && in_array('my_centre', $allowedScopes) ? 'my_centre' : '') === 'my_centre' ? 'checked' : '' }}
                                    class="radio mt-0.5">
                                <label for="target_my_centre" class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">Centre Level</span>
                                    <span class="block text-xs text-gray-600 mt-0.5">Staff at the specific centre
                                        location</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('my_centre_stations', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_my_centre_stations" name="target_scope"
                                    value="my_centre_stations"
                                    {{ old('target_scope') === 'my_centre_stations' ? 'checked' : '' }}
                                    class="radio mt-0.5">
                                <label for="target_my_centre_stations" class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">Centre and Its Stations</span>
                                    <span class="block text-xs text-gray-600 mt-0.5">Staff at the centre and all associated
                                        stations</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('my_station', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_my_station" name="target_scope" value="my_station"
                                    {{ old('target_scope', !in_array('all', $allowedScopes) && !in_array('my_centre', $allowedScopes) ? 'my_station' : '') === 'my_station' ? 'checked' : '' }}
                                    class="radio mt-0.5">
                                <label for="target_my_station" class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">Station Level</span>
                                    <span class="block text-xs text-gray-600 mt-0.5">Staff at the specific station
                                        location</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('all_centres', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_all_centres" name="target_scope" value="all_centres"
                                    {{ old('target_scope') === 'all_centres' ? 'checked' : '' }} class="radio mt-0.5">
                                <label for="target_all_centres" class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">All Centres</span>
                                    <span class="block text-xs text-gray-600 mt-0.5">Staff in any centre (excludes
                                        stations)</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('all_stations', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_all_stations" name="target_scope" value="all_stations"
                                    {{ old('target_scope') === 'all_stations' ? 'checked' : '' }} class="radio mt-0.5">
                                <label for="target_all_stations" class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">All Stations</span>
                                    <span class="block text-xs text-gray-600 mt-0.5">Staff in any station</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('specific', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_specific" name="target_scope" value="specific"
                                    {{ old('target_scope') === 'specific' ? 'checked' : '' }} class="radio mt-0.5">
                                <label for="target_specific" class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">Specific
                                        Centres/Stations</span>
                                    <span class="block text-xs text-gray-600 mt-0.5">Manually select specific
                                        centres or stations</span>
                                </label>
                            </div>
                        @endif
                    </div>
                    @error('target_scope')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Specific Target Selection --}}
                <div id="specificTargetSection" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Select Centres</label>
                            <div class="space-y-2 max-h-60 overflow-y-auto bg-white rounded-xl p-4 border border-gray-200">
                                @foreach ($centres as $centre)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="centre_{{ $centre->id }}" name="target_centres[]"
                                            value="{{ $centre->id }}"
                                            {{ in_array($centre->id, old('target_centres', [])) ? 'checked' : '' }}
                                            class="checkbox">
                                        <label for="centre_{{ $centre->id }}"
                                            class="ml-2 text-sm text-gray-900">{{ $centre->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Select Stations</label>
                            <div class="space-y-2 max-h-60 overflow-y-auto bg-white rounded-xl p-4 border border-gray-200">
                                @foreach ($stations as $station)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="station_{{ $station->id }}" name="target_stations[]"
                                            value="{{ $station->id }}"
                                            {{ in_array($station->id, old('target_stations', [])) ? 'checked' : '' }}
                                            class="checkbox">
                                        <label for="station_{{ $station->id }}" class="ml-2 text-sm text-gray-900">
                                            {{ $station->name }}
                                            @if ($station->centre)
                                                <span class="text-xs text-gray-500">({{ $station->centre->name }})</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Publishing Options --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="published_at" class="block text-sm font-semibold text-gray-900 mb-2">Publish
                            Date (Optional)</label>
                        <input type="datetime-local" id="published_at" name="published_at"
                            value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" class="input">
                        <p class="mt-2 text-xs text-gray-600">Leave empty to publish immediately</p>
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-semibold text-gray-900 mb-2">Expiry Date
                            (Optional)</label>
                        <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at') }}"
                            class="input">
                        <p class="mt-2 text-xs text-gray-600">Leave empty for no expiry</p>
                    </div>
                </div>

                {{-- Email Notification --}}
                <div class="flex items-start bg-blue-50 p-4 rounded-xl">
                    <input type="checkbox" id="email_notification" name="email_notification" value="1"
                        {{ old('email_notification') ? 'checked' : '' }} class="checkbox mt-0.5">
                    <div class="ml-3">
                        <label for="email_notification" class="text-sm font-medium text-gray-900">
                            Send email notification to target audience
                        </label>
                        <p class="text-xs text-gray-600 mt-1">Recipients will receive an email notification</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="btn btn-primary inline-flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Publish Announcement
                    </button>
                    <a href="{{ route('announcements.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Target scope toggle
            document.addEventListener('DOMContentLoaded', function() {
                const targetRadios = document.querySelectorAll('input[name="target_scope"]');
                const specificSection = document.getElementById('specificTargetSection');

                function toggleSpecificSection() {
                    const selectedValue = document.querySelector('input[name="target_scope"]:checked').value;
                    specificSection.classList.toggle('hidden', selectedValue !== 'specific');
                }

                targetRadios.forEach(radio => radio.addEventListener('change', toggleSpecificSection));
                toggleSpecificSection();
            });

            // File upload handling with accumulation
            let accumulatedFiles = [];

            function updateFileList(input) {
                const fileList = document.getElementById('fileList');
                const fileListContainer = document.getElementById('fileListContainer');
                const fileCount = document.getElementById('fileCount');
                const uploadPrompt = document.getElementById('uploadPrompt');
                const uploadSuccess = document.getElementById('uploadSuccess');
                const successFileCount = document.getElementById('successFileCount');

                // Add new files to accumulated list (avoid duplicates)
                if (input.files && input.files.length > 0) {
                    const newFiles = Array.from(input.files);
                    newFiles.forEach(file => {
                        // Check if file with same name and size already exists
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

                // Clear and rebuild file list
                fileList.innerHTML = '';

                if (accumulatedFiles.length > 0) {
                    // Show success state, hide prompt
                    uploadPrompt.classList.add('hidden');
                    uploadSuccess.classList.remove('hidden');
                    successFileCount.textContent = accumulatedFiles.length;

                    // Show file list container
                    fileListContainer.classList.remove('hidden');
                    fileCount.textContent = accumulatedFiles.length;

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
                    // Show prompt, hide success
                    uploadPrompt.classList.remove('hidden');
                    uploadSuccess.classList.add('hidden');

                    // Hide file list container
                    fileListContainer.classList.add('hidden');
                }
            }

            function removeFile(index) {
                // Remove file from accumulated list
                accumulatedFiles.splice(index, 1);

                // Refresh the display
                refreshFileDisplay();
            }

            function refreshFileDisplay() {
                const fileList = document.getElementById('fileList');
                const fileListContainer = document.getElementById('fileListContainer');
                const fileCount = document.getElementById('fileCount');
                const uploadPrompt = document.getElementById('uploadPrompt');
                const uploadSuccess = document.getElementById('uploadSuccess');
                const successFileCount = document.getElementById('successFileCount');
                const input = document.getElementById('attachments');

                // Update the input with accumulated files
                const dt = new DataTransfer();
                accumulatedFiles.forEach(file => dt.items.add(file));
                input.files = dt.files;

                // Clear file list
                fileList.innerHTML = '';

                if (accumulatedFiles.length > 0) {
                    // Show success state, hide prompt
                    uploadPrompt.classList.add('hidden');
                    uploadSuccess.classList.remove('hidden');
                    successFileCount.textContent = accumulatedFiles.length;

                    // Show file list container
                    fileListContainer.classList.remove('hidden');
                    fileCount.textContent = accumulatedFiles.length;

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
                    // Show prompt, hide success
                    uploadPrompt.classList.remove('hidden');
                    uploadSuccess.classList.add('hidden');

                    // Hide file list container
                    fileListContainer.classList.add('hidden');
                }
            }
        </script>
    @endpush
@endsection
