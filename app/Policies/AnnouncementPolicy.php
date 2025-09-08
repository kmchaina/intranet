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