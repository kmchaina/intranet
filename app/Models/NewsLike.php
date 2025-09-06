<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'user_id',
    ];

    /**
     * Get the news that owns the like.
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    /**
     * Get the user that owns the like.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
