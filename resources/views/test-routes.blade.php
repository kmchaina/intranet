@extends('layouts.dashboard')

@section('title', 'Test Document Route')

@section('content')
    <div class="p-6">
        <h1>Testing Document Routes</h1>

        @php
            $document = App\Models\Document::first();
        @endphp

        @if ($document)
            <div class="mt-4">
                <p><strong>Document ID:</strong> {{ $document->id }}</p>
                <p><strong>Document Title:</strong> {{ $document->title }}</p>
                <p><strong>Show URL:</strong> {{ route('documents.show', $document) }}</p>
                <p><strong>Download URL:</strong> {{ route('documents.download', $document) }}</p>

                <div class="mt-6 space-x-4">
                    <a href="{{ route('documents.show', $document) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                        Test View Button
                    </a>

                    <a href="{{ route('documents.download', $document) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg">
                        Test Download Button
                    </a>
                </div>
            </div>
        @else
            <p>No documents found in database.</p>
        @endif
    </div>
@endsection
