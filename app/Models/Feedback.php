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
        'priority',
        'status',
        'is_anonymous',
        'submitted_by',
        'assigned_to',
        'admin_response',
        'responded_at',
        'resolved_at',
        'attachments',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'attachments' => 'array',
        'responded_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'submitted_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'under_review' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-purple-100 text-purple-800',
            'resolved' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'suggestion' => '💡',
            'bug_report' => '🐛',
            'feature_request' => '🚀',
            'complaint' => '😞',
            'compliment' => '👏',
            default => '📝',
        };
    }

    public static function getTypes(): array
    {
        return [
            'suggestion' => 'Suggestion',
            'bug_report' => 'Bug Report',
            'feature_request' => 'Feature Request',
            'complaint' => 'Complaint',
            'compliment' => 'Compliment',
        ];
    }

    public static function getCategories(): array
    {
        return [
            'general' => 'General',
            'system' => 'System/Technical',
            'hr' => 'Human Resources',
            'process' => 'Work Process',
            'other' => 'Other',
        ];
    }

    public static function getPriorities(): array
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'under_review' => 'Under Review',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
        ];
    }
}
