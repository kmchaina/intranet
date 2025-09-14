<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Centre;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Display a listing of staff members.
     */
    public function index(Request $request): View
    {
        $query = User::with(['centre', 'station', 'headquarters']);
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by centre
        if ($request->has('centre') && $request->centre) {
            $query->where('centre_id', $request->centre);
        }

        // Filter by station
        if ($request->has('station') && $request->station) {
            $query->where('station_id', $request->station);
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter based on user's access level
        $user = auth()->user();
        if ($user->role === 'centre_admin' && $user->centre_id) {
            $query->where('centre_id', $user->centre_id);
        } elseif ($user->role === 'station_admin' && $user->station_id) {
            $query->where('station_id', $user->station_id);
        }

        $staff = $query->orderBy('name')->paginate(20);
        
        // Get filter options
        $centres = Centre::orderBy('name')->get();
        $stations = Station::orderBy('name')->get();
        $roles = User::distinct()->whereNotNull('role')->pluck('role')->sort();

        return view('staff.index', compact('staff', 'centres', 'stations', 'roles'));
    }

    /**
     * Display the specified staff member.
     */
    public function show(User $staff): View
    {
        $staff->load(['centre', 'station', 'headquarters']);
        return view('staff.show', compact('staff'));
    }
}
