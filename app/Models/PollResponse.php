<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'user_id',
        'response_data',
        'comment',
        'ip_address',
    ];

    protected $casts = [
        'response_data' => 'array',
    ];

    // Relationships
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isAnonymous(): bool
    {
        return $this->user_id === null;
    }

    public function getResponseSummary(): string
    {
        $poll = $this->poll;

        switch ($poll->type) {
            case 'single_choice':
                $selectedIndex = $this->response_data['selected_option'];
                return $poll->options[$selectedIndex] ?? 'Unknown option';

            case 'multiple_choice':
                $selectedOptions = $this->response_data['selected_options'] ?? [];
                $optionTexts = [];
                foreach ($selectedOptions as $index) {
                    $optionTexts[] = $poll->options[$index] ?? 'Unknown option';
                }
                return implode(', ', $optionTexts);

            case 'rating':
                $rating = $this->response_data['rating'];
                return "{$rating}/{$poll->max_rating}";

            case 'yes_no':
                return ucfirst($this->response_data['answer']);

            default:
                return 'Unknown response type';
        }
    }
}
