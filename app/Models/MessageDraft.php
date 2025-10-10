<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageDraft extends Model
{
    public $timestamps = false; // manual updated_at management
    protected $fillable = ['conversation_id', 'user_id', 'body', 'updated_at'];
    protected $casts = ['updated_at' => 'datetime'];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
