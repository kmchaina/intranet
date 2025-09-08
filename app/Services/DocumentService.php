<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DocumentService
{
    private const CACHE_TTL = 3600; // 1 hour
    private const MAX_FILE_SIZE = 52428800; // 50MB
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain',
        'image/jpeg',
        'image/png',
        'image/gif'
    ];

    /**
     * Upload and store a document
     */
    public function uploadDocument(UploadedFile $file, array $data, User $user): Document
    {
        // Validate file
        $this->validateFile($file);

        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);
        
        // Store file
        $path = $file->storeAs('documents', $filename, 'public');

        // Create document record
        $document = Document::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'access_level' => $data['access_level'] ?? 'public',
            'department_id' => $data['department_id'] ?? null,
            'uploaded_by' => $user->id,
        ]);

        // Clear relevant caches
        $this->clearDocumentCaches();

        // Log activity
        Log::info('Document uploaded', [
            'document_id' => $document->id,
            'user_id' => $user->id,
            'filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize()
        ]);

        return $document;
    }

    /**
     * Search documents with caching
     */
    public function searchDocuments(string $query = null, array $filters = [], int $perPage = 20)
    {
        $cacheKey = $this->generateSearchCacheKey($query, $filters, $perPage);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($query, $filters, $perPage) {
            return Document::with(['department:id,name', 'uploader:id,name'])
                ->when($query, function ($q) use ($query) {
                    $q->where(function ($subQuery) use ($query) {
                        $subQuery->where('title', 'LIKE', "%{$query}%")
                            ->orWhere('description', 'LIKE', "%{$query}%")
                            ->orWhere('original_filename', 'LIKE', "%{$query}%");
                    });
                })
                ->when($filters['department_id'] ?? null, function ($q) use ($filters) {
                    $q->where('department_id', $filters['department_id']);
                })
                ->when($filters['access_level'] ?? null, function ($q) use ($filters) {
                    $q->where('access_level', $filters['access_level']);
                })
                ->when($filters['file_type'] ?? null, function ($q) use ($filters) {
                    $q->where('mime_type', 'LIKE', $filters['file_type'] . '%');
                })
                ->when($filters['date_from'] ?? null, function ($q) use ($filters) {
                    $q->whereDate('created_at', '>=', $filters['date_from']);
                })
                ->when($filters['date_to'] ?? null, function ($q) use ($filters) {
                    $q->whereDate('created_at', '<=', $filters['date_to']);
                })
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        });
    }

    /**
     * Get popular documents with caching
     */
    public function getPopularDocuments(int $limit = 10)
    {
        return Cache::remember("popular_documents_{$limit}", self::CACHE_TTL, function () use ($limit) {
            return Document::with(['department:id,name'])
                ->where('is_active', true)
                ->orderBy('download_count', 'desc')
                ->limit($limit)
                ->get(['id', 'title', 'download_count', 'department_id', 'file_size', 'created_at']);
        });
    }

    /**
     * Get recent documents with caching
     */
    public function getRecentDocuments(int $limit = 10)
    {
        return Cache::remember("recent_documents_{$limit}", self::CACHE_TTL, function () use ($limit) {
            return Document::with(['department:id,name', 'uploader:id,name'])
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get(['id', 'title', 'department_id', 'uploaded_by', 'file_size', 'created_at']);
        });
    }

    /**
     * Record document download
     */
    public function recordDownload(Document $document, User $user): void
    {
        // Increment download count
        $document->increment('download_count');

        // Log download activity
        Log::info('Document downloaded', [
            'document_id' => $document->id,
            'user_id' => $user->id,
            'document_title' => $document->title
        ]);

        // Clear caches that include download counts
        Cache::forget("popular_documents_10");
        Cache::forget("popular_documents_5");
    }

    /**
     * Delete document and its file
     */
    public function deleteDocument(Document $document): bool
    {
        try {
            // Delete physical file
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Delete database record
            $document->delete();

            // Clear caches
            $this->clearDocumentCaches();

            Log::info('Document deleted', [
                'document_id' => $document->id,
                'title' => $document->title
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete document', [
                'document_id' => $document->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Get document statistics
     */
    public function getDocumentStatistics(): array
    {
        return Cache::remember('document_statistics', self::CACHE_TTL, function () {
            return [
                'total_documents' => Document::count(),
                'total_downloads' => Document::sum('download_count'),
                'total_file_size' => Document::sum('file_size'),
                'documents_this_month' => Document::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'average_file_size' => Document::avg('file_size'),
                'most_popular_type' => Document::selectRaw('mime_type, COUNT(*) as count')
                    ->groupBy('mime_type')
                    ->orderBy('count', 'desc')
                    ->first()?->mime_type,
            ];
        });
    }

    /**
     * Validate uploaded file
     */
    private function validateFile(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \InvalidArgumentException('File size exceeds maximum allowed size of 50MB.');
        }

        // Check mime type
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw new \InvalidArgumentException('File type not allowed.');
        }

        // Check if file is valid
        if (!$file->isValid()) {
            throw new \InvalidArgumentException('Invalid file upload.');
        }

        // Basic security check for executable files
        $extension = strtolower($file->getClientOriginalExtension());
        $dangerousExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'jar', 'php', 'asp'];
        
        if (in_array($extension, $dangerousExtensions)) {
            throw new \InvalidArgumentException('File type not allowed for security reasons.');
        }
    }

    /**
     * Generate unique filename
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid() . '.' . $extension;
    }

    /**
     * Generate cache key for search
     */
    private function generateSearchCacheKey(string $query = null, array $filters = [], int $perPage = 20): string
    {
        $keyData = [
            'query' => $query,
            'filters' => $filters,
            'per_page' => $perPage
        ];

        return 'documents_search_' . md5(json_encode($keyData));
    }

    /**
     * Clear document-related caches
     */
    private function clearDocumentCaches(): void
    {
        $patterns = [
            'documents_search_*',
            'popular_documents_*',
            'recent_documents_*',
            'document_statistics'
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }

        // In Redis, you might want to use Cache::tags(['documents'])->flush()
        // if you implement cache tagging
    }
}
