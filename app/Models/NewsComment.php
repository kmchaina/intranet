<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'user_id',
        'content',
        'parent_id',
    ];

    /**
     * Get the news that owns the comment.
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(NewsComment::class, 'parent_id');
    }

    /**
     * Get the replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(NewsComment::class, 'parent_id')->with('user');
    }

    /**
     * Check if this comment is a reply.
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }
}
