@extends('layouts.dashboard')
@section('title', 'Create Announcement')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-nimr-neutral-900">Create New Announcement</h1>
                <p class="text-nimr-neutral-600 mt-2">Share important information with your colleagues</p>
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
                    <label for="title" class="block text-sm font-semibold text-nimr-neutral-900 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                        class="w-full px-4 py-3 bg-white border border-nimr-neutral-300 rounded-xl focus:ring-2 focus:ring-nimr-primary-500 focus:border-nimr-primary-500 text-nimr-neutral-900 placeholder:text-nimr-neutral-400"
                        placeholder="Enter announcement title..." required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div>
                    <label for="content" class="block text-sm font-semibold text-nimr-neutral-900 mb-2">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="10"
                        class="w-full px-4 py-3 bg-white border border-nimr-neutral-300 rounded-xl focus:ring-2 focus:ring-nimr-primary-500 focus:border-nimr-primary-500 text-nimr-neutral-900 placeholder:text-nimr-neutral-400 resize-y"
                        placeholder="Write your announcement content here..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Attachments --}}
                <div>
                    <label class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Attachments (Optional)</label>
                    <div class="border-2 border-dashed border-nimr-neutral-300 rounded-xl p-8 text-center hover:border-nimr-primary-400 transition-colors bg-nimr-neutral-50/50"
                        id="uploadArea">
                        <input type="file" id="attachments" name="attachments[]" multiple
                            accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.xls,.xlsx,.ppt,.pptx"
                            class="hidden" onchange="updateFileList(this)">
                        <label for="attachments" class="cursor-pointer block">
                            <svg class="w-16 h-16 mx-auto text-nimr-neutral-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-lg text-nimr-neutral-700 font-medium mb-2">
                                <span class="text-nimr-primary-600">Click to upload files</span> or drag and drop
                            </p>
                            <p class="text-sm text-nimr-neutral-500">
                                PDF, DOC, XLS, PPT, images, ZIP files up to 10MB each
                            </p>
                        </label>
                    </div>
                    <div id="fileList" class="mt-4 space-y-2"></div>
                    @error('attachments.*')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category and Priority --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category"
                            class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Category</label>
                        <select id="category" name="category"
                            class="w-full px-4 py-3 bg-white border border-nimr-neutral-300 rounded-xl focus:ring-2 focus:ring-nimr-primary-500 focus:border-nimr-primary-500 text-nimr-neutral-900">
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
                        <label for="priority"
                            class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Priority</label>
                        <select id="priority" name="priority"
                            class="w-full px-4 py-3 bg-white border border-nimr-neutral-300 rounded-xl focus:ring-2 focus:ring-nimr-primary-500 focus:border-nimr-primary-500 text-nimr-neutral-900">
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
                    <label class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Target Audience <span
                            class="text-red-500">*</span></label>
                    <div class="p-3 mb-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>💡 Tip:</strong> Select <strong>"All NIMR Staff"</strong> to make this announcement
                            visible to everyone in the organization.
                        </p>
                    </div>
                    <div class="space-y-3 bg-nimr-neutral-50 p-4 rounded-xl">
                        @if (in_array('all', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_all" name="target_scope" value="all"
                                    {{ old('target_scope', $allowedScopes[0] ?? 'all') === 'all' ? 'checked' : '' }}
                                    class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 focus:ring-nimr-primary-500 focus:ring-2 mt-0.5">
                                <label for="target_all" class="ml-3">
                                    <span class="block text-sm font-medium text-nimr-neutral-900">All NIMR Staff</span>
                                    <span class="block text-xs text-nimr-neutral-600 mt-0.5">Everyone in the organization
                                        will see this</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('headquarters', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_headquarters" name="target_scope" value="headquarters"
                                    {{ old('target_scope') === 'headquarters' ? 'checked' : '' }}
                                    class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 focus:ring-nimr-primary-500 focus:ring-2 mt-0.5">
                                <label for="target_headquarters" class="ml-3">
                                    <span class="block text-sm font-medium text-nimr-neutral-900">Headquarters Only</span>
                                    <span class="block text-xs text-nimr-neutral-600 mt-0.5">Only HQ-level staff will see
                                        this</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('my_centre', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_my_centre" name="target_scope" value="my_centre"
                                    {{ old('target_scope', !in_array('all', $allowedScopes) && in_array('my_centre', $allowedScopes) ? 'my_centre' : '') === 'my_centre' ? 'checked' : '' }}
                                    class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 focus:ring-nimr-primary-500 focus:ring-2 mt-0.5">
                                <label for="target_my_centre" class="ml-3">
                                    <span class="block text-sm font-medium text-nimr-neutral-900">My Centre Only</span>
                                    <span class="block text-xs text-nimr-neutral-600 mt-0.5">Only staff in your
                                        centre</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('my_centre_stations', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_my_centre_stations" name="target_scope"
                                    value="my_centre_stations"
                                    {{ old('target_scope') === 'my_centre_stations' ? 'checked' : '' }}
                                    class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 focus:ring-nimr-primary-500 focus:ring-2 mt-0.5">
                                <label for="target_my_centre_stations" class="ml-3">
                                    <span class="block text-sm font-medium text-nimr-neutral-900">My Centre and Its
                                        Stations</span>
                                    <span class="block text-xs text-nimr-neutral-600 mt-0.5">Staff in your centre and all
                                        its stations</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('my_station', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_my_station" name="target_scope" value="my_station"
                                    {{ old('target_scope', !in_array('all', $allowedScopes) && !in_array('my_centre', $allowedScopes) ? 'my_station' : '') === 'my_station' ? 'checked' : '' }}
                                    class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 focus:ring-nimr-primary-500 focus:ring-2 mt-0.5">
                                <label for="target_my_station" class="ml-3">
                                    <span class="block text-sm font-medium text-nimr-neutral-900">My Station Only</span>
                                    <span class="block text-xs text-nimr-neutral-600 mt-0.5">Only staff in your
                                        station</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('all_centres', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_all_centres" name="target_scope" value="all_centres"
                                    {{ old('target_scope') === 'all_centres' ? 'checked' : '' }}
                                    class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 focus:ring-nimr-primary-500 focus:ring-2 mt-0.5">
                                <label for="target_all_centres" class="ml-3">
                                    <span class="block text-sm font-medium text-nimr-neutral-900">All Centres</span>
                                    <span class="block text-xs text-nimr-neutral-600 mt-0.5">Staff in any centre (excludes
                                        stations)</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('all_stations', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_all_stations" name="target_scope" value="all_stations"
                                    {{ old('target_scope') === 'all_stations' ? 'checked' : '' }}
                                    class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 focus:ring-nimr-primary-500 focus:ring-2 mt-0.5">
                                <label for="target_all_stations" class="ml-3">
                                    <span class="block text-sm font-medium text-nimr-neutral-900">All Stations</span>
                                    <span class="block text-xs text-nimr-neutral-600 mt-0.5">Staff in any station</span>
                                </label>
                            </div>
                        @endif

                        @if (in_array('specific', $allowedScopes))
                            <div class="flex items-start">
                                <input type="radio" id="target_specific" name="target_scope" value="specific"
                                    {{ old('target_scope') === 'specific' ? 'checked' : '' }}
                                    class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 focus:ring-nimr-primary-500 focus:ring-2 mt-0.5">
                                <label for="target_specific" class="ml-3">
                                    <span class="block text-sm font-medium text-nimr-neutral-900">Specific
                                        Centres/Stations</span>
                                    <span class="block text-xs text-nimr-neutral-600 mt-0.5">Manually select specific
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
                            <label class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Select Centres</label>
                            <div
                                class="space-y-2 max-h-60 overflow-y-auto bg-white rounded-xl p-4 border border-nimr-neutral-200">
                                @foreach ($centres as $centre)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="centre_{{ $centre->id }}" name="target_centres[]"
                                            value="{{ $centre->id }}"
                                            {{ in_array($centre->id, old('target_centres', [])) ? 'checked' : '' }}
                                            class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 rounded focus:ring-nimr-primary-500 focus:ring-2">
                                        <label for="centre_{{ $centre->id }}"
                                            class="ml-2 text-sm text-nimr-neutral-900">{{ $centre->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Select Stations</label>
                            <div
                                class="space-y-2 max-h-60 overflow-y-auto bg-white rounded-xl p-4 border border-nimr-neutral-200">
                                @foreach ($stations as $station)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="station_{{ $station->id }}" name="target_stations[]"
                                            value="{{ $station->id }}"
                                            {{ in_array($station->id, old('target_stations', [])) ? 'checked' : '' }}
                                            class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 rounded focus:ring-nimr-primary-500 focus:ring-2">
                                        <label for="station_{{ $station->id }}"
                                            class="ml-2 text-sm text-nimr-neutral-900">
                                            {{ $station->name }}
                                            @if ($station->centre)
                                                <span
                                                    class="text-xs text-nimr-neutral-500">({{ $station->centre->name }})</span>
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
                        <label for="published_at" class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Publish
                            Date (Optional)</label>
                        <input type="datetime-local" id="published_at" name="published_at"
                            value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                            class="w-full px-4 py-3 bg-white border border-nimr-neutral-300 rounded-xl focus:ring-2 focus:ring-nimr-primary-500 focus:border-nimr-primary-500 text-nimr-neutral-900">
                        <p class="mt-2 text-xs text-nimr-neutral-600">Leave empty to publish immediately</p>
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Expiry Date
                            (Optional)</label>
                        <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at') }}"
                            class="w-full px-4 py-3 bg-white border border-nimr-neutral-300 rounded-xl focus:ring-2 focus:ring-nimr-primary-500 focus:border-nimr-primary-500 text-nimr-neutral-900">
                        <p class="mt-2 text-xs text-nimr-neutral-600">Leave empty for no expiry</p>
                    </div>
                </div>

                {{-- Email Notification --}}
                <div class="flex items-start bg-nimr-primary-50 p-4 rounded-xl">
                    <input type="checkbox" id="email_notification" name="email_notification" value="1"
                        {{ old('email_notification') ? 'checked' : '' }}
                        class="w-4 h-4 text-nimr-primary-600 bg-white border-nimr-neutral-300 rounded focus:ring-nimr-primary-500 focus:ring-2 mt-0.5">
                    <div class="ml-3">
                        <label for="email_notification" class="text-sm font-medium text-nimr-neutral-900">
                            Send email notification to target audience
                        </label>
                        <p class="text-xs text-nimr-neutral-600 mt-1">Recipients will receive an email notification</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-nimr-neutral-200">
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

            // File upload handling
            function updateFileList(input) {
                console.log('updateFileList called with', input.files.length, 'files');
                const fileList = document.getElementById('fileList');
                const uploadArea = document.getElementById('uploadArea');
                fileList.innerHTML = '';

                if (input.files.length > 0) {
                    // Update upload area to show files are selected
                    uploadArea.className =
                        'border-2 border-dashed border-green-400 rounded-xl p-4 text-center bg-green-50/50 transition-colors';
                    uploadArea.innerHTML = `
                        <div class="flex items-center justify-center gap-2 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">${input.files.length} file(s) selected</span>
                        </div>
                    `;

                    Array.from(input.files).forEach((file, index) => {
                        console.log('Processing file:', file.name, file.size);
                        const fileItem = document.createElement('div');
                        fileItem.className =
                            'flex items-center justify-between bg-white border border-nimr-neutral-200 rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow';
                        fileItem.innerHTML = `
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-nimr-primary-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-nimr-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-nimr-neutral-900 truncate max-w-xs">${file.name}</p>
                                    <p class="text-xs text-nimr-neutral-600">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
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
                    console.log('No files selected');
                    // Reset upload area
                    uploadArea.className =
                        'border-2 border-dashed border-nimr-neutral-300 rounded-xl p-8 text-center hover:border-nimr-primary-400 transition-colors bg-nimr-neutral-50/50';
                    uploadArea.innerHTML = `
                        <input type="file" id="attachments" name="attachments[]" multiple
                            accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.xls,.xlsx,.ppt,.pptx" class="hidden"
                            onchange="updateFileList(this)">
                        <label for="attachments" class="cursor-pointer block">
                            <svg class="w-16 h-16 mx-auto text-nimr-neutral-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-lg text-nimr-neutral-700 font-medium mb-2">
                                <span class="text-nimr-primary-600">Click to upload files</span> or drag and drop
                            </p>
                            <p class="text-sm text-nimr-neutral-500">
                                PDF, DOC, XLS, PPT, images, ZIP files up to 10MB each
                            </p>
                        </label>
                    `;
                }
            }

            function removeFile(index) {
                console.log('removeFile called for index:', index);
                const input = document.getElementById('attachments');
                const dt = new DataTransfer();
                const files = Array.from(input.files);

                files.forEach((file, i) => {
                    if (i !== index) dt.items.add(file);
                });

                input.files = dt.files;
                updateFileList(input);
            }

            // Debug form submission
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const fileInput = document.getElementById('attachments');
                        console.log('Form submitting with', fileInput.files.length, 'files');
                        Array.from(fileInput.files).forEach((file, index) => {
                            console.log('File', index, ':', file.name, file.size);
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
