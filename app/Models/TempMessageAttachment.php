<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempMessageAttachment extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'temp_message_attachments';
    public $timestamps = false; // we manage created_at manually (no updated_at)

    protected $fillable = [
        'id',
        'user_id',
        'original_name',
        'path',
        'mime',
        'size',
        'ext',
        'kind',
        'created_at',
        'consumed_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'consumed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
