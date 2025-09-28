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
    $query = User::with(['centre', 'station', 'headquarters', 'department']);
        
        // Search by name only
        if ($request->filled('search')) {
            $search = trim((string) $request->string('search'));
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by centre
        if ($request->has('centre') && $request->centre) {
            $query->where('centre_id', $request->centre);
        }

        // Station filter removed by request; station admin scoping remains below

        // Role filter removed (keeping only name and centre per request)

        // Filter based on user's access level
        $user = auth()->user();
        if ($user->role === 'centre_admin' && $user->centre_id) {
            $query->where('centre_id', $user->centre_id);
        } elseif ($user->role === 'station_admin' && $user->station_id) {
            $query->where('station_id', $user->station_id);
        }

    // Sorting and pagination
    $allowedSorts = ['name'];
    $sort = in_array($request->query('sort'), $allowedSorts, true) ? $request->query('sort') : 'name';
    $direction = 'asc';
    $perPage = (int) $request->query('per_page', 20);
    if ($perPage < 5) { $perPage = 5; }
    if ($perPage > 100) { $perPage = 100; }

    $staff = $query->orderBy($sort, $direction)->paginate($perPage);
        
    // Get filter options
    $centres = Centre::orderBy('name')->get();

    return view('staff.index', compact('staff', 'centres'));
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
