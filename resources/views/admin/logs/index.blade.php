@extends('layouts.dashboard')

@section('title', 'System Logs')
@section('page-title', 'System Logs')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filename
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                            Modified</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($files as $file)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $file['name'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($file['size'] / 1024, 2) }} KB</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::createFromTimestamp($file['last_modified'])->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                <a href="{{ route('admin.logs.show', $file['name']) }}"
                                    class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                                    View
                                </a>
                                <a href="{{ route('admin.logs.download', $file['name']) }}"
                                    class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                    Download
                                </a>
                                <form method="POST" action="{{ route('admin.logs.destroy', $file['name']) }}"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded"
                                        onclick="return confirm('Delete this log file?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                No log files found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
