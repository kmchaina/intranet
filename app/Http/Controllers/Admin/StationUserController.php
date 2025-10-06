<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StationUserController extends Controller
{
    private function ensureStationAdmin(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user instanceof \App\Models\User || !$user->isStationAdmin() || !$user->station_id) {
            abort(403, 'Only Station Administrators can perform this action.');
        }
    }

    public function index(): View
    {
        $this->ensureStationAdmin();
        $admin = Auth::user();

        $stationStaff = User::where('station_id', $admin->station_id)
            ->orderBy('name')
            ->get();

        return view('admin.users.station.index', [
            'stationStaff' => $stationStaff,
        ]);
    }

    public function create(): View
    {
        $this->ensureStationAdmin();

        return view('admin.users.station.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureStationAdmin();
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
        ], [
            'email.regex' => 'Email address must be from the @nimr.or.tz domain.'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'role' => 'staff',
            'centre_id' => $admin->centre_id,
            'station_id' => $admin->station_id,
        ]);

        // Send email verification instead of auto-verifying
        event(new \Illuminate\Auth\Events\Registered($user));

        return redirect()
            ->route('admin.station.users.index')
            ->with('success', "Station staff member added successfully. An email verification has been sent to {$user->email}.");
    }

    public function edit(User $user): View
    {
        $this->ensureStationAdmin();
        $admin = Auth::user();

        if ($user->station_id !== $admin->station_id) {
            abort(404);
        }

        return view('admin.users.station.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureStationAdmin();
        $admin = Auth::user();

        if ($user->station_id !== $admin->station_id) {
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
        ], [
            'email.regex' => 'Email address must be from the @nimr.or.tz domain.'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->employee_id = $validated['employee_id'] ?? null;
        $user->centre_id = $admin->centre_id; // keep alignment
        $user->station_id = $admin->station_id;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.station.users.index')
            ->with('success', 'Station staff member updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureStationAdmin();
        $admin = Auth::user();

        if ($user->station_id !== $admin->station_id) {
            abort(404);
        }

        if ($user->id === Auth::id()) {
            return redirect()
                ->route('admin.station.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.station.users.index')
            ->with('success', 'Station staff member removed successfully.');
    }
}
