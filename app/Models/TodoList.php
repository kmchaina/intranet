<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class TodoList extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_completed',
        'completed_at',
        'category',
        'project',
        'priority',
        'status',
        'due_date',
        'due_time',
        'all_day',
        'reminder_times',
        'estimated_hours',
        'actual_hours',
        'progress_percentage',
        'assigned_to',
        'is_shared',
        'shared_with',
        'watchers',
        'parent_task_id',
        'depends_on',
        'sort_order',
        'tags',
        'custom_properties',
        'color',
        'notes',
        'started_at',
        'last_activity_at',
        'view_count',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'due_date' => 'date',
        'due_time' => 'time',
        'all_day' => 'boolean',
        'reminder_times' => 'array',
        'estimated_hours' => 'integer',
        'actual_hours' => 'integer',
        'progress_percentage' => 'integer',
        'is_shared' => 'boolean',
        'shared_with' => 'array',
        'watchers' => 'array',
        'depends_on' => 'array',
        'sort_order' => 'integer',
        'tags' => 'array',
        'custom_properties' => 'array',
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'view_count' => 'integer',
    ];

    /**
     * Get the user who owns this task
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user assigned to this task
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the parent task
     */
    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(TodoList::class, 'parent_task_id');
    }

    /**
     * Get child tasks (subtasks)
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(TodoList::class, 'parent_task_id')->orderBy('sort_order');
    }

    /**
     * Get the parent task (alias for parentTask)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(TodoList::class, 'parent_task_id');
    }

    /**
     * Get child tasks (alias for subtasks)
     */
    public function children(): HasMany
    {
        return $this->hasMany(TodoList::class, 'parent_task_id')->orderBy('sort_order');
    }

    /**
     * Get tasks that depend on this task
     */
    public function dependents()
    {
        return static::whereJsonContains('depends_on', $this->id)->get();
    }

    /**
     * Get tasks this one depends on
     */
    public function dependencies()
    {
        if (!$this->depends_on) {
            return collect();
        }

        return static::whereIn('id', $this->depends_on)->get();
    }

    /**
     * Get users this task is shared with
     */
    public function sharedUsers()
    {
        if (!$this->is_shared || !$this->shared_with) {
            return collect();
        }

        return User::whereIn('id', $this->shared_with)->get();
    }

    /**
     * Get users watching this task
     */
    public function watchingUsers()
    {
        if (!$this->watchers) {
            return collect();
        }

        return User::whereIn('id', $this->watchers)->get();
    }

    /**
     * Mark task as completed
     */
    public function markCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'status' => 'completed',
            'progress_percentage' => 100,
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Mark task as incomplete
     */
    public function markIncomplete(): void
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null,
            'status' => $this->progress_percentage > 0 ? 'in_progress' : 'todo',
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Update progress and automatically set status
     */
    public function updateProgress(int $percentage): void
    {
        $status = match (true) {
            $percentage == 0 => 'todo',
            $percentage == 100 => 'completed',
            $percentage > 0 => 'in_progress',
            default => $this->status,
        };

        $this->update([
            'progress_percentage' => max(0, min(100, $percentage)),
            'status' => $status,
            'is_completed' => $percentage == 100,
            'completed_at' => $percentage == 100 ? now() : null,
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Record task view
     */
    public function recordView(): void
    {
        $this->increment('view_count');
        $this->update(['last_activity_at' => now()]);
    }

    /**
     * Scope for user's tasks including assigned and shared
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('assigned_to', $user->id)
                ->orWhere(function ($shared) use ($user) {
                    $shared->where('is_shared', true)
                        ->whereJsonContains('shared_with', $user->id);
                })
                ->orWhere(function ($watching) use ($user) {
                    $watching->whereJsonContains('watchers', $user->id);
                });
        });
    }

    /**
     * Scope for pending tasks
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    /**
     * Scope for completed tasks
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope for overdue tasks
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('is_completed', false)
            ->where('due_date', '<', now()->toDateString());
    }

    /**
     * Scope for due today
     */
    public function scopeDueToday(Builder $query): Builder
    {
        return $query->where('is_completed', false)
            ->where('due_date', now()->toDateString());
    }

    /**
     * Scope for due this week
     */
    public function scopeDueThisWeek(Builder $query): Builder
    {
        return $query->where('is_completed', false)
            ->whereBetween('due_date', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString()
            ]);
    }

    /**
     * Scope by priority
     */
    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope by project
     */
    public function scopeByProject(Builder $query, string $project): Builder
    {
        return $query->where('project', $project);
    }

    /**
     * Scope for main tasks (no parent)
     */
    public function scopeMainTasks(Builder $query): Builder
    {
        return $query->whereNull('parent_task_id');
    }

    /**
     * Get task priority color
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'low' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        };
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'todo' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
            'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'blocked' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        };
    }

    /**
     * Check if task is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return !$this->is_completed &&
            $this->due_date &&
            $this->due_date->isPast();
    }

    /**
     * Get days until due
     */
    public function getDaysUntilDueAttribute(): ?int
    {
        if (!$this->due_date || $this->is_completed) {
            return null;
        }

        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Get category icon
     */
    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'work' => 'briefcase',
            'personal' => 'user',
            'research' => 'flask',
            'admin' => 'cog',
            'meeting' => 'users',
            'project' => 'folder',
            default => 'check-square',
        };
    }
}
