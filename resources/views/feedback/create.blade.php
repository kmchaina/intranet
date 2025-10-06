@extends('layouts.dashboard')
@section('title', 'Share Your Ideas')

@section('content')
    <div class="space-y-6">
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'href' => route('dashboard')],
            ['label' => 'Suggestions', 'href' => route('feedback.index')],
            ['label' => 'New Suggestion'],
        ]" />

        <div class="card-premium overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-8 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Share Your Ideas</h1>
                        <p class="text-white/90 mt-1">Help us improve NIMR with your suggestions</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('feedback.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-premium p-8">
                        <h2 class="text-xl font-bold text-nimr-neutral-900 mb-6">Your Suggestion</h2>

                        <div class="mb-6">
                            <label for="subject" class="block text-sm font-semibold text-nimr-neutral-900 mb-2">
                                What's your idea? <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                                placeholder="E.g., Add a staff parking area..."
                                class="input @error('subject') border-red-500 @enderror">
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="message" class="block text-sm font-semibold text-nimr-neutral-900 mb-2">
                                Tell us more <span class="text-red-500">*</span>
                            </label>
                            <textarea id="message" name="message" rows="6" required placeholder="Describe your suggestion..."
                                class="input @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="type"
                                    class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Type</label>
                                <select id="type" name="type" class="input">
                                    @foreach ($types as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('type', 'suggestion') === $key ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="category"
                                    class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Category</label>
                                <select id="category" name="category" class="input">
                                    @foreach ($categories as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('category', 'general') === $key ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="attachment_path"
                                class="block text-sm font-semibold text-nimr-neutral-900 mb-2">Attachment (Optional)</label>
                            <input type="file" id="attachment_path" name="attachment_path"
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="input">
                            <p class="mt-1 text-xs text-nimr-neutral-500">Add images or documents (Max: 10MB)</p>
                        </div>

                        <div class="bg-nimr-neutral-50 p-6 rounded-xl space-y-4">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="is_public" name="is_public" value="1"
                                    {{ old('is_public') ? 'checked' : '' }}
                                    class="mt-1 rounded border-nimr-neutral-300 text-nimr-primary-600">
                                <div class="flex-1">
                                    <label for="is_public" class="block text-sm font-semibold text-nimr-neutral-900">Make
                                        this suggestion public</label>
                                    <p class="text-sm text-nimr-neutral-600 mt-1">Let other staff see and upvote your
                                        suggestion</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="is_anonymous" name="is_anonymous" value="1"
                                    {{ old('is_anonymous') ? 'checked' : '' }}
                                    class="mt-1 rounded border-nimr-neutral-300 text-nimr-primary-600">
                                <div class="flex-1">
                                    <label for="is_anonymous"
                                        class="block text-sm font-semibold text-nimr-neutral-900">Submit anonymously</label>
                                    <p class="text-sm text-nimr-neutral-600 mt-1">Your name won't be shown</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-nimr-neutral-200">
                            <a href="{{ route('feedback.index') }}" class="btn btn-outline">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Submit Suggestion
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="card-premium overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-100">
                            <h3 class="text-lg font-bold text-nimr-neutral-900">💡 Tips</h3>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-3 text-sm text-nimr-neutral-700">
                                <li class="flex gap-2"><span class="text-green-600 font-bold">✓</span><span>Be specific
                                        about the problem</span></li>
                                <li class="flex gap-2"><span class="text-green-600 font-bold">✓</span><span>Explain how it
                                        helps</span></li>
                                <li class="flex gap-2"><span class="text-green-600 font-bold">✓</span><span>Make it public
                                        for more support!</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
