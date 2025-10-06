<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class BaseAdminController extends Controller
{
    /**
     * Get the authenticated user with proper type hinting
     */
    protected function getAuthUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(401);
        }

        return $user;
    }

    /**
     * Ensure the current user is a Super Admin
     */
    protected function ensureSuperAdmin(): void
    {
        if (!$this->getAuthUser()->isSuperAdmin()) {
            abort(403, 'This action requires Super Admin privileges.');
        }
    }

    /**
     * Ensure the current user is at least an HQ Admin
     */
    protected function ensureHqAdmin(): void
    {
        $user = $this->getAuthUser();
        if (!$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'This action requires HQ Admin or Super Admin privileges.');
        }
    }

    /**
     * Ensure the current user is at least a Centre Admin
     */
    protected function ensureCentreAdmin(): void
    {
        $user = $this->getAuthUser();
        if (!$user->isCentreAdmin() && !$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'This action requires Centre Admin or higher privileges.');
        }
    }

    /**
     * Ensure the current user is at least a Station Admin
     */
    protected function ensureStationAdmin(): void
    {
        $user = $this->getAuthUser();
        if (!$user->isStationAdmin() && !$user->isCentreAdmin() && !$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'This action requires Station Admin or higher privileges.');
        }
    }

    /**
     * Ensure the current user is any admin level
     */
    protected function ensureAdmin(): void
    {
        if (!$this->getAuthUser()->isAdmin()) {
            abort(403, 'This action requires admin privileges.');
        }
    }

    /**
     * Get common admin stats based on user role
     */
    protected function getAdminStats(array $additionalStats = []): array
    {
        $user = $this->getAuthUser();
        $stats = [];

        if ($user->isSuperAdmin() || $user->isHqAdmin()) {
            $stats = [
                'totalUsers' => \App\Models\User::count(),
                'totalCentres' => \App\Models\Centre::count(),
                'totalStations' => \App\Models\Station::count(),
            ];
        } elseif ($user->isCentreAdmin()) {
            $stats = [
                'centreUsers' => \App\Models\User::where('centre_id', $user->centre_id)->count(),
                'centreStations' => \App\Models\Station::where('centre_id', $user->centre_id)->count(),
            ];
        } elseif ($user->isStationAdmin()) {
            $stats = [
                'stationUsers' => \App\Models\User::where('station_id', $user->station_id)->count(),
            ];
        }

        return array_merge($stats, $additionalStats);
    }

    /**
     * Format success response
     */
    protected function successResponse(string $message): array
    {
        return [
            'success' => true,
            'message' => $message,
        ];
    }

    /**
     * Format error response
     */
    protected function errorResponse(string $message, int $code = 400): array
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => $code,
        ];
    }
}
