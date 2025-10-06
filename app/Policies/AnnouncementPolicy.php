<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    /**
     * Determine whether the user can view any announcements.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view announcements
    }

    /**
     * Determine whether the user can view the announcement.
     */
    public function view(User $user, Announcement $announcement): bool
    {
        // Check if the announcement is visible to this user
        return Announcement::published()->forUser($user)->where('id', $announcement->id)->exists();
    }

    /**
     * Determine whether the user can create announcements.
     */
    public function create(User $user): bool
    {
        // Only admin-level users can create announcements
        return in_array($user->role, ['super_admin', 'hq_admin', 'centre_admin', 'station_admin']);
    }

    /**
     * Determine whether a user role can target a given scope.
     */
    public function canTargetScope(User $user, string $scope): bool
    {
        return match ($user->role) {
            'super_admin' => true,
            'hq_admin' => in_array($scope, ['all', 'headquarters', 'my_centre', 'my_centre_stations', 'my_station', 'all_centres', 'all_stations', 'specific']),
            'centre_admin' => in_array($scope, ['my_centre', 'my_centre_stations', 'specific']),
            'station_admin' => in_array($scope, ['my_station', 'specific']),
            default => false,
        };
    }

    /**
     * Fine-grained validation for specific targeting arrays
     */
    public function validateSpecificTargets(User $user, array $centreIds, array $stationIds): bool
    {
        // Super & HQ admins can target any provided ids
        if (in_array($user->role, ['super_admin', 'hq_admin'])) {
            return true;
        }
        // Centre admin limited to their centre + its stations
        if ($user->role === 'centre_admin') {
            if (empty($centreIds) && empty($stationIds)) return false;
            // centre ids must be exactly their centre if any present
            if (!empty($centreIds) && (count($centreIds) !== 1 || (int)$centreIds[0] !== (int)$user->centre_id)) return false;
            // station ids (if present) must belong to their centre -> checked later in controller filtering
            return true;
        }
        // Station admin: only its own station (no centres array)
        if ($user->role === 'station_admin') {
            if (!empty($centreIds)) return false;
            if (empty($stationIds)) return false; // must specify itself
            return count($stationIds) === 1 && (int)$stationIds[0] === (int)$user->station_id;
        }
        return false;
    }

    /**
     * Determine whether the user can update the announcement.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        // Super admin can update any announcement
        if ($user->role === 'super_admin') {
            return true;
        }

        // Users can update announcements they created
        if ($announcement->created_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the announcement.
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        // Super admin can delete any announcement
        if ($user->role === 'super_admin') {
            return true;
        }

        // Users can delete announcements they created
        if ($announcement->created_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the announcement.
     */
    public function restore(User $user, Announcement $announcement): bool
    {
        return $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can permanently delete the announcement.
     */
    public function forceDelete(User $user, Announcement $announcement): bool
    {
        return $user->role === 'super_admin';
    }
}
