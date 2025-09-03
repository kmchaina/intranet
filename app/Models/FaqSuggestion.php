<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaqSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'suggested_question',
        'context',
        'category',
        'status',
        'suggested_by',
        'reviewed_by',
        'converted_to_faq_id',
        'admin_notes',
        'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function suggester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'suggested_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function convertedFaq(): BelongsTo
    {
        return $this->belongsTo(Faq::class, 'converted_to_faq_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Methods
    public function getCategoryLabel(): string
    {
        return Faq::getCategories()[$this->category] ?? $this->category;
    }

    public function getStatusLabel(): string
    {
        return [
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'converted' => 'Converted to FAQ',
        ][$this->status] ?? $this->status;
    }

    public function approve($reviewerId, $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_notes' => $notes
        ]);
    }

    public function reject($reviewerId, $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_notes' => $notes
        ]);
    }

    public function convertToFaq($faqId, $reviewerId): void
    {
        $this->update([
            'status' => 'converted',
            'converted_to_faq_id' => $faqId,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now()
        ]);
    }
}
