<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageAttachment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'message_id',
        'user_id',
        'original_name',
        'path',
        'mime',
        'size',
        'ext',
        'kind',
        'created_at',
        'linked_at'
    ];
    protected $casts = [
        'linked_at' => 'datetime',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    public $timestamps = false; // Disable Laravel's automatic timestamp management

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
