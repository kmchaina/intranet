<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
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

    public function index(Request $request): View
    {
        $this->ensureHqAdmin();

        $query = User::whereNull('centre_id')
            ->whereNull('station_id')
            ->where('role', 'staff');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            if ($status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->orderBy('name')->paginate(20)->withQueryString();

        // Calculate stats
        $baseQuery = User::whereNull('centre_id')->whereNull('station_id')->where('role', 'staff');
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->whereNotNull('email_verified_at')->count(),
            'new_this_month' => (clone $baseQuery)->where('created_at', '>=', now()->startOfMonth())->count(),
            'unverified' => (clone $baseQuery)->whereNull('email_verified_at')->count(),
        ];

        return view('admin.users.hq.index', compact('users', 'stats'));
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
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id',
            'bio' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'hire_date' => 'nullable|date',
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
            'bio' => $validated['bio'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'hire_date' => $validated['hire_date'] ?? null,
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
            'bio' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'hire_date' => 'nullable|date',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ], [
            'email.regex' => 'Email address must be from the @nimr.or.tz domain.'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->employee_id = $validated['employee_id'] ?? null;
        $user->bio = $validated['bio'] ?? null;
        $user->birth_date = $validated['birth_date'] ?? null;
        $user->hire_date = $validated['hire_date'] ?? null;

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
