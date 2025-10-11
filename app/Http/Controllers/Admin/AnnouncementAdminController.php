<?php

namespace App\Http\Controllers\Admin;

use App\Models\Announcement;
use App\Models\Centre;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementAdminController extends BaseAdminController
{
    /**
     * Display a listing of all announcements for admin management
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Announcement::query()
            ->with(['creator', 'attachments'])
            ->withCount(['attachments', 'readers']);

        // Filter by creator (only Super Admin sees ALL, others see only what they created)
        if (!$user->isSuperAdmin()) {
            $query->where('created_by', $user->id);
        }

        // Search
        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->query('status')) {
            if ($status === 'published') {
                $query->published();
            } elseif ($status === 'expired') {
                $query->where('expires_at', '<=', now());
            } elseif ($status === 'scheduled') {
                $query->where('published_at', '>', now());
            }
        }

        // Filter by creator (admin can see content created by specific users)
        if ($creatorId = $request->query('creator_id')) {
            $query->where('created_by', $creatorId);
        }

        // Sort
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $announcements = $query->paginate(20)->withQueryString();

        // Get stats (filtered by creator)
        $statsQuery = Announcement::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('created_by', $user->id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'published' => (clone $statsQuery)->published()->count(),
            'expired' => (clone $statsQuery)->where('expires_at', '<=', now())->count(),
            'high_priority' => (clone $statsQuery)->where('priority', 'high')->count(),
        ];

        return view('admin.announcements.index', compact('announcements', 'stats'));
    }

    /**
     * Edit announcement
     */
    public function edit(Announcement $announcement): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check jurisdiction permission
        if (!$user->canManageAnnouncement($announcement)) {
            abort(403, 'You do not have permission to edit this announcement.');
        }

        $centres = Centre::where('is_active', true)->get();
        $stations = Station::where('is_active', true)->with('centre')->get();

        // Get allowed scopes for the current admin
        $allowedScopes = $user->getAllowedTargetScopes();

        return view('admin.announcements.edit', compact('announcement', 'centres', 'stations', 'allowedScopes'));
    }

    /**
     * Update announcement
     */
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check jurisdiction permission
        if (!$user->canManageAnnouncement($announcement)) {
            abort(403, 'You do not have permission to update this announcement.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:general,urgent,event,policy,training',
            'priority' => 'required|in:low,medium,high',
            'target_scope' => 'required|in:all,headquarters,my_centre,my_centre_stations,my_station,all_centres,all_stations,specific',
            'target_centres' => 'nullable|array',
            'target_centres.*' => 'exists:centres,id',
            'target_stations' => 'nullable|array',
            'target_stations.*' => 'exists:stations,id',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'email_notification' => 'boolean',
        ]);

        $announcement->update([
            ...$validated,
            'email_notification' => $validated['email_notification'] ?? false,
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Delete announcement
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check jurisdiction permission
        if (!$user->canManageAnnouncement($announcement)) {
            abort(403, 'You do not have permission to delete this announcement.');
        }

        // Delete attachments
        foreach ($announcement->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    /**
     * Bulk delete announcements
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'announcement_ids' => 'required|array',
            'announcement_ids.*' => 'exists:announcements,id',
        ]);

        $announcements = Announcement::whereIn('id', $validated['announcement_ids'])->get();

        foreach ($announcements as $announcement) {
            // Delete attachments
            foreach ($announcement->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
                $attachment->delete();
            }
            $announcement->delete();
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', count($validated['announcement_ids']) . ' announcement(s) deleted successfully!');
    }

    /**
     * Toggle announcement publish status
     */
    public function togglePublish(Announcement $announcement): RedirectResponse
    {
        $announcement->update([
            'is_published' => !$announcement->is_published,
        ]);

        $status = $announcement->is_published ? 'published' : 'unpublished';

        return back()->with('success', "Announcement {$status} successfully!");
    }
}
