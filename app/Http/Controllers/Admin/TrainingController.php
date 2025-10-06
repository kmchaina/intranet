<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrainingController extends Controller
{
    protected function ensureAuthorized(): void
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isHqAdmin()) {
            abort(403);
        }
    }

    public function index(Request $request): View
    {
        $this->ensureAuthorized();

        $modules = TrainingModule::query()
            ->when($request->filled('search'), fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->filled('category'), fn($q) => $q->where('category', $request->category))
            ->when($request->filled('audience'), fn($q) => $q->where('target_audience', $request->audience))
            ->orderByDesc('created_at')
            ->paginate(10);

        $categories = TrainingModule::categories();
        $audiences = TrainingModule::audiences();

        return view('admin.training.index', compact('modules', 'categories', 'audiences'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAuthorized();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:' . implode(',', array_keys(TrainingModule::categories())),
            'delivery_mode' => 'required|string|max:255',
            'duration_minutes' => 'nullable|integer|min:1',
            'target_audience' => 'required|string|in:' . implode(',', array_keys(TrainingModule::audiences())),
            'resource_link' => 'nullable|url',
            'attachment' => 'nullable|file|max:20480',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('training', 'public');
        }

        $data['uploaded_by'] = Auth::id();

        TrainingModule::create($data);

        return back()->with('success', 'Training module created successfully.');
    }

    public function update(Request $request, TrainingModule $trainingModule): RedirectResponse
    {
        $this->ensureAuthorized();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:' . implode(',', array_keys(TrainingModule::categories())),
            'delivery_mode' => 'required|string|max:255',
            'duration_minutes' => 'nullable|integer|min:1',
            'target_audience' => 'required|string|in:' . implode(',', array_keys(TrainingModule::audiences())),
            'resource_link' => 'nullable|url',
            'attachment' => 'nullable|file|max:20480',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('attachment')) {
            if ($trainingModule->attachment_path) {
                Storage::disk('public')->delete($trainingModule->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')->store('training', 'public');
        }

        $trainingModule->update($data);

        return back()->with('success', 'Training module updated successfully.');
    }

    public function destroy(TrainingModule $trainingModule): RedirectResponse
    {
        $this->ensureAuthorized();

        if ($trainingModule->attachment_path) {
            Storage::disk('public')->delete($trainingModule->attachment_path);
        }

        $trainingModule->delete();

        return back()->with('success', 'Training module deleted successfully.');
    }
}
