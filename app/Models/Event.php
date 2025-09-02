<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'all_day',
        'location',
        'venue',
        'venue_details',
        'category',
        'priority',
        'status',
        'is_recurring',
        'recurrence_type',
        'recurrence_interval',
        'recurrence_end_date',
        'recurrence_days',
        'requires_rsvp',
        'max_attendees',
        'rsvp_deadline',
        'visibility_scope',
        'target_centres',
        'target_stations',
        'created_by',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'all_day' => 'boolean',
        'is_recurring' => 'boolean',
        'requires_rsvp' => 'boolean',
        'recurrence_end_date' => 'date',
        'rsvp_deadline' => 'datetime',
        'target_centres' => 'array',
        'target_stations' => 'array',
        'recurrence_days' => 'array',
    ];

    /**
     * Get the user who created this event
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all RSVPs for this event
     */
    public function rsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    /**
     * Get confirmed attendees
     */
    public function attendees(): HasMany
    {
        return $this->hasMany(EventRsvp::class)->where('status', 'attending');
    }

    /**
     * Scope events visible to a specific user
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            // All NIMR events
            $q->where('visibility_scope', 'all')

                // Headquarters level events
                ->orWhere(function ($subQ) use ($user) {
                    if ($user->headquarters_id && !$user->centre_id && !$user->station_id) {
                        $subQ->where('visibility_scope', 'headquarters');
                    }
                })

                // Centre level events
                ->orWhere(function ($subQ) use ($user) {
                    if ($user->centre_id) {
                        $subQ->where('visibility_scope', 'centres')
                            ->orWhere(function ($centreQ) use ($user) {
                                $centreQ->where('visibility_scope', 'specific')
                                    ->whereJsonContains('target_centres', $user->centre_id);
                            })
                            ->orWhere(function ($mycentreQ) use ($user) {
                                $mycentreQ->where('visibility_scope', 'my_centre')
                                    ->whereHas('creator', function ($creatorQ) use ($user) {
                                        $creatorQ->where('centre_id', $user->centre_id);
                                    });
                            });
                    }
                })

                // Station level events
                ->orWhere(function ($subQ) use ($user) {
                    if ($user->station_id) {
                        $subQ->where('visibility_scope', 'stations')
                            ->orWhere(function ($stationQ) use ($user) {
                                $stationQ->where('visibility_scope', 'specific')
                                    ->whereJsonContains('target_stations', $user->station_id);
                            })
                            ->orWhere(function ($mystationQ) use ($user) {
                                $mystationQ->where('visibility_scope', 'my_station')
                                    ->whereHas('creator', function ($creatorQ) use ($user) {
                                        $creatorQ->where('station_id', $user->station_id);
                                    });
                            });
                    }
                });
        });
    }

    /**
     * Scope for published events only
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_datetime', '>', now());
    }

    /**
     * Scope for past events
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('end_datetime', '<', now());
    }

    /**
     * Scope for events happening today
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('start_datetime', today());
    }

    /**
     * Scope for events in a date range
     */
    public function scopeInDateRange(Builder $query, $startDate, $endDate): Builder
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_datetime', [$startDate, $endDate])
                ->orWhereBetween('end_datetime', [$startDate, $endDate])
                ->orWhere(function ($overlap) use ($startDate, $endDate) {
                    $overlap->where('start_datetime', '<=', $startDate)
                        ->where('end_datetime', '>=', $endDate);
                });
        });
    }

    /**
     * Get RSVP status for a specific user
     */
    public function getRsvpStatusForUser(User $user): ?string
    {
        $rsvp = $this->rsvps()->where('user_id', $user->id)->first();
        return $rsvp?->status;
    }

    /**
     * Check if user has RSVP'd to this event
     */
    public function hasUserRsvped(User $user): bool
    {
        return $this->rsvps()->where('user_id', $user->id)->exists();
    }

    /**
     * Get attendees count
     */
    public function getAttendeesCountAttribute(): int
    {
        return $this->attendees()->count();
    }

    /**
     * Check if event is full
     */
    public function getIsFullAttribute(): bool
    {
        if (!$this->max_attendees) {
            return false;
        }

        return $this->attendees_count >= $this->max_attendees;
    }

    /**
     * Check if RSVP is still open
     */
    public function getCanRsvpAttribute(): bool
    {
        if (!$this->requires_rsvp) {
            return false;
        }

        if ($this->is_full) {
            return false;
        }

        if ($this->rsvp_deadline && now()->isAfter($this->rsvp_deadline)) {
            return false;
        }

        return true;
    }

    /**
     * Get formatted duration
     */
    public function getDurationAttribute(): string
    {
        $start = $this->start_datetime;
        $end = $this->end_datetime;

        $duration = $start->diffInMinutes($end);

        if ($duration < 60) {
            return $duration . ' minutes';
        }

        $hours = floor($duration / 60);
        $minutes = $duration % 60;

        if ($minutes > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $hours . ' hour' . ($hours > 1 ? 's' : '');
    }

    /**
     * Get category badge color
     */
    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'meeting' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'training' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'conference' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            'workshop' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'seminar' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'fieldwork' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300',
            'social' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
        };
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'low' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        };
    }
}
