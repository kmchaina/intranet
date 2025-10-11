<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventAdminController extends BaseAdminController
{
    /**
     * Display a listing of all events for admin management
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = $this->getAuthUser();

        $query = Event::query()
            ->with('organizer')
            ->withCount('rsvps');

        // Filter by creator (only Super Admin sees ALL, others see only what they created)
        if (!$user->isSuperAdmin()) {
            $query->where('created_by', $user->id);
        }

        // Search
        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->query('status')) {
            if ($status === 'upcoming') {
                $query->where('start_date', '>', now());
            } elseif ($status === 'ongoing') {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            } elseif ($status === 'past') {
                $query->where('end_date', '<', now());
            }
        }

        // Sort
        $sortBy = $request->query('sort_by', 'start_date');
        $sortOrder = $request->query('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $events = $query->paginate(20)->withQueryString();

        // Get stats (filtered by creator)
        $statsQuery = Event::query();
        if (!$user->isSuperAdmin()) {
            $statsQuery->where('created_by', $user->id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'upcoming' => (clone $statsQuery)->where('start_datetime', '>', now())->count(),
            'ongoing' => (clone $statsQuery)->where('start_datetime', '<=', now())->where('end_datetime', '>=', now())->count(),
            'past' => (clone $statsQuery)->where('end_datetime', '<', now())->count(),
        ];

        return view('admin.events.index', compact('events', 'stats'));
    }

    /**
     * Delete event
     */
    public function destroy(Event $event): RedirectResponse
    {
        // Check jurisdiction permission
        if (!$this->canManageEvent($event)) {
            abort(403, 'You do not have permission to delete this event.');
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Bulk delete events
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_ids' => 'required|array',
            'event_ids.*' => 'exists:events,id',
        ]);

        Event::whereIn('id', $validated['event_ids'])->delete();

        return redirect()->route('admin.events.index')
            ->with('success', count($validated['event_ids']) . ' event(s) deleted successfully!');
    }

    /**
     * Check if current user can manage this event
     */
    private function canManageEvent(Event $event): bool
    {
        $user = $this->getAuthUser();

        // Super admin can manage all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Others can only manage their own content
        return $event->created_by === $user->id;
    }
}
