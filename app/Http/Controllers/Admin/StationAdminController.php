<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\Centre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StationAdminController extends Controller
{
    public function index()
    {
        // Check if user is Centre Admin or higher
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isCentreAdmin() && !$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only Centre Administrators can manage stations.');
        }

        // Filter stations based on user's access level
        $query = Station::with(['centre', 'users'])
            ->withCount(['users']);

        if ($user->isCentreAdmin()) {
            // Centre Admin can only see stations in their centre
            $query->where('centre_id', $user->centre_id);
        }

        $stations = $query->orderBy('name')->get();

        return view('admin.stations.index', compact('stations'));
    }

    public function create()
    {
        // Check if user is Centre Admin or higher
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isCentreAdmin() && !$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only Centre Administrators can create stations.');
        }

        // Get centres the user can assign stations to
        if ($user->isSuperAdmin() || $user->isHqAdmin()) {
            $centres = Centre::where('is_active', true)->orderBy('name')->get();
        } else {
            // Centre Admin can only create stations in their centre
            $centres = Centre::where('id', $user->centre_id)->where('is_active', true)->get();
        }

        return view('admin.stations.create', compact('centres'));
    }

    public function store(Request $request)
    {
        // Check if user is Centre Admin or higher
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isCentreAdmin() && !$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only Centre Administrators can create stations.');
        }

        // Validate centre access
        $centreId = $request->input('centre_id');
        if ($user->isCentreAdmin() && $centreId != $user->centre_id) {
            abort(403, 'You can only create stations in your assigned centre.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:stations,code',
            'centre_id' => 'required|exists:centres,id',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $station = Station::create([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'centre_id' => $validated['centre_id'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'contact_person' => $validated['contact_person'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            DB::commit();

            return redirect()->route('admin.stations.index')
                ->with('success', 'Station created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create station: ' . $e->getMessage());
        }
    }

    public function show(Station $station)
    {
        // Check if user can access this station
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->canAccessStation($station)) {
            abort(403, 'You do not have permission to view this station.');
        }

        $station->load(['centre', 'users']);

        return view('admin.stations.show', compact('station'));
    }

    public function edit(Station $station)
    {
        // Check if user can edit this station
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->canAccessStation($station)) {
            abort(403, 'You do not have permission to edit this station.');
        }

        // Get centres the user can assign stations to
        if ($user->isSuperAdmin() || $user->isHqAdmin()) {
            $centres = Centre::where('is_active', true)->orderBy('name')->get();
        } else {
            // Centre Admin can only assign stations to their centre
            $centres = Centre::where('id', $user->centre_id)->where('is_active', true)->get();
        }

        return view('admin.stations.edit', compact('station', 'centres'));
    }

    public function update(Request $request, Station $station)
    {
        // Check if user can edit this station
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->canAccessStation($station)) {
            abort(403, 'You do not have permission to edit this station.');
        }

        // Validate centre access
        $centreId = $request->input('centre_id');
        if ($user->isCentreAdmin() && $centreId != $user->centre_id) {
            abort(403, 'You can only assign stations to your assigned centre.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:stations,code,' . $station->id,
            'centre_id' => 'required|exists:centres,id',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $station->update([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'centre_id' => $validated['centre_id'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'contact_person' => $validated['contact_person'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            DB::commit();

            return redirect()->route('admin.stations.index')
                ->with('success', 'Station updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update station: ' . $e->getMessage());
        }
    }

    public function destroy(Station $station)
    {
        // Check if user can delete this station
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->canAccessStation($station)) {
            abort(403, 'You do not have permission to delete this station.');
        }

        // Only Super Admin can delete stations
        if (!$user->isSuperAdmin()) {
            abort(403, 'Only Super Administrators can delete stations.');
        }

        try {
            DB::beginTransaction();

            // Check if station has users
            if ($station->users()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete station with assigned users. Please reassign users first.');
            }

            $station->delete();

            DB::commit();

            return redirect()->route('admin.stations.index')
                ->with('success', 'Station deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete station: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Station $station)
    {
        // Check if user can edit this station
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->canAccessStation($station)) {
            abort(403, 'You do not have permission to edit this station.');
        }

        try {
            $station->update(['is_active' => !$station->is_active]);

            $status = $station->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Station {$status} successfully!",
                'is_active' => $station->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update station status: ' . $e->getMessage()
            ]);
        }
    }

    public function getStats(Station $station)
    {
        // Check if user can access this station
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->canAccessStation($station)) {
            abort(403, 'You do not have permission to view this station.');
        }

        $stats = [
            'total_users' => $station->users()->count(),
            'active_users' => $station->users()->whereNotNull('email_verified_at')->count(),
            'total_announcements' => \App\Models\Announcement::whereHas('creator', function ($q) use ($station) {
                $q->where('station_id', $station->id);
            })->count(),
            'total_documents' => \App\Models\Document::whereHas('uploader', function ($q) use ($station) {
                $q->where('station_id', $station->id);
            })->count(),
        ];

        return response()->json($stats);
    }
}
