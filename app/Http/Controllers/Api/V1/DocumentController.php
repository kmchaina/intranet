<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Services\DocumentService;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DocumentController extends Controller
{
    public function __construct(
        private DocumentService $documentService
    ) {
        $this->middleware('auth:sanctum');
        $this->middleware('throttle:api');
    }

    /**
     * Display a listing of documents
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = $request->get('q');
        $filters = $request->only(['department_id', 'access_level', 'file_type', 'date_from', 'date_to']);
        $perPage = min($request->get('per_page', 15), 50); // Max 50 items per page

        $documents = $this->documentService->searchDocuments($query, $filters, $perPage);

        return DocumentResource::collection($documents);
    }

    /**
     * Store a newly created document
     */
    public function store(StoreDocumentRequest $request): JsonResponse
    {
        try {
            $document = $this->documentService->uploadDocument(
                $request->file('file'),
                $request->validated(),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => new DocumentResource($document)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified document
     */
    public function show(Document $document): JsonResponse
    {
        $this->authorize('view', $document);

        return response()->json([
            'success' => true,
            'data' => new DocumentResource($document->load(['department', 'uploader']))
        ]);
    }

    /**
     * Update the specified document
     */
    public function update(UpdateDocumentRequest $request, Document $document): JsonResponse
    {
        $this->authorize('update', $document);

        try {
            $document->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Document updated successfully',
                'data' => new DocumentResource($document)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified document
     */
    public function destroy(Document $document): JsonResponse
    {
        $this->authorize('delete', $document);

        if ($this->documentService->deleteDocument($document)) {
            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete document'
        ], 400);
    }

    /**
     * Download document
     */
    public function download(Document $document): JsonResponse
    {
        $this->authorize('view', $document);

        try {
            // Record download
            $this->documentService->recordDownload($document, request()->user());

            return response()->json([
                'success' => true,
                'download_url' => route('documents.download', $document),
                'filename' => $document->original_filename
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate download link',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get popular documents
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 10), 25);
        $documents = $this->documentService->getPopularDocuments($limit);

        return response()->json([
            'success' => true,
            'data' => DocumentResource::collection($documents)
        ]);
    }

    /**
     * Get recent documents
     */
    public function recent(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 10), 25);
        $documents = $this->documentService->getRecentDocuments($limit);

        return response()->json([
            'success' => true,
            'data' => DocumentResource::collection($documents)
        ]);
    }

    /**
     * Search documents
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
            'department_id' => 'nullable|exists:departments,id',
            'access_level' => 'nullable|in:public,restricted,confidential',
            'per_page' => 'nullable|integer|min:1|max:50'
        ]);

        $query = $request->get('q');
        $filters = $request->only(['department_id', 'access_level']);
        $perPage = $request->get('per_page', 15);

        $documents = $this->documentService->searchDocuments($query, $filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => DocumentResource::collection($documents)->response()->getData()
        ]);
    }

    /**
     * Get document statistics
     */
    public function statistics(): JsonResponse
    {
        $stats = $this->documentService->getDocumentStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
