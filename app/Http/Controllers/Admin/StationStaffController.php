<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StationStaffController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser instanceof User) {
            abort(401);
        }

        if (!$currentUser->isStationAdmin() && !$currentUser->isCentreAdmin() && !$currentUser->isHqAdmin() && !$currentUser->isSuperAdmin()) {
            abort(403);
        }

        $requestedStationId = $currentUser->isStationAdmin()
            ? $currentUser->station_id
            : $request->integer('station_id');

        $station = $this->resolveStation($requestedStationId, $currentUser);

        if (!$station) {
            abort(404, 'No station records available.');
        }

        if ($currentUser->isStationAdmin() && $station->id !== $currentUser->station_id) {
            abort(403);
        }

        $station->loadMissing([
            'centre:id,name',
            'users' => function ($query) {
                $query->with('centre:id,name');
            },
        ]);

        $staff = $station->users->map(function ($member) use ($currentUser) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'role' => $member->role,
                'role_label' => ucwords(str_replace('_', ' ', (string) $member->role)),
                'centre' => $member->centre ? [
                    'id' => $member->centre->id,
                    'name' => $member->centre->name,
                ] : null,
                'status' => $member->email_verified_at ? 'active' : 'inactive',
                'manage_url' => $this->manageUrlFor($currentUser, $member),
            ];
        })->values();

        $stationStats = [
            'total' => $station->users->count(),
            'active' => $station->users->whereNotNull('email_verified_at')->count(),
        ];

        $availableStations = $currentUser->isStationAdmin()
            ? collect()
            : Station::when($currentUser->isCentreAdmin(), function ($query) use ($currentUser) {
                $query->where('centre_id', $currentUser->centre_id);
            })->orderBy('name')->get(['id', 'name', 'centre_id']);

        return view('admin.station.staff.index', [
            'station' => $station,
            'stationStats' => $stationStats,
            'staff' => $staff,
            'availableStations' => $availableStations,
            'selectedStationId' => $station->id,
        ]);
    }

    protected function resolveStation(?int $stationId, $currentUser): ?Station
    {
        $query = Station::with(['users', 'centre:id,name']);

        if ($currentUser->isStationAdmin()) {
            $query->where('id', $currentUser->station_id);
        } elseif ($stationId) {
            $query->where('id', $stationId);
        } elseif ($currentUser->isCentreAdmin()) {
            $query->where('centre_id', $currentUser->centre_id);
        }

        $station = $query->first();

        if (!$station && !$currentUser->isStationAdmin()) {
            $fallbackQuery = Station::with(['users', 'centre:id,name']);

            if ($currentUser->isCentreAdmin()) {
                $fallbackQuery->where('centre_id', $currentUser->centre_id);
            }

            $station = $fallbackQuery->orderBy('name')->first();
        }

        return $station;
    }

    protected function manageUrlFor($currentUser, $member): ?string
    {
        if ($currentUser->isStationAdmin() && $member->station_id === $currentUser->station_id) {
            if ($member->role === 'staff') {
                return route('admin.station.users.edit', $member);
            }
        }

        if ($currentUser->isSuperAdmin()) {
            return route('admin.users.edit', $member);
        }

        return null;
    }
}
