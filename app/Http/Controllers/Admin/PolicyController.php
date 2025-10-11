<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use App\Models\Document;
use App\Models\Station;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PolicyController extends Controller
{
    private const VISIBILITY_OPTIONS = ['all', 'headquarters', 'centres', 'stations', 'specific'];
    private const ACCESS_LEVELS = ['public', 'restricted', 'confidential'];

    public function index(Request $request): View
    {
        $this->ensureAuthorized();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Document::query()
            ->where('category', 'policy')
            ->with(['uploader']);

        // Filter by creator (only Super Admin sees ALL, others see only what they uploaded)
        if (!$user->isSuperAdmin()) {
            $query->where('uploaded_by', $user->id);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('original_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('visibility') && in_array($request->visibility, self::VISIBILITY_OPTIONS, true)) {
            $query->where('visibility_scope', $request->visibility);
        }

        if ($request->filled('access_level') && in_array($request->access_level, self::ACCESS_LEVELS, true)) {
            $query->where('access_level', $request->access_level);
        }

        $policies = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();

        $basePolicyQuery = Document::query()->where('category', 'policy');

        // Filter stats by creator
        if (!$user->isSuperAdmin()) {
            $basePolicyQuery->where('uploaded_by', $user->id);
        }

        $policyStats = [
            'total' => (clone $basePolicyQuery)->count(),
            'published_this_month' => (clone $basePolicyQuery)->where('created_at', '>=', now()->startOfMonth())->count(),
            'hq_visible' => (clone $basePolicyQuery)->where('visibility_scope', 'headquarters')->count(),
            'restricted' => (clone $basePolicyQuery)->where('access_level', 'restricted')->count(),
        ];

        $centres = Centre::orderBy('name')->get(['id', 'name']);
        $stations = Station::orderBy('name')->with('centre:id,name')->get(['id', 'name', 'centre_id']);

        return view('admin.policies.index', [
            'policies' => $policies,
            'policyStats' => $policyStats,
            'visibilityOptions' => self::VISIBILITY_OPTIONS,
            'accessLevels' => self::ACCESS_LEVELS,
            'centres' => $centres,
            'stations' => $stations,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAuthorized();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'visibility_scope' => 'required|in:' . implode(',', self::VISIBILITY_OPTIONS),
            'access_level' => 'required|in:' . implode(',', self::ACCESS_LEVELS),
            'requires_download_permission' => 'sometimes|boolean',
            'file' => 'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt',
            'target_centres' => 'nullable|array',
            'target_centres.*' => 'exists:centres,id',
            'target_stations' => 'nullable|array',
            'target_stations.*' => 'exists:stations,id',
            'tags' => 'nullable|string|max:255',
        ]);

        if ($data['visibility_scope'] === 'specific' && empty($data['target_centres']) && empty($data['target_stations'])) {
            throw ValidationException::withMessages([
                'target_centres' => 'Select at least one centre or station when using the specific visibility option.',
            ]);
        }

        $file = $request->file('file');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('documents/policies', $fileName, 'public');

        $tags = null;
        if (!empty($data['tags'])) {
            $tags = collect(explode(',', $data['tags']))
                ->map(fn($tag) => trim($tag))
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        Document::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_name' => $fileName,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'category' => 'policy',
            'access_level' => $data['access_level'],
            'visibility_scope' => $data['visibility_scope'],
            'target_centres' => $data['target_centres'] ?? null,
            'target_stations' => $data['target_stations'] ?? null,
            'requires_download_permission' => $request->boolean('requires_download_permission'),
            'uploaded_by' => Auth::id(),
            'tags' => $tags,
            'is_active' => true,
        ]);

        // Redirect based on role
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.policies.index')
                ->with('success', 'Policy uploaded successfully.');
        }

        return redirect()->route('documents.index')
            ->with('success', 'Policy uploaded successfully.');
    }

    public function destroy(Document $policy): RedirectResponse
    {
        $this->ensureAuthorized();

        if ($policy->category !== 'policy') {
            abort(404);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if user can delete this policy
        if (!$user->isSuperAdmin() && $policy->uploaded_by !== $user->id) {
            abort(403, 'You do not have permission to delete this policy.');
        }

        if (Storage::disk('public')->exists($policy->file_path)) {
            Storage::disk('public')->delete($policy->file_path);
        }

        $policy->delete();

        return redirect()->route('admin.policies.index')
            ->with('success', 'Policy deleted successfully.');
    }

    private function ensureAuthorized(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user instanceof \App\Models\User || (!$user->isSuperAdmin() && !$user->isHqAdmin())) {
            abort(403);
        }
    }
}
