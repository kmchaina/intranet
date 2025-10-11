<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Centre;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of documents
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Document::active()
            ->forUser($user)
            ->with(['uploader'])
            ->orderBy('created_at', 'desc');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by access level
        if ($request->filled('access_level')) {
            $query->where('access_level', $request->access_level);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('original_name', 'LIKE', "%{$search}%");
            });
        }

        // Filter by tags
        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->tag);
        }

        $documents = $query->paginate(12)->withQueryString();

        // Get all departments from config
        $allDepartments = config('departments');

        // Get available filters
        $categories = Document::active()->forUser($user)->distinct()->pluck('category');
        $accessLevels = Document::active()->forUser($user)->distinct()->pluck('access_level');

        // Get all unique tags
        $allTags = Document::active()->forUser($user)
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        // Get category counts for department cards
        $categoryCounts = [];
        foreach (array_keys($allDepartments) as $deptKey) {
            $categoryCounts[$deptKey] = Document::active()->forUser($user)->where('category', $deptKey)->count();
        }

        // Add policy count (special category)
        $categoryCounts['policy'] = Document::active()->forUser($user)->where('category', 'policy')->count();

        // Get document statistics
        $stats = [
            'total' => Document::active()->forUser($user)->count(),
            'recent' => Document::active()->forUser($user)->where('created_at', '>=', now()->subWeek())->count(),
            'popular' => Document::active()->forUser($user)->where('download_count', '>', 0)->orderBy('download_count', 'desc')->take(10)->count(),
            'myDownloads' => 0, // TODO: Implement user download tracking
        ];

        return view('documents.index', compact('documents', 'categories', 'accessLevels', 'allTags', 'categoryCounts', 'stats', 'allDepartments'));
    }

    /**
     * Show the form for creating a new document
     */
    public function create(): View
    {
        // Only super admins and HQ admins can upload documents
        $user = Auth::user();
        if (!$user->isSuperAdmin() && !$user->isHqAdmin()) {
            abort(403, 'Only administrators can upload documents.');
        }

        $centres = Centre::where('is_active', true)->orderBy('name')->get();
        $stations = Station::where('is_active', true)->with('centre')->orderBy('name')->get();
        $departments = config('departments');

        return view('documents.create', compact('centres', 'stations', 'departments'));
    }

    /**
     * Store a newly created document
     */
    public function store(Request $request): RedirectResponse
    {
        // Only super admins and HQ admins can upload documents
        $user = Auth::user();
        if (!$user->isSuperAdmin() && !$user->isHqAdmin()) {
            abort(403, 'Only administrators can upload documents.');
        }

        $departmentKeys = implode(',', array_keys(config('departments')));

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif,zip,rar,mp4,mp3',
            'category' => "required|in:{$departmentKeys}",
            'access_level' => 'required|in:public,restricted,confidential',
            'visibility_scope' => 'required|in:all,headquarters,centres,stations,specific',
            'target_centres' => 'nullable|array',
            'target_centres.*' => 'exists:centres,id',
            'target_stations' => 'nullable|array',
            'target_stations.*' => 'exists:stations,id',
            'tags' => 'nullable|string',
            'requires_download_permission' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        // Validate specific targeting
        if ($validated['visibility_scope'] === 'specific') {
            if (empty($validated['target_centres']) && empty($validated['target_stations'])) {
                throw ValidationException::withMessages([
                    'visibility_scope' => 'When using specific targeting, you must select at least one centre or station.'
                ]);
            }
        }

        // Handle file upload
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('documents', $fileName, 'public');

        // Process tags
        $tags = null;
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $tags = array_filter($tags); // Remove empty tags
        }

        $document = Document::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'category' => $validated['category'],
            'access_level' => $validated['access_level'],
            'visibility_scope' => $validated['visibility_scope'],
            'target_centres' => $validated['target_centres'] ?? null,
            'target_stations' => $validated['target_stations'] ?? null,
            'tags' => $tags,
            'requires_download_permission' => $validated['requires_download_permission'] ?? false,
            'expires_at' => $validated['expires_at'] ?? null,
            'uploaded_by' => Auth::id(),
            'is_active' => true,
        ]);

        // Redirect based on user role - admins go to management page, staff to regular index
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.documents.index')
                ->with('success', 'Document uploaded successfully!');
        }

        return redirect()->route('documents.index')
            ->with('success', 'Document uploaded successfully!');
    }

    /**
     * Display the specified document
     */
    public function show(Document $document): View
    {
        $user = Auth::user();

        // Check if user can access this document
        $canAccess = Document::active()
            ->forUser($user)
            ->where('id', $document->id)
            ->exists();

        if (!$canAccess) {
            abort(403, 'You do not have permission to access this document.');
        }

        // Log view event
        \App\Services\ActivityLogger::log('document.view', 'document', $document->id, [
            'category' => $document->category,
            'access_level' => $document->access_level
        ]);

        // Load relationships
        $document->load(['uploader', 'versions' => function ($query) {
            $query->orderBy('version', 'desc');
        }]);

        return view('documents.show', compact('document'));
    }

    /**
     * View the document inline (for browser preview)
     */
    public function view(Document $document)
    {
        $user = Auth::user();

        // Check if user can access this document
        $canAccess = Document::active()
            ->forUser($user)
            ->where('id', $document->id)
            ->exists();

        if (!$canAccess) {
            abort(403, 'You do not have permission to view this document.');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        $filePath = Storage::disk('public')->path($document->file_path);
        $mimeType = $document->mime_type ?? mime_content_type($filePath);

        // Return file with inline disposition (view in browser)
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->original_name . '"'
        ]);
    }

    /**
     * Download the specified document
     */
    public function download(Document $document)
    {
        $user = Auth::user();

        // Check if user can access this document
        $canAccess = Document::active()
            ->forUser($user)
            ->where('id', $document->id)
            ->exists();

        if (!$canAccess) {
            abort(403, 'You do not have permission to download this document.');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        // Record the download and log event
        $document->recordDownload();
        \App\Services\ActivityLogger::log('document.download', 'document', $document->id, [
            'category' => $document->category,
            'access_level' => $document->access_level
        ]);

        $filePath = Storage::disk('public')->path($document->file_path);

        return response()->download($filePath, $document->original_name);
    }

    /**
     * Show the form for editing the specified document
     */
    public function edit(Document $document): View
    {
        // Only allow editing by document uploader or super admin
        if ($document->uploaded_by !== Auth::id() && Auth::user()->role !== 'super_admin') {
            abort(403, 'You can only edit documents you uploaded.');
        }

        $centres = Centre::where('is_active', true)->orderBy('name')->get();
        $stations = Station::where('is_active', true)->with('centre')->orderBy('name')->get();
        $departments = config('departments');

        return view('documents.edit', compact('document', 'centres', 'stations', 'departments'));
    }

    /**
     * Update the specified document
     */
    public function update(Request $request, Document $document): RedirectResponse
    {
        // Only allow editing by document uploader or super admin
        if ($document->uploaded_by !== Auth::id() && Auth::user()->role !== 'super_admin') {
            abort(403, 'You can only edit documents you uploaded.');
        }

        $departmentKeys = implode(',', array_keys(config('departments')));

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => "required|in:{$departmentKeys}",
            'access_level' => 'required|in:public,restricted,confidential',
            'visibility_scope' => 'required|in:all,headquarters,centres,stations,specific',
            'target_centres' => 'nullable|array',
            'target_centres.*' => 'exists:centres,id',
            'target_stations' => 'nullable|array',
            'target_stations.*' => 'exists:stations,id',
            'tags' => 'nullable|string',
            'requires_download_permission' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        // Validate specific targeting
        if ($validated['visibility_scope'] === 'specific') {
            if (empty($validated['target_centres']) && empty($validated['target_stations'])) {
                throw ValidationException::withMessages([
                    'visibility_scope' => 'When using specific targeting, you must select at least one centre or station.'
                ]);
            }
        }

        // Process tags
        $tags = null;
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $tags = array_filter($tags); // Remove empty tags
        }

        $document->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'access_level' => $validated['access_level'],
            'visibility_scope' => $validated['visibility_scope'],
            'target_centres' => $validated['target_centres'] ?? null,
            'target_stations' => $validated['target_stations'] ?? null,
            'tags' => $tags,
            'requires_download_permission' => $validated['requires_download_permission'] ?? false,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        // Redirect based on user role
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.documents.index')
                ->with('success', 'Document updated successfully!');
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document updated successfully!');
    }

    /**
     * Remove the specified document
     */
    public function destroy(Document $document): RedirectResponse
    {
        // Only allow deletion by document uploader or super admin
        if ($document->uploaded_by !== Auth::id() && Auth::user()->role !== 'super_admin') {
            abort(403, 'You can only delete documents you uploaded.');
        }

        // Delete the file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Delete the document record
        $document->delete();

        // Redirect based on user role
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.documents.index')
                ->with('success', 'Document deleted successfully!');
        }

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully!');
    }
}
