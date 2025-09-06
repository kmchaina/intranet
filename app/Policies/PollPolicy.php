<?php

namespace App\Policies;

use App\Models\Poll;
use App\Models\User;

class PollPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Anyone can view polls (filtered by visibility in model)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Poll $poll): bool
    {
        return $poll->isVisibleTo($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return $user !== null; // Must be logged in to create polls
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Poll $poll): bool
    {
        return $poll->canManage($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Poll $poll): bool
    {
        return $poll->canManage($user);
    }

    /**
     * Determine whether the user can vote on the poll.
     */
    public function vote(?User $user, Poll $poll): bool
    {
        return $poll->canVote($user);
    }

    /**
     * Determine whether the user can view poll results.
     */
    public function viewResults(?User $user, Poll $poll): bool
    {
        if (!$poll->isVisibleTo($user)) {
            return false;
        }

        // Can always view if user can manage the poll
        if ($poll->canManage($user)) {
            return true;
        }

        // Can view if results are shown and user has voted or poll is not active
        return $poll->show_results && ($poll->hasUserVoted($user) || !$poll->isActive());
    }
}
