@extends('layouts.dashboard')
@section('title', 'Manage Policies')

@section('content')
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Manage Policies
                    </span>
                </h1>
                <p class="text-gray-600 mt-1">Official policies, guidelines, and SOPs</p>
            </div>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-policy-modal'))"
                class="btn btn-primary shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                </svg>
                Upload New Policy
            </button>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium uppercase">Total Policies</p>
                        <p class="text-3xl font-bold mt-1">{{ $policyStats['total'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium uppercase">This Month</p>
                        <p class="text-3xl font-bold mt-1">{{ $policyStats['published_this_month'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium uppercase">HQ Visible</p>
                        <p class="text-3xl font-bold mt-1">{{ $policyStats['hq_visible'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium uppercase">Restricted</p>
                        <p class="text-3xl font-bold mt-1">{{ $policyStats['restricted'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Policies</label>
                    <div class="relative">
                        <input type="search" name="search" value="{{ request('search') }}"
                            placeholder="Search by title or description..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Visibility</label>
                    <select name="visibility"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Visibility</option>
                        @foreach ($visibilityOptions as $scope)
                            <option value="{{ $scope }}" @selected(request('visibility') === $scope)>
                                {{ ucfirst($scope) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Access Level</label>
                    <select name="access_level"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Levels</option>
                        @foreach ($accessLevels as $level)
                            <option value="{{ $level }}" @selected(request('access_level') === $level)>
                                {{ ucfirst($level) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        {{-- Policies Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Policy Details
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Visibility
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Access Level
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Last Updated
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($policies as $policy)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $policy->title }}
                                            </p>
                                            @if ($policy->description)
                                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                                    {{ $policy->description }}
                                                </p>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $policy->original_name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ match ($policy->visibility_scope) {
                                            'all' => 'bg-blue-100 text-blue-700',
                                            'headquarters' => 'bg-purple-100 text-purple-700',
                                            'centres' => 'bg-green-100 text-green-700',
                                            'stations' => 'bg-orange-100 text-orange-700',
                                            'specific' => 'bg-indigo-100 text-indigo-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        } }}">
                                        {{ ucfirst($policy->visibility_scope) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ match ($policy->access_level) {
                                            'public' => 'bg-green-100 text-green-700',
                                            'restricted' => 'bg-yellow-100 text-yellow-700',
                                            'confidential' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        } }}">
                                        {{ ucfirst($policy->access_level) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $policy->updated_at->diffForHumans() }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('documents.show', $policy) }}"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                            title="View Policy">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('documents.download', $policy) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Download">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>

                                        <form id="deleteForm{{ $policy->id }}"
                                            action="{{ route('admin.policies.destroy', $policy) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="showConfirmModal({
                                                    type: 'danger',
                                                    title: 'Delete Policy?',
                                                    message: 'This action cannot be undone. The policy will be permanently removed.',
                                                    confirmText: 'Delete Policy',
                                                    onConfirm: () => document.getElementById('deleteForm{{ $policy->id }}').submit()
                                                })"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-gray-500 font-medium">No policy documents found</p>
                                        <p class="text-gray-400 text-sm mt-1">Upload your first policy to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($policies->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $policies->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Upload Modal --}}
    <div x-data="policyUploadModal()" x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div @click.away="close"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all"
            x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Upload Policy Document</h3>
                            <p class="text-blue-100 text-sm">Share an official policy with the organization</p>
                        </div>
                    </div>
                    <button type="button" @click="close"
                        class="text-white/80 hover:text-white transition-colors text-2xl leading-none">
                        &times;
                    </button>
                </div>
            </div>

            {{-- Modal Form --}}
            <form method="POST" action="{{ route('admin.policies.store') }}" enctype="multipart/form-data"
                class="p-6 space-y-5">
                @csrf

                <div class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Policy Title *</label>
                        <input type="text" name="title" required value="{{ old('title') }}"
                            placeholder="e.g., Data Protection Policy 2024"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description
                            <span class="text-gray-400 font-normal">(optional)</span>
                        </label>
                        <textarea name="description" rows="3" placeholder="Brief description of this policy..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                    </div>

                    {{-- Visibility & Access --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Visibility Scope *</label>
                            <select name="visibility_scope" x-model="visibilityScope" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @foreach ($visibilityOptions as $scope)
                                    <option value="{{ $scope }}">{{ ucfirst($scope) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Access Level *</label>
                            <select name="access_level" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @foreach ($accessLevels as $level)
                                    <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Specific Targets --}}
                    <div x-show="visibilityScope === 'specific'" class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-700 font-medium">
                                Select specific centres or stations to limit visibility
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Target Centres</label>
                            <div
                                class="max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50 space-y-2">
                                @foreach ($centres as $centre)
                                    <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                                        <input type="checkbox" name="target_centres[]" value="{{ $centre->id }}"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span>{{ $centre->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Target Stations</label>
                            <div
                                class="max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50 space-y-2">
                                @foreach ($stations as $station)
                                    <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                                        <input type="checkbox" name="target_stations[]" value="{{ $station->id }}"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span>{{ $station->name }}
                                            @if ($station->centre)
                                                <span class="text-gray-400">({{ $station->centre->name }})</span>
                                            @endif
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Additional Options --}}
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="requires_download_permission"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-5 h-5">
                            <span class="text-sm text-gray-700 font-medium">Require admin approval before download</span>
                        </label>
                    </div>

                    {{-- Tags --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tags
                            <span class="text-gray-400 font-normal">(comma separated)</span>
                        </label>
                        <input type="text" name="tags" value="{{ old('tags') }}"
                            placeholder="e.g., HR, Compliance, Safety"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    {{-- File Upload --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Policy Document *</label>
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                            <input type="file" name="file" id="policyFile"
                                accept="application/pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" required class="hidden"
                                onchange="document.getElementById('fileLabel').textContent = this.files[0]?.name || 'Choose file'">
                            <label for="policyFile" class="cursor-pointer">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span id="fileLabel" class="text-sm text-blue-600 font-medium">Choose file</span>
                                <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT (Max 50MB)
                                </p>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" @click="close"
                        class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-md transition-all">
                        Upload Policy
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Confirmation Modal Component --}}
    <x-confirm-modal />

    @push('scripts')
        <script>
            function policyUploadModal() {
                return {
                    open: false,
                    visibilityScope: 'all',
                    close() {
                        this.open = false;
                    },
                    init() {
                        window.addEventListener('open-policy-modal', () => {
                            this.open = true;
                        });
                    }
                }
            }
        </script>
    @endpush
@endsection
