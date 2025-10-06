<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Centre') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.centres.index') }}"
                    class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Centres
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.centres.update', $centre) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Centre Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Centre Name
                                    *</label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $centre->name) }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Centre Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Centre Code
                                    *</label>
                                <input type="text" id="code" name="code"
                                    value="{{ old('code', $centre->code) }}" required maxlength="10"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('code') border-red-300 @enderror">
                                <p class="mt-1 text-sm text-gray-500">Short unique code for this centre (max 10
                                    characters)</p>
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                                <input type="text" id="location" name="location"
                                    value="{{ old('location', $centre->location) }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('location') border-red-300 @enderror">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description', $centre->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_person" class="block text-sm font-medium text-gray-700">Contact
                                        Person</label>
                                    <input type="text" id="contact_person" name="contact_person"
                                        value="{{ old('contact_person', $centre->contact_person) }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('contact_person') border-red-300 @enderror">
                                    @error('contact_person')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact
                                        Email</label>
                                    <input type="email" id="contact_email" name="contact_email"
                                        value="{{ old('contact_email', $centre->contact_email) }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('contact_email') border-red-300 @enderror">
                                    @error('contact_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700">Contact
                                    Phone</label>
                                <input type="text" id="contact_phone" name="contact_phone"
                                    value="{{ old('contact_phone', $centre->contact_phone) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('contact_phone') border-red-300 @enderror">
                                @error('contact_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="is_active" class="flex items-center">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                        {{ old('is_active', $centre->is_active) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Active Centre</span>
                                </label>
                                <p class="mt-1 text-sm text-gray-500">Inactive centres cannot have new users assigned to
                                    them</p>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.centres.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Update Centre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

