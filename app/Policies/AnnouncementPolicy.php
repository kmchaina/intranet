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
        // All authenticated users can view announcements
        return true;
    }

    /**
     * Determine whether the user can view the announcement.
     */
    public function view(User $user, Announcement $announcement): bool
    {
        // Users can view announcements targeted to them
        return $announcement->newQuery()->published()->forUser($user)->where('id', $announcement->id)->exists();
    }

    /**
     * Determine whether the user can create announcements.
     */
    public function create(User $user): bool
    {
        // Temporarily simplified for debugging
        return in_array($user->role, ['super_admin', 'hq_admin', 'centre_admin', 'station_admin']);
    }

    /**
     * Determine whether the user can update the announcement.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        // Super admin can edit any announcement
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Users can only edit their own announcements
        if ($announcement->created_by !== $user->id) {
            return false;
        }

        // Must still have announcement creation permissions
        return $user->canCreateAnnouncements();
    }

    /**
     * Determine whether the user can delete the announcement.
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        // Super admin can delete any announcement
        if ($user->isSuperAdmin()) {
            return true;
        }

        // HQ admin can delete any announcement
        if ($user->isHqAdmin()) {
            return true;
        }

        // Users can only delete their own announcements
        if ($announcement->created_by !== $user->id) {
            return false;
        }

        // Must still have announcement creation permissions
        return $user->canCreateAnnouncements();
    }

    /**
     * Determine if user can target specific scope
     */
    public function canTargetScope(User $user, string $targetScope): bool
    {
        $allowedScopes = array_keys($user->getAllowedTargetScopes());
        return in_array($targetScope, $allowedScopes);
    }

    /**
     * Determine if user can target specific centres
     */
    public function canTargetCentres(User $user, array $centreIds): bool
    {
        // Super admin can target any centres
        if ($user->isSuperAdmin()) {
            return true;
        }

        // HQ admin can target any centres
        if ($user->isHqAdmin()) {
            return true;
        }

        // Centre admin can only target their own centre
        if ($user->isCentreAdmin() && $user->centre_id) {
            return count($centreIds) === 1 && $centreIds[0] == $user->centre_id;
        }

        return false;
    }

    /**
     * Determine if user can target specific stations
     */
    public function canTargetStations(User $user, array $stationIds): bool
    {
        // Super admin can target any stations
        if ($user->isSuperAdmin()) {
            return true;
        }

        // HQ admin can target any stations
        if ($user->isHqAdmin()) {
            return true;
        }

        // Centre admin can only target stations under their centre
        if ($user->isCentreAdmin() && $user->centre_id) {
            // Get stations under user's centre
            $userCentreStations = \App\Models\Station::where('centre_id', $user->centre_id)->pluck('id')->toArray();
            return empty(array_diff($stationIds, $userCentreStations));
        }

        // Station admin can only target their own station
        if ($user->isStationAdmin() && $user->station_id) {
            return count($stationIds) === 1 && $stationIds[0] == $user->station_id;
        }

        return false;
    }
}
