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
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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

        $query = User::with(['centre', 'station', 'headquarters']);

        // Search by name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('employee_id', 'LIKE', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Filter by centre
        if ($centreId = $request->input('centre_id')) {
            $query->where('centre_id', $centreId);
        }

        // Filter by verification status
        if ($status = $request->input('status')) {
            if ($status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->orderBy('role')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $centres = Centre::where('is_active', true)->orderBy('name')->get();
        $stations = Station::where('is_active', true)->with('centre')->orderBy('name')->get();

        // Calculate stats
        $stats = [
            'total' => User::count(),
            'active' => User::whereNotNull('email_verified_at')->count(),
            'new_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
        ];

        $roleCounts = [
            'super_admin' => User::where('role', 'super_admin')->count(),
            'hq_admin' => User::where('role', 'hq_admin')->count(),
            'centre_admin' => User::where('role', 'centre_admin')->count(),
            'station_admin' => User::where('role', 'station_admin')->count(),
            'staff' => User::where('role', 'staff')->count(),
        ];

        return view('admin.users.index', compact('users', 'centres', 'stations', 'stats', 'roleCounts'));
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
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
                'regex:/^[a-zA-Z0-9._%+-]+@nimr\.or\.tz$/'
            ],
            'role' => 'required|in:super_admin,hq_admin,centre_admin,station_admin,staff',
            'centre_id' => 'nullable|exists:centres,id',
            'station_id' => 'nullable|exists:stations,id',
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'hire_date' => 'nullable|date',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ], [
            'email.regex' => 'Email address must be from the @nimr.or.tz domain.'
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
            if ($station && $station->centre_id) {
                $validated['centre_id'] = $station->centre_id;
            } else {
                return back()->withErrors(['station_id' => 'Selected station is invalid or not assigned to a centre.']);
            }
        }

        // Clear inappropriate assignments based on role
        if (in_array($validated['role'], ['super_admin', 'hq_admin'])) {
            $validated['centre_id'] = null;
            $validated['station_id'] = null;
        } elseif ($validated['role'] === 'centre_admin') {
            $validated['station_id'] = null;
        }

        // Handle password update
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "User updated successfully! {$user->name} is now a " . str_replace('_', ' ', $validated['role']) . ".");
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

    /**
     * Show the form for creating a new user
     */
    public function create(): View
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can create users.');
        }

        $centres = Centre::where('is_active', true)->orderBy('name')->get();
        $stations = Station::where('is_active', true)->with('centre')->orderBy('name')->get();

        return view('admin.users.create', compact('centres', 'stations'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request): RedirectResponse
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can create users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@nimr\.or\.tz$/'
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:super_admin,hq_admin,centre_admin,station_admin,staff',
            'centre_id' => 'nullable|integer',
            'station_id' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id',
            'bio' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'hire_date' => 'nullable|date',
        ], [
            'email.regex' => 'Email address must be from the @nimr.or.tz domain.'
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

        // Hash password
        $validated['password'] = bcrypt($validated['password']);

        try {
            $user = User::create($validated);

            // Send email verification instead of auto-verifying
            event(new \Illuminate\Auth\Events\Registered($user));

            return redirect()->route('admin.users.index')
                ->with('success', "User created successfully! {$user->name} has been added to the system. An email verification has been sent to {$user->email}.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user): RedirectResponse
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can delete users.');
        }

        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        // Check if user has created content that would be orphaned
        $hasContent = $user->announcements()->count() > 0;

        if ($hasContent) {
            return redirect()->back()
                ->with('error', 'Cannot delete user. This user has created content (announcements, documents, or conversations). Please reassign or delete the content first.');
        }

        try {
            $userName = $user->name;
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', "User {$userName} has been deleted successfully.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
