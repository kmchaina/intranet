<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\Centre;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of events (calendar default, list optional)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $view = $request->get('view', 'list'); // list is default, calendar optional
        if (!in_array($view, ['calendar', 'list'], true)) {
            $view = 'list';
        }
        $date = $request->get('date', now()->toDateString());
        $category = $request->get('category');

        // Get events visible to user
        $query = Event::forUser($user)->published();

        // Filter by category if specified
        if ($category) {
            $query->where('category', $category);
        }

        if ($view === 'calendar') {
            // Get events for calendar month view
            $startOfMonth = Carbon::parse($date)->startOfMonth()->startOfDay();
            $endOfMonth = Carbon::parse($date)->endOfMonth()->endOfDay();
            $events = $query->inDateRange($startOfMonth, $endOfMonth)
                ->with(['creator', 'rsvps'])
                ->orderBy('start_datetime')
                ->get();
        } else {
            // List view with pagination - sorted by date (ascending)
            $events = $query->with(['creator', 'rsvps'])
                ->orderBy('start_datetime', 'asc')
                ->paginate(20);
        }

        $categories = [
            'meeting' => 'Meetings',
            'training' => 'Training',
            'conference' => 'Conferences',
            'workshop' => 'Workshops',
            'seminar' => 'Seminars',
            'fieldwork' => 'Fieldwork',
            'social' => 'Social Events'
        ];

        return view('events.index', compact('events', 'view', 'date', 'category', 'categories'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        $user = Auth::user();

        // Get centres and stations for targeting
        $centres = Centre::orderBy('name')->get();
        $stations = Station::orderBy('name')->get();

        $categories = [
            'meeting' => 'Meeting',
            'training' => 'Training',
            'conference' => 'Conference',
            'workshop' => 'Workshop',
            'seminar' => 'Seminar',
            'fieldwork' => 'Fieldwork',
            'social' => 'Social Event'
        ];

        $priorities = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent'
        ];

        $visibilityScopes = [
            'all' => 'All NIMR Staff',
            'headquarters' => 'Headquarters Only',
            'centres' => 'All Research Centres',
            'stations' => 'All Research Stations',
            'my_centre' => 'My Centre Only',
            'my_station' => 'My Station Only',
            'specific' => 'Specific Centres/Stations'
        ];

        return view('events.create', compact('categories', 'priorities', 'visibilityScopes', 'centres', 'stations'));
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'all_day' => 'boolean',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'venue_details' => 'nullable|string',
            'category' => ['required', Rule::in(['meeting', 'training', 'conference', 'workshop', 'seminar', 'fieldwork', 'social'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'status' => ['required', Rule::in(['draft', 'published', 'cancelled'])],
            'is_recurring' => 'boolean',
            'recurrence_type' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurrence_interval' => 'nullable|integer|min:1',
            'recurrence_end_date' => 'nullable|date|after:start_datetime',
            'recurrence_days' => 'nullable|array',
            'requires_rsvp' => 'boolean',
            'max_attendees' => 'nullable|integer|min:1',
            'rsvp_deadline' => 'nullable|date|before:start_datetime',
            'visibility_scope' => ['required', Rule::in(['all', 'headquarters', 'centres', 'stations', 'my_centre', 'my_station', 'specific'])],
            'target_centres' => 'nullable|array',
            'target_centres.*' => 'exists:centres,id',
            'target_stations' => 'nullable|array',
            'target_stations.*' => 'exists:stations,id',
        ]);

        $event = Event::create([
            ...$request->all(),
            'created_by' => Auth::id(),
        ]);

        // Redirect based on user role - admins go to management page, staff to event detail
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.events.index')
                ->with('success', 'Event created successfully!');
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $user = Auth::user();

        // Check if user can view this event
        if (!Event::forUser($user)->where('id', $event->id)->exists()) {
            abort(403, 'You do not have permission to view this event.');
        }

        $event->load(['creator', 'rsvps.user']);

        $userRsvp = $event->rsvps()->where('user_id', $user->id)->first();

        return view('events.show', compact('event', 'userRsvp'));
    }

    /**
     * Show the form for editing the event
     */
    public function edit(Event $event)
    {
        $user = Auth::user();

        // Check permissions
        if ($event->created_by !== $user->id && $user->role === 'staff') {
            abort(403, 'You can only edit events you created.');
        }

        // Get centres and stations for targeting
        $centres = Centre::orderBy('name')->get();
        $stations = Station::orderBy('name')->get();

        $categories = [
            'meeting' => 'Meeting',
            'training' => 'Training',
            'conference' => 'Conference',
            'workshop' => 'Workshop',
            'seminar' => 'Seminar',
            'fieldwork' => 'Fieldwork',
            'social' => 'Social Event'
        ];

        $priorities = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent'
        ];

        $visibilityScopes = [
            'all' => 'All NIMR Staff',
            'headquarters' => 'Headquarters Only',
            'centres' => 'All Research Centres',
            'stations' => 'All Research Stations',
            'my_centre' => 'My Centre Only',
            'my_station' => 'My Station Only',
            'specific' => 'Specific Centres/Stations'
        ];

        return view('events.edit', compact('event', 'categories', 'priorities', 'visibilityScopes', 'centres', 'stations'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        $user = Auth::user();

        // Check permissions
        if ($event->created_by !== $user->id && $user->role === 'staff') {
            abort(403, 'You can only edit events you created.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'all_day' => 'boolean',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'venue_details' => 'nullable|string',
            'category' => ['required', Rule::in(['meeting', 'training', 'conference', 'workshop', 'seminar', 'fieldwork', 'social'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'status' => ['required', Rule::in(['draft', 'published', 'cancelled'])],
            'is_recurring' => 'boolean',
            'recurrence_type' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurrence_interval' => 'nullable|integer|min:1',
            'recurrence_end_date' => 'nullable|date|after:start_datetime',
            'recurrence_days' => 'nullable|array',
            'requires_rsvp' => 'boolean',
            'max_attendees' => 'nullable|integer|min:1',
            'rsvp_deadline' => 'nullable|date|before:start_datetime',
            'visibility_scope' => ['required', Rule::in(['all', 'headquarters', 'centres', 'stations', 'my_centre', 'my_station', 'specific'])],
            'target_centres' => 'nullable|array',
            'target_centres.*' => 'exists:centres,id',
            'target_stations' => 'nullable|array',
            'target_stations.*' => 'exists:stations,id',
        ]);

        $event->update($request->all());

        // Redirect based on user role
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.events.index')
                ->with('success', 'Event updated successfully!');
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event)
    {
        $user = Auth::user();

        // Check permissions
        if ($event->created_by !== $user->id && $user->role === 'staff') {
            abort(403, 'You can only delete events you created.');
        }

        $event->delete();

        // Redirect based on user role
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.events.index')
                ->with('success', 'Event deleted successfully!');
        }

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * RSVP to an event
     */
    public function rsvp(Request $request, Event $event)
    {
        $user = Auth::user();

        // Check if user can view this event
        if (!Event::forUser($user)->where('id', $event->id)->exists()) {
            abort(403, 'You do not have permission to RSVP to this event.');
        }

        // Check if RSVP is allowed
        if (!$event->can_rsvp) {
            return back()->with('error', 'RSVP is not available for this event.');
        }

        $request->validate([
            'status' => ['required', Rule::in(['attending', 'declined', 'maybe'])],
            'response_notes' => 'nullable|string|max:500',
            'dietary_requirements' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:255',
            'guest_count' => 'nullable|integer|min:0|max:5',
        ]);

        // Check if adding guests would exceed capacity
        if ($request->status === 'attending' && $event->max_attendees) {
            $currentAttendees = $event->attendees_count;
            $totalWithGuests = 1 + ($request->guest_count ?? 0);

            if ($currentAttendees + $totalWithGuests > $event->max_attendees) {
                return back()->with('error', 'Adding this many attendees would exceed the event capacity.');
            }
        }

        // Update or create RSVP
        EventRsvp::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $user->id],
            $request->all()
        );

        $statusMessage = match ($request->status) {
            'attending' => 'confirmed your attendance',
            'declined' => 'declined the invitation',
            'maybe' => 'marked yourself as maybe attending',
        };

        return redirect()->route('events.index')->with('success', "You have {$statusMessage} for this event.");
    }

    /**
     * Mark attendance for an event (for event organizers)
     */
    public function markAttendance(Request $request, Event $event)
    {
        $user = Auth::user();

        // Check permissions
        if ($event->created_by !== $user->id && $user->role === 'staff') {
            abort(403, 'Only event organizers can mark attendance.');
        }

        $request->validate([
            'attendances' => 'required|array',
            'attendances.*' => 'boolean',
        ]);

        foreach ($request->attendances as $rsvpId => $attended) {
            EventRsvp::where('id', $rsvpId)
                ->where('event_id', $event->id)
                ->update(['attended' => $attended]);
        }

        return back()->with('success', 'Attendance marked successfully!');
    }
}
