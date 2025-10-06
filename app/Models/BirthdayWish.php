<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BirthdayWish extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_id',
        'sender_id',
        'celebration_type',
        'message',
        'is_public',
        'parent_wish_id',
        'reactions',
        'reply_count',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'reactions' => 'array',
        'reply_count' => 'integer',
    ];

    /**
     * Get the recipient user
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get the sender user
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the parent wish (for replies)
     */
    public function parentWish(): BelongsTo
    {
        return $this->belongsTo(BirthdayWish::class, 'parent_wish_id');
    }

    /**
     * Get replies to this wish
     */
    public function replies()
    {
        return $this->hasMany(BirthdayWish::class, 'parent_wish_id')->orderBy('created_at');
    }

    /**
     * Check if this is a reply
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_wish_id);
    }

    /**
     * Add a reaction to this wish
     */
    public function addReaction(string $emoji, int $userId): void
    {
        $reactions = $this->reactions ?? [];

        if (!isset($reactions[$emoji])) {
            $reactions[$emoji] = [];
        }

        if (!in_array($userId, $reactions[$emoji])) {
            $reactions[$emoji][] = $userId;
        }

        $this->reactions = $reactions;
        $this->save();
    }

    /**
     * Remove a reaction from this wish
     */
    public function removeReaction(string $emoji, int $userId): void
    {
        $reactions = $this->reactions ?? [];

        if (isset($reactions[$emoji])) {
            $reactions[$emoji] = array_values(array_filter($reactions[$emoji], fn($id) => $id !== $userId));

            if (empty($reactions[$emoji])) {
                unset($reactions[$emoji]);
            }
        }

        $this->reactions = $reactions;
        $this->save();
    }

    /**
     * Get reaction count for a specific emoji
     */
    public function getReactionCount(string $emoji): int
    {
        return count($this->reactions[$emoji] ?? []);
    }

    /**
     * Check if user has reacted with specific emoji
     */
    public function hasUserReacted(string $emoji, int $userId): bool
    {
        return in_array($userId, $this->reactions[$emoji] ?? []);
    }

    /**
     * Get celebration icon based on type
     */
    public function getIconAttribute(): string
    {
        return match ($this->celebration_type) {
            'birthday' => '🎂',
            'work_anniversary' => '🏆',
            default => '🎉',
        };
    }
}
