<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CentreStaffController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser instanceof User) {
            abort(401);
        }

        if (!$currentUser->isCentreAdmin() && !$currentUser->isHqAdmin() && !$currentUser->isSuperAdmin()) {
            abort(403);
        }

        $requestedCentreId = $currentUser->isCentreAdmin()
            ? $currentUser->centre_id
            : $request->integer('centre_id');

        $centre = $this->resolveCentre($requestedCentreId, $currentUser->isCentreAdmin() ? $currentUser->centre_id : null);

        if (!$centre) {
            abort(404, 'No centre records available.');
        }

        if ($currentUser->isCentreAdmin() && $centre->id !== $currentUser->centre_id) {
            abort(403);
        }

        $centre->loadMissing([
            'users.station:id,name,centre_id',
            'stations.users' => function ($query) {
                $query->select('id', 'name', 'role', 'centre_id', 'station_id', 'email', 'email_verified_at');
            },
        ]);

        // Only show centre-level staff (not assigned to stations)
        $centreUsers = $centre->users->filter(function ($user) {
            return $user->station_id === null; // Only centre-level staff, no station assignments
        });

        $staff = $centreUsers->map(function ($member) use ($currentUser) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'role' => $member->role,
                'role_label' => ucwords(str_replace('_', ' ', (string) $member->role)),
                'status' => $member->email_verified_at ? 'active' : 'inactive',
                'profile_url' => $this->profileUrlFor($currentUser, $member),
            ];
        })->values();

        $stations = $centre->stations->map(function ($station) {
            return [
                'id' => $station->id,
                'name' => $station->name,
            ];
        })->values();

        $centreStats = [
            'total' => $centreUsers->count(),
            'active' => $centreUsers->whereNotNull('email_verified_at')->count(),
        ];

        $stationsSummary = $centre->stations->map(function ($station) {
            $users = $station->users ?? collect();

            return [
                'id' => $station->id,
                'name' => $station->name,
                'total' => $users->count(),
                'admin_count' => $users->where('role', 'station_admin')->count(),
                'manage_url' => route('admin.station.staff.index', ['station_id' => $station->id]),
            ];
        })->values();

        $availableCentres = $currentUser->isCentreAdmin()
            ? collect()
            : Centre::orderBy('name')->get(['id', 'name']);

        return view('admin.centre.staff.index', [
            'centre' => $centre,
            'centreStats' => $centreStats,
            'stationsSummary' => $stationsSummary,
            'staff' => $staff,
            'stations' => $stations,
            'availableCentres' => $availableCentres,
            'selectedCentreId' => $centre->id,
        ]);
    }

    protected function resolveCentre(?int $centreId, ?int $restrictedCentreId)
    {
        $query = Centre::with([
            'users' => function ($query) {
                $query->select('id', 'name', 'email', 'role', 'centre_id', 'station_id', 'email_verified_at');
            },
            'stations' => function ($query) {
                $query->orderBy('name');
            },
        ]);

        if ($restrictedCentreId) {
            $query->where('id', $restrictedCentreId);
        } elseif ($centreId) {
            $query->where('id', $centreId);
        }

        $centre = $query->first();

        if (!$centre && !$restrictedCentreId) {
            $centre = Centre::with([
                'users' => function ($query) {
                    $query->select('id', 'name', 'email', 'role', 'centre_id', 'station_id', 'email_verified_at');
                },
                'stations' => function ($query) {
                    $query->orderBy('name');
                },
            ])->orderBy('name')->first();
        }

        return $centre;
    }

    protected function profileUrlFor($currentUser, $member): ?string
    {
        if ($currentUser->isSuperAdmin()) {
            return route('admin.users.edit', $member);
        }

        if (
            $currentUser->isCentreAdmin()
            && $member->role === 'staff'
            && $member->centre_id === $currentUser->centre_id
            && $member->station_id === null
        ) {
            return route('admin.centre.users.edit', $member);
        }

        return null;
    }
}
