<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementAttachment;
use App\Models\Centre;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AnnouncementController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of announcements for the authenticated user
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $announcements = Announcement::where('is_published', true)
            ->with(['creator', 'attachments'])
            ->withCount('attachments')
            ->orderBy('priority', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        // Mark announcements as read when viewed
        foreach ($announcements as $announcement) {
            if (!$announcement->isReadBy($user)) {
                $announcement->markAsReadBy($user);
            }
        }

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create(): View
    {
        $this->authorize('create', Announcement::class);

        $centres = Centre::where('is_active', true)->get();
        $stations = Station::where('is_active', true)->with('centre')->get();

        return view('announcements.create', compact('centres', 'stations'));
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Announcement::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:general,urgent,info,event',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_scope' => 'required|in:all,headquarters,centres,stations,specific',
            'target_centres' => 'nullable|array',
            'target_centres.*' => 'exists:centres,id',
            'target_stations' => 'nullable|array',
            'target_stations.*' => 'exists:stations,id',
            'published_at' => 'nullable|date|after_or_equal:now',
            'expires_at' => 'nullable|date|after:published_at',
            'email_notification' => 'boolean',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,zip,rar',
        ]);

        // Validate specific targeting
        if ($validated['target_scope'] === 'specific') {
            if (empty($validated['target_centres']) && empty($validated['target_stations'])) {
                throw ValidationException::withMessages([
                    'target_scope' => 'When using specific targeting, you must select at least one centre or station.'
                ]);
            }
        }

        $announcement = Announcement::create([
            ...$validated,
            'created_by' => Auth::id(),
            'published_at' => $validated['published_at'] ?? now(),
            'is_published' => true,
            'email_notification' => $validated['email_notification'] ?? false,
        ]);

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('announcements/attachments', $fileName, 'public');

                $announcement->attachments()->create([
                    'original_name' => $originalName,
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Display the specified announcement
     */
    public function show(Announcement $announcement): View
    {
        $user = Auth::user();

        // Check if announcement is published and not expired
        if (!$announcement->is_published || 
            ($announcement->expires_at && $announcement->expires_at->isPast()) ||
            $announcement->published_at->isFuture()) {
            abort(404);
        }

        // Mark as read
        $announcement->markAsReadBy($user);

        $announcement->load(['creator', 'attachments']);

        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit(Announcement $announcement): View
    {
        $this->authorize('update', $announcement);

        $centres = Centre::where('is_active', true)->get();
        $stations = Station::where('is_active', true)->with('centre')->get();

        return view('announcements.edit', compact('announcement', 'centres', 'stations'));
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->authorize('update', $announcement);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:general,urgent,info,event',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_scope' => 'required|in:all,headquarters,centres,stations,specific',
            'target_centres' => 'nullable|array',
            'target_centres.*' => 'exists:centres,id',
            'target_stations' => 'nullable|array',
            'target_stations.*' => 'exists:stations,id',
            'expires_at' => 'nullable|date|after:now',
            'email_notification' => 'boolean',
        ]);

        // Validate specific targeting
        if ($validated['target_scope'] === 'specific') {
            if (empty($validated['target_centres']) && empty($validated['target_stations'])) {
                throw ValidationException::withMessages([
                    'target_scope' => 'When using specific targeting, you must select at least one centre or station.'
                ]);
            }
        }

        $announcement->update($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified announcement
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        $this->authorize('delete', $announcement);

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    /**
     * Mark announcement as read (AJAX endpoint)
     */
    public function markAsRead(Announcement $announcement)
    {
        $user = Auth::user();
        $announcement->markAsReadBy($user);

        return response()->json(['success' => true]);
    }

    /**
     * Download announcement attachment
     */
    public function downloadAttachment(AnnouncementAttachment $attachment)
    {
        // Check if user can view the announcement
        $this->authorize('view', $attachment->announcement);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File not found');
        }

        $filePath = Storage::disk('public')->path($attachment->file_path);

        return response()->download($filePath, $attachment->original_name);
    }
}
