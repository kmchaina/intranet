<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class HqUserController extends Controller
{
    private function ensureHqAdmin(): void
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || !$user->isHqAdmin()) {
            abort(403, 'Only HQ Administrators can perform this action.');
        }
    }

    public function index(): View
    {
        $this->ensureHqAdmin();

        $users = User::whereNull('centre_id')
            ->whereNull('station_id')
            ->where('role', 'staff')
            ->orderBy('name')
            ->get();

        return view('admin.users.hq.index', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        $this->ensureHqAdmin();

        return view('admin.users.hq.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureHqAdmin();

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

        $admin = Auth::user();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'role' => 'staff',
            'headquarters_id' => $admin->headquarters_id,
        ]);

        // Send email verification instead of auto-verifying
        event(new \Illuminate\Auth\Events\Registered($user));

        return redirect()
            ->route('admin.hq.users.index')
            ->with('success', "Staff member added successfully. An email verification has been sent to {$user->email}.");
    }

    public function edit(User $user): View
    {
        $this->ensureHqAdmin();

        if ($user->role !== 'staff' || $user->centre_id || $user->station_id) {
            abort(404);
        }

        return view('admin.users.hq.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureHqAdmin();

        if ($user->role !== 'staff' || $user->centre_id || $user->station_id) {
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

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.hq.users.index')
            ->with('success', 'Staff details updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureHqAdmin();

        if ($user->role !== 'staff' || $user->centre_id || $user->station_id) {
            abort(404);
        }

        if ($user->id === Auth::id()) {
            return redirect()
                ->route('admin.hq.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.hq.users.index')
            ->with('success', 'Staff member deleted successfully.');
    }
}
