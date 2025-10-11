<?php

namespace App\Http\Controllers\Admin;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class DocumentAdminController extends BaseAdminController
{
    /**
     * Display a listing of all documents for admin management
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = $this->getAuthUser();

        $query = Document::query()->with('uploader');

        // Filter by uploader (only Super Admin sees ALL, others see only what they uploaded)
        if (!$user->isSuperAdmin()) {
            $query->where('uploaded_by', $user->id);
        }

        // Search
        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        // Filter by type
        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        // Sort
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $documents = $query->paginate(20)->withQueryString();

        // Get stats (filtered by uploader)
        $statsQuery = Document::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('uploaded_by', $user->id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'total_downloads' => (clone $statsQuery)->sum('download_count'),
            'total_size' => (clone $statsQuery)->sum('file_size'),
        ];

        return view('admin.documents.index', compact('documents', 'stats'));
    }

    /**
     * Delete document
     */
    public function destroy(Document $document): RedirectResponse
    {
        // Check jurisdiction permission
        if (!$this->canManageDocument($document)) {
            abort(403, 'You do not have permission to delete this document.');
        }

        // Delete file from storage
        Storage::disk('public')->delete($document->file_path);

        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document deleted successfully!');
    }

    /**
     * Bulk delete documents
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:documents,id',
        ]);

        $documents = Document::whereIn('id', $validated['document_ids'])->get();

        foreach ($documents as $document) {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
        }

        return redirect()->route('admin.documents.index')
            ->with('success', count($validated['document_ids']) . ' document(s) deleted successfully!');
    }

    /**
     * Check if current user can manage this document
     */
    private function canManageDocument(Document $document): bool
    {
        $user = $this->getAuthUser();

        // Super admin can manage all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Others can only manage their own content
        return $document->uploaded_by === $user->id;
    }
}
