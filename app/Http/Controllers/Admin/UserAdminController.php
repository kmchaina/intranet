<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Centre;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserAdminController extends Controller
{
    /**
     * Display a listing of users with their roles
     */
    public function index(Request $request): View
    {
        // Check if user is super admin
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can manage user roles.');
        }

        $query = User::with(['centre', 'station']);

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by centre
        if ($request->filled('centre_id')) {
            $query->where('centre_id', $request->centre_id);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('role')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $centres = Centre::where('is_active', true)->orderBy('name')->get();

        $roleCounts = [
            'super_admin' => User::where('role', 'super_admin')->count(),
            'hq_admin' => User::where('role', 'hq_admin')->count(),
            'centre_admin' => User::where('role', 'centre_admin')->count(),
            'station_admin' => User::where('role', 'station_admin')->count(),
            'staff' => User::where('role', 'staff')->count(),
        ];

        return view('admin.users.index', compact('users', 'centres', 'roleCounts'));
    }

    /**
     * Show the form for editing user roles
     */
    public function edit(User $user): View
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can manage user roles.');
        }

        $centres = Centre::where('is_active', true)->orderBy('name')->get();
        $stations = Station::where('is_active', true)->with('centre')->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'centres', 'stations'));
    }

    /**
     * Update user role and organizational assignment
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can manage user roles.');
        }

        $validated = $request->validate([
            'role' => 'required|in:super_admin,hq_admin,centre_admin,station_admin,staff',
            'centre_id' => 'nullable|exists:centres,id',
            'station_id' => 'nullable|exists:stations,id',
        ]);

        // Validation rules based on role
        if ($validated['role'] === 'centre_admin' && !$validated['centre_id']) {
            return back()->withErrors(['centre_id' => 'Centre Admins must be assigned to a centre.']);
        }

        if ($validated['role'] === 'station_admin' && !$validated['station_id']) {
            return back()->withErrors(['station_id' => 'Station Admins must be assigned to a station.']);
        }

        // If assigning to station, ensure centre is also set
        if ($validated['station_id']) {
            $station = Station::find($validated['station_id']);
            $validated['centre_id'] = $station->centre_id;
        }

        // Clear inappropriate assignments based on role
        if (in_array($validated['role'], ['super_admin', 'hq_admin'])) {
            $validated['centre_id'] = null;
            $validated['station_id'] = null;
        } elseif ($validated['role'] === 'centre_admin') {
            $validated['station_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "User role updated successfully! {$user->name} is now a " . str_replace('_', ' ', $validated['role']) . ".");
    }

    /**
     * Bulk role assignment
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can manage user roles.');
        }

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'bulk_role' => 'required|in:super_admin,hq_admin,centre_admin,station_admin,staff',
        ]);

        $count = User::whereIn('id', $validated['user_ids'])
            ->update(['role' => $validated['bulk_role']]);

        return redirect()->route('admin.users.index')
            ->with('success', "Updated {$count} users to " . str_replace('_', ' ', $validated['bulk_role']) . " role.");
    }

    /**
     * Get role suggestions based on user's organizational placement
     */
    public function getRoleSuggestions(): View
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can manage user roles.');
        }

        $suggestions = [
            'unassigned_centre_staff' => User::where('role', 'staff')
                ->whereNotNull('centre_id')
                ->whereNull('station_id')
                ->with('centre')
                ->get(),

            'unassigned_station_staff' => User::where('role', 'staff')
                ->whereNotNull('station_id')
                ->with(['centre', 'station'])
                ->get(),

            'hq_staff_without_admin' => User::where('role', 'staff')
                ->whereNull('centre_id')
                ->whereNull('station_id')
                ->get(),
        ];

        return view('admin.users.suggestions', compact('suggestions'));
    }
}
