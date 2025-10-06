<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'subject',
        'message',
        'status',
        'is_anonymous',
        'is_public',
        'submitted_by',
        'upvotes_count',
        'admin_response',
        'admin_notes',
        'attachments',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_public' => 'boolean',
        'upvotes_count' => 'integer',
        'attachments' => 'array',
    ];

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'submitted_by');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'new' => 'bg-blue-100 text-blue-800 border border-blue-200',
            'reviewed' => 'bg-purple-100 text-purple-800 border border-purple-200',
            'implemented' => 'bg-green-100 text-green-800 border border-green-200',
            'closed' => 'bg-gray-100 text-gray-800 border border-gray-200',
            default => 'bg-gray-100 text-gray-800 border border-gray-200',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'suggestion' => '💡',
            'feature_request' => '🚀',
            'compliment' => '👏',
            'general' => '📝',
            default => '💡',
        };
    }

    public static function getTypes(): array
    {
        return [
            'suggestion' => 'Suggestion',
            'feature_request' => 'Feature Request',
            'compliment' => 'Compliment',
            'general' => 'General Feedback',
        ];
    }

    public static function getCategories(): array
    {
        return [
            'general' => 'General',
            'it_system' => 'IT & Systems',
            'hr' => 'HR & People',
            'facilities' => 'Facilities',
            'process' => 'Work Process',
            'other' => 'Other',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'new' => 'New',
            'reviewed' => 'Reviewed',
            'implemented' => 'Implemented',
            'closed' => 'Closed',
        ];
    }

    /**
     * Scope for public suggestions
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for popular suggestions (most upvoted)
     */
    public function scopePopular($query)
    {
        return $query->where('upvotes_count', '>', 0)->orderBy('upvotes_count', 'desc');
    }
}
