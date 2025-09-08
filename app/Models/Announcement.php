<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'priority',
        'status',
        'created_by',
        'target_scope',
        'target_centres',
        'target_stations',
        'published_at',
        'expires_at',
        'is_published',
        'email_notification',
        'views_count'
    ];

    protected $casts = [
        'target_centres' => 'array',
        'target_stations' => 'array',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_published' => 'boolean',
        'email_notification' => 'boolean',
    ];

    /**
     * Get the user who created this announcement
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Users who have read this announcement
     */
    public function readers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'announcement_reads')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    /**
     * Alias for readers relationship (for cleaner queries)
     */
    public function readBy(): BelongsToMany
    {
        return $this->readers();
    }

    /**
     * Attachments for this announcement
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(AnnouncementAttachment::class);
    }

    /**
     * Scope to get published announcements
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope to get draft announcements
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to get archived announcements
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope to get announcements for a specific user based on their organizational hierarchy
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            // 1. All NIMR staff announcements
            $q->where('target_scope', 'all')
            
            // 2. Headquarters-only announcements (only if user is at HQ)
            ->orWhere(function ($subQ) use ($user) {
                if ($user->headquarters_id && !$user->centre_id && !$user->station_id) {
                    $subQ->where('target_scope', 'headquarters');
                }
            })
            
            // 3. Centre-level announcements
            ->orWhere(function ($subQ) use ($user) {
                if ($user->centre_id) {
                    $subQ->where(function ($centreQ) use ($user) {
                        // My centre announcements from same centre users
                        $centreQ->where('target_scope', 'my_centre')
                                ->whereHas('creator', function ($creatorQ) use ($user) {
                                    $creatorQ->where('centre_id', $user->centre_id);
                                });
                    })
                    ->orWhere(function ($centreStationsQ) use ($user) {
                        // My centre + stations announcements from same centre users
                        $centreStationsQ->where('target_scope', 'my_centre_stations')
                                       ->whereHas('creator', function ($creatorQ) use ($user) {
                                           $creatorQ->where('centre_id', $user->centre_id);
                                       });
                    })
                    ->orWhere(function ($allCentresQ) {
                        // All centres announcements (visible to all centre staff)
                        $allCentresQ->where('target_scope', 'all_centres');
                    })
                    ->orWhere(function ($specificQ) use ($user) {
                        // Specific targeting that includes this user's centre
                        $specificQ->where('target_scope', 'specific')
                                 ->whereJsonContains('target_centres', $user->centre_id);
                    });
                }
            })
            
            // 4. Station-level announcements
            ->orWhere(function ($subQ) use ($user) {
                if ($user->station_id) {
                    $subQ->where(function ($stationQ) use ($user) {
                        // My station announcements from same station users
                        $stationQ->where('target_scope', 'my_station')
                                ->whereHas('creator', function ($creatorQ) use ($user) {
                                    $creatorQ->where('station_id', $user->station_id);
                                });
                    })
                    ->orWhere(function ($allStationsQ) {
                        // All stations announcements (visible to all station staff)
                        $allStationsQ->where('target_scope', 'all_stations');
                    })
                    ->orWhere(function ($specificStationQ) use ($user) {
                        // Specific targeting that includes this user's station
                        $specificStationQ->where('target_scope', 'specific')
                                        ->whereJsonContains('target_stations', $user->station_id);
                    })
                    ->orWhere(function ($centreStationsQ) use ($user) {
                        // Centre + stations announcements from same centre (station users can see centre announcements)
                        $centreStationsQ->where('target_scope', 'my_centre_stations')
                                       ->whereHas('creator', function ($creatorQ) use ($user) {
                                           $creatorQ->where('centre_id', $user->centre_id);
                                       });
                    });
                }
            });
        });
    }

    /**
     * Scope to get announcements visible to a specific user
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        return $query->published()->forUser($user);
    }

    /**
     * Check if user has read this announcement
     */
    public function isReadBy(User $user): bool
    {
        return $this->readers()->where('user_id', $user->id)->exists();
    }

    /**
     * Mark announcement as read by user
     */
    public function markAsReadBy(User $user): void
    {
        if (!$this->isReadBy($user)) {
            $this->readers()->attach($user->id, ['read_at' => now()]);
            $this->increment('views_count');
        }
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-blue-100 text-blue-800',
            'low' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get category badge color
     */
    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'urgent' => 'bg-red-100 text-red-800',
            'info' => 'bg-blue-100 text-blue-800',
            'event' => 'bg-green-100 text-green-800',
            'general' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get readable target scope description
     */
    public function getTargetScopeDescriptionAttribute(): string
    {
        return match ($this->target_scope) {
            'all' => 'All NIMR Staff',
            'headquarters' => 'Headquarters Staff',
            'centres' => 'All Centre Staff',
            'stations' => 'All Station Staff',
            'specific' => 'Specific Groups',
            default => 'Unknown'
        };
    }
}
