@extends('layouts.dashboard')

@section('title', 'Policy Management')
@section('page-title', 'Policy Management')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data>
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Policy Library</h2>
                            <p class="text-sm text-gray-600">Share official policies, guidelines, and SOPs with staff.</p>
                        </div>
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-policy-modal'))"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                            Upload Policy
                        </button>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="search" name="search" value="{{ request('search') }}"
                            placeholder="Search policies..."
                            class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <select name="visibility"
                            class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All visibility</option>
                            @foreach ($visibilityOptions as $scope)
                                <option value="{{ $scope }}" @selected(request('visibility') === $scope)>{{ ucfirst($scope) }}
                                </option>
                            @endforeach
                        </select>
                        <select name="access_level"
                            class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All access levels</option>
                            @foreach ($accessLevels as $level)
                                <option value="{{ $level }}" @selected(request('access_level') === $level)>{{ ucfirst($level) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="px-4 py-2 bg-gray-900 hover:bg-black text-white rounded-lg text-sm">Apply</button>
                    </form>
                </div>

                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Visibility</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Access</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Updated</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($policies as $policy)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900">{{ $policy->title }}</span>
                                            <span
                                                class="text-xs text-gray-500">{{ Str::limit($policy->description, 70) }}</span>
                                            <span class="text-xs text-gray-400 mt-1">Uploaded by
                                                {{ $policy->uploader->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                            {{ ucfirst($policy->visibility_scope) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ ucfirst($policy->access_level) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $policy->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right space-x-2">
                                        <a href="{{ route('documents.download', $policy) }}"
                                            class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded">
                                            Download
                                        </a>
                                        @if ($policy->requires_download_permission)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 text-xs bg-amber-100 text-amber-700 rounded">
                                                Restricted
                                            </span>
                                        @endif
                                        <form action="{{ route('admin.policies.destroy', $policy) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this policy?')"
                                                class="inline-flex items-center px-3 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No policy documents found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $policies->links() }}
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-900">Policy Summary</h3>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-600">Total policies</dt>
                            <dd class="text-gray-900 font-semibold">{{ $policyStats['total'] }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-600">Added this month</dt>
                            <dd class="text-gray-900 font-semibold">{{ $policyStats['published_this_month'] }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-600">HQ visibility</dt>
                            <dd class="text-gray-900 font-semibold">{{ $policyStats['hq_visible'] }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-600">Restricted access</dt>
                            <dd class="text-gray-900 font-semibold">{{ $policyStats['restricted'] }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Need to target a policy?</h3>
                        <p class="text-xs text-gray-500 mt-1">Select specific centres or stations when uploading to limit
                            visibility.</p>
                    </div>
                    <div class="text-xs text-gray-600 space-y-2">
                        <p><span class="font-semibold">All:</span> visible to everyone.</p>
                        <p><span class="font-semibold">Headquarters:</span> HQ staff only.</p>
                        <p><span class="font-semibold">Centres / Stations:</span> staff tied to that level.</p>
                        <p><span class="font-semibold">Specific:</span> choose exact centres or stations.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-data="policyUploadModal()" x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4">
        <div @click.away="close" class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Upload Policy</h3>
                    <p class="text-xs text-gray-500 mt-1">Share an official policy document with the organization.</p>
                </div>
                <button type="button" @click="close" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.policies.store') }}" enctype="multipart/form-data"
                class="px-6 py-5 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" required value="{{ old('title') }}"
                            class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description <span
                                class="text-gray-400">(optional)</span></label>
                        <textarea name="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Visibility scope</label>
                        <select name="visibility_scope" x-model="visibilityScope"
                            class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @foreach ($visibilityOptions as $scope)
                                <option value="{{ $scope }}">{{ ucfirst($scope) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Access level</label>
                        <select name="access_level"
                            class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @foreach ($accessLevels as $level)
                                <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2" x-show="visibilityScope === 'specific'">
                        <label class="block text-sm font-medium text-gray-700">Target centres</label>
                        <div
                            class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2 max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-2">
                            @foreach ($centres as $centre)
                                <label class="flex items-center space-x-2 text-xs text-gray-600">
                                    <input type="checkbox" name="target_centres[]" value="{{ $centre->id }}"
                                        class="rounded border-gray-300 text-indigo-600">
                                    <span>{{ $centre->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="md:col-span-2" x-show="visibilityScope === 'specific'">
                        <label class="block text-sm font-medium text-gray-700">Target stations</label>
                        <div
                            class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2 max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-2">
                            @foreach ($stations as $station)
                                <label class="flex items-center space-x-2 text-xs text-gray-600">
                                    <input type="checkbox" name="target_stations[]" value="{{ $station->id }}"
                                        class="rounded border-gray-300 text-indigo-600">
                                    <span>{{ $station->name }} @if ($station->centre)
                                            <span class="text-gray-400">({{ $station->centre->name }})</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2 text-sm text-gray-600">
                            <input type="checkbox" name="requires_download_permission"
                                class="rounded border-gray-300 text-indigo-600">
                            <span>Require admin approval before download</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Tags <span class="text-gray-400">(comma
                                separated)</span></label>
                        <input type="text" name="tags" value="{{ old('tags') }}"
                            class="mt-1 block w-full border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Upload file</label>
                        <input type="file" name="file"
                            accept="application/pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" required
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="close"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Upload</button>
                </div>
            </form>
        </div>
    </div>

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
@endsection
