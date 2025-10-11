<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'featured_image',
        'status',
        'priority',
        'tags',
        'author_id',
        'location',
        'location_type',
        'published_at',
        'views_count',
        'likes_count',
        'is_featured',
        'allow_comments',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
    ];

    protected $dates = [
        'published_at',
    ];

    /**
     * Get the author of the news.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the comments for the news.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(NewsComment::class)->whereNull('parent_id')->with('replies');
    }

    /**
     * Get all comments (including replies) for the news.
     */
    public function allComments(): HasMany
    {
        return $this->hasMany(NewsComment::class);
    }

    /**
     * Get the likes for the news.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(NewsLike::class);
    }

    /**
     * Check if the news is liked by the given user.
     */
    public function isLikedBy($user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope a query to only include published news.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include draft news.
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include featured news.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to order by date first, then priority.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('published_at', 'desc')
            ->orderByRaw("
                CASE priority 
                    WHEN 'high' THEN 1 
                    WHEN 'normal' THEN 2 
                    WHEN 'low' THEN 3 
                END
            ");
    }

    /**
     * Get the featured image URL.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        try {
            // Safety check to ensure we have a valid model instance
            if (!isset($this->attributes) || !is_array($this->attributes)) {
                Log::warning('getFeaturedImageUrlAttribute called on invalid News model instance', [
                    'type' => gettype($this),
                    'value' => is_scalar($this) ? $this : 'non-scalar'
                ]);
                return null;
            }

            if (!$this->featured_image) {
                return null;
            }

            if (filter_var($this->featured_image, FILTER_VALIDATE_URL)) {
                return $this->featured_image;
            }

            return Storage::url($this->featured_image);
        } catch (\Exception $e) {
            Log::error('Error in getFeaturedImageUrlAttribute: ' . $e->getMessage(), [
                'model_type' => gettype($this),
                'model_class' => get_class($this),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Get the excerpt or truncated content.
     */
    public function getExcerptAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 150);
    }

    /**
     * Get the reading time estimate.
     */
    public function getReadingTimeAttribute(): string
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed

        return $minutes . ' min read';
    }

    /**
     * Increment the views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Get the location display name.
     */
    public function getLocationDisplayAttribute(): string
    {
        if (!$this->location) {
            return 'NIMR Headquarters';
        }

        $type = $this->location_type ? ucfirst($this->location_type) : '';
        return "{$this->location} {$type}";
    }

    /**
     * Check if the news is recent (within last 24 hours).
     */
    public function getIsRecentAttribute(): bool
    {
        return $this->published_at && $this->published_at->gt(now()->subDay());
    }

    /**
     * Get the priority badge color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'bg-red-100 text-red-800',
            'normal' => 'bg-blue-100 text-blue-800',
            'low' => 'bg-gray-100 text-gray-800',
            default => 'bg-blue-100 text-blue-800'
        };
    }
}
