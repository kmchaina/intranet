<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CentreUserController extends Controller
{
    private function ensureCentreAdmin(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user instanceof \App\Models\User || !$user->isCentreAdmin() || !$user->centre_id) {
            abort(403, 'Only Centre Administrators can perform this action.');
        }
    }

    public function index(): View
    {
        $this->ensureCentreAdmin();
        $admin = Auth::user();

        // Base query for users in this centre
        $query = User::where('centre_id', $admin->centre_id)
            ->with(['station', 'centre']);

        // Apply search filter
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if (request('role')) {
            $query->where('role', request('role'));
        }

        // Apply station filter
        if (request('station_id')) {
            $query->where('station_id', request('station_id'));
        }

        $users = $query->orderBy('name')->paginate(15);

        // Get separate collections for stats
        $centreStaff = User::where('centre_id', $admin->centre_id)
            ->whereNull('station_id')
            ->where('role', 'staff')
            ->get();

        $stationAdmins = User::where('centre_id', $admin->centre_id)
            ->whereNotNull('station_id')
            ->where('role', 'station_admin')
            ->get();

        $stationStaff = User::where('centre_id', $admin->centre_id)
            ->whereNotNull('station_id')
            ->where('role', 'staff')
            ->get();

        // Get stations for filter dropdown
        $stations = \App\Models\Station::where('centre_id', $admin->centre_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.users.centre.index', [
            'users' => $users,
            'centreStaff' => $centreStaff,
            'stationAdmins' => $stationAdmins,
            'stationStaff' => $stationStaff,
            'stations' => $stations,
        ]);
    }

    public function create(): View
    {
        $this->ensureCentreAdmin();
        $admin = Auth::user();

        $stations = \App\Models\Station::where('centre_id', $admin->centre_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.users.centre.create', [
            'stations' => $stations,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureCentreAdmin();
        $admin = Auth::user();

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
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id',
            'role' => 'required|in:staff,station_admin',
            'station_id' => 'nullable|exists:stations,id',
        ], [
            'email.regex' => 'Email address must be from the @nimr.or.tz domain.'
        ]);

        // Validate station assignment
        if ($validated['role'] === 'station_admin' && !$validated['station_id']) {
            return back()->withErrors(['station_id' => 'Station Admins must be assigned to a station.']);
        }

        // Ensure station belongs to admin's centre
        if ($validated['station_id']) {
            $station = \App\Models\Station::find($validated['station_id']);
            if (!$station || $station->centre_id !== $admin->centre_id) {
                return back()->withErrors(['station_id' => 'Selected station is not in your centre.']);
            }
        }

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'role' => $validated['role'],
            'centre_id' => $admin->centre_id,
        ];

        // Set station_id if provided
        if ($validated['station_id']) {
            $userData['station_id'] = $validated['station_id'];
        }

        $user = User::create($userData);

        // Send email verification instead of auto-verifying
        event(new \Illuminate\Auth\Events\Registered($user));

        $roleLabel = $validated['role'] === 'station_admin' ? 'Station Admin' : 'Centre staff member';
        return redirect()
            ->route('admin.centre.users.index')
            ->with('success', "{$roleLabel} added successfully. An email verification has been sent to {$user->email}.");
    }

    public function edit(User $user): View
    {
        $this->ensureCentreAdmin();
        $admin = Auth::user();

        // Allow editing centre staff and station admins/staff within the centre
        if ($user->centre_id !== $admin->centre_id) {
            abort(404);
        }

        $stations = \App\Models\Station::where('centre_id', $admin->centre_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.users.centre.edit', [
            'user' => $user,
            'stations' => $stations,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureCentreAdmin();
        $admin = Auth::user();

        // Allow updating centre staff and station admins/staff within the centre
        if ($user->centre_id !== $admin->centre_id) {
            abort(404);
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
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:staff,station_admin',
            'station_id' => 'nullable|exists:stations,id',
        ], [
            'email.regex' => 'Email address must be from the @nimr.or.tz domain.'
        ]);

        // Validate station assignment
        if ($validated['role'] === 'station_admin' && !$validated['station_id']) {
            return back()->withErrors(['station_id' => 'Station Admins must be assigned to a station.']);
        }

        // Ensure station belongs to admin's centre
        if ($validated['station_id']) {
            $station = \App\Models\Station::find($validated['station_id']);
            if (!$station || $station->centre_id !== $admin->centre_id) {
                return back()->withErrors(['station_id' => 'Selected station is not in your centre.']);
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->employee_id = $validated['employee_id'] ?? null;
        $user->role = $validated['role'];

        // Set station_id if provided, otherwise clear it
        if ($validated['station_id']) {
            $user->station_id = $validated['station_id'];
        } else {
            $user->station_id = null;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $roleLabel = $validated['role'] === 'station_admin' ? 'Station Admin' : 'Centre staff member';
        return redirect()
            ->route('admin.centre.users.index')
            ->with('success', "{$roleLabel} updated successfully.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureCentreAdmin();
        $admin = Auth::user();

        // Allow managing centre staff and station admins/staff within the centre
        if ($user->centre_id !== $admin->centre_id) {
            abort(404);
        }

        if ($user->id === Auth::id()) {
            return redirect()
                ->route('admin.centre.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Prevent deletion of users with content
        $hasContent = $user->announcements()->count() > 0;

        if ($hasContent) {
            return redirect()
                ->route('admin.centre.users.index')
                ->with('error', 'Cannot delete user. This user has created content. Please deactivate instead.');
        }

        $user->delete();

        $roleLabel = $user->role === 'station_admin' ? 'Station Admin' : 'Centre staff member';
        return redirect()
            ->route('admin.centre.users.index')
            ->with('success', "{$roleLabel} removed successfully.");
    }
}
