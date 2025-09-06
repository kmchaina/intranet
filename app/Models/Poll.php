<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'options',
        'max_rating',
        'anonymous',
        'show_results',
        'allow_comments',
        'visibility',
        'visible_to',
        'status',
        'starts_at',
        'ends_at',
        'created_by',
    ];

    protected $casts = [
        'options' => 'array',
        'visible_to' => 'array',
        'anonymous' => 'boolean',
        'show_results' => 'boolean',
        'allow_comments' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(PollResponse::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeVisibleTo($query, ?User $user = null)
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return $query->where('visibility', 'public');
        }

        return $query->where(function ($q) use ($user) {
            $q->where('visibility', 'public')
                ->orWhere(function ($q2) use ($user) {
                    $q2->where('visibility', 'department')
                        ->where('visible_to', 'like', '%"' . $user->department . '"%');
                })
                ->orWhere(function ($q3) use ($user) {
                    $q3->where('visibility', 'custom')
                        ->where('visible_to', 'like', '%"' . $user->id . '"%');
                })
                ->orWhere('created_by', $user->id);
        });
    }

    // Helper methods
    public function canVote(?User $user = null): bool
    {
        $user = $user ?? Auth::user();

        if ($this->status !== 'active') {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        if (!$this->anonymous && !$user) {
            return false;
        }

        if ($user && $this->hasUserVoted($user)) {
            return false;
        }

        return $this->isVisibleTo($user);
    }

    public function hasUserVoted(?User $user = null): bool
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return false;
        }

        return $this->responses()->where('user_id', $user->id)->exists();
    }

    public function isVisibleTo(?User $user = null): bool
    {
        $user = $user ?? Auth::user();

        if ($this->visibility === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($this->created_by === $user->id) {
            return true;
        }

        if ($this->visibility === 'department') {
            $departments = $this->visible_to ?? [];
            return in_array($user->department, $departments);
        }

        if ($this->visibility === 'custom') {
            $allowedUsers = $this->visible_to ?? [];
            return in_array($user->id, $allowedUsers);
        }

        return false;
    }

    public function canManage(?User $user = null): bool
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return false;
        }

        return $user->id === $this->created_by || $user->canManagePolls();
    }

    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function getResponseCount(): int
    {
        return $this->responses()->count();
    }

    public function getResults(): array
    {
        $responses = $this->responses()->get();
        $results = [];

        switch ($this->type) {
            case 'single_choice':
            case 'multiple_choice':
                $results = $this->getChoiceResults($responses);
                break;
            case 'rating':
                $results = $this->getRatingResults($responses);
                break;
            case 'yes_no':
                $results = $this->getYesNoResults($responses);
                break;
        }

        return $results;
    }

    private function getChoiceResults($responses): array
    {
        $results = [];
        $totalResponses = $responses->count();

        foreach ($this->options as $index => $option) {
            $count = 0;
            foreach ($responses as $response) {
                $responseData = $response->response_data;
                if ($this->type === 'single_choice') {
                    if ($responseData['selected_option'] == $index) {
                        $count++;
                    }
                } else { // multiple_choice
                    if (in_array($index, $responseData['selected_options'] ?? [])) {
                        $count++;
                    }
                }
            }

            $results[] = [
                'option' => $option,
                'count' => $count,
                'percentage' => $totalResponses > 0 ? round(($count / $totalResponses) * 100, 1) : 0,
            ];
        }

        return $results;
    }

    private function getRatingResults($responses): array
    {
        $ratings = [];
        $totalRating = 0;
        $count = 0;

        for ($i = 1; $i <= $this->max_rating; $i++) {
            $ratings[$i] = 0;
        }

        foreach ($responses as $response) {
            $rating = $response->response_data['rating'];
            $ratings[$rating]++;
            $totalRating += $rating;
            $count++;
        }

        $averageRating = $count > 0 ? round($totalRating / $count, 2) : 0;

        $results = [];
        foreach ($ratings as $rating => $ratingCount) {
            $results[] = [
                'rating' => $rating,
                'count' => $ratingCount,
                'percentage' => $count > 0 ? round(($ratingCount / $count) * 100, 1) : 0,
            ];
        }

        return [
            'average' => $averageRating,
            'total_responses' => $count,
            'breakdown' => $results,
        ];
    }

    private function getYesNoResults($responses): array
    {
        $yes = 0;
        $no = 0;

        foreach ($responses as $response) {
            if ($response->response_data['answer'] === 'yes') {
                $yes++;
            } else {
                $no++;
            }
        }

        $total = $yes + $no;

        return [
            'yes' => [
                'count' => $yes,
                'percentage' => $total > 0 ? round(($yes / $total) * 100, 1) : 0,
            ],
            'no' => [
                'count' => $no,
                'percentage' => $total > 0 ? round(($no / $total) * 100, 1) : 0,
            ],
            'total' => $total,
        ];
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'active' => 'green',
            'closed' => 'yellow',
            'archived' => 'red',
            default => 'gray',
        };
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'single_choice' => 'Single Choice',
            'multiple_choice' => 'Multiple Choice',
            'rating' => 'Rating',
            'yes_no' => 'Yes/No',
            default => $this->type,
        };
    }

    // Static methods
    public static function getTypes(): array
    {
        return [
            'single_choice' => 'Single Choice',
            'multiple_choice' => 'Multiple Choice',
            'rating' => 'Rating (1-5, 1-10, etc.)',
            'yes_no' => 'Yes/No',
        ];
    }

    public static function getVisibilityOptions(): array
    {
        return [
            'public' => 'Everyone',
            'department' => 'Specific Departments',
            'custom' => 'Custom Users',
        ];
    }
}
