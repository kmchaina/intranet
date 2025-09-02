<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class EventRsvp extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'attended',
        'response_notes',
        'dietary_requirements',
        'emergency_contact',
        'guest_count',
    ];

    protected $casts = [
        'attended' => 'boolean',
        'guest_count' => 'integer',
    ];

    /**
     * Get the event this RSVP belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who made this RSVP
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for attending RSVPs
     */
    public function scopeAttending(Builder $query): Builder
    {
        return $query->where('status', 'attending');
    }

    /**
     * Scope for declined RSVPs
     */
    public function scopeDeclined(Builder $query): Builder
    {
        return $query->where('status', 'declined');
    }

    /**
     * Scope for maybe RSVPs
     */
    public function scopeMaybe(Builder $query): Builder
    {
        return $query->where('status', 'maybe');
    }

    /**
     * Scope for RSVPs that actually attended
     */
    public function scopeActuallyAttended(Builder $query): Builder
    {
        return $query->where('attended', true);
    }

    /**
     * Get the status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'attending' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'declined' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'maybe' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    /**
     * Get total attendee count including guests
     */
    public function getTotalAttendeesAttribute(): int
    {
        return 1 + ($this->guest_count ?? 0);
    }
}
