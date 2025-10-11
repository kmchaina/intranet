<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Document extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_name',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
        'version',
        'parent_document_id',
        'visibility_scope',
        'target_centres',
        'target_stations',
        'category',
        'tags',
        'access_level',
        'requires_download_permission',
        'uploaded_by',
        'last_accessed_at',
        'download_count',
        'is_active',
        'expires_at'
    ];

    protected $casts = [
        'target_centres' => 'array',
        'target_stations' => 'array',
        'tags' => 'array',
        'requires_download_permission' => 'boolean',
        'is_active' => 'boolean',
        'last_accessed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user who uploaded this document
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the parent document (for versioning)
     */
    public function parentDocument(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'parent_document_id');
    }

    /**
     * Get all versions of this document
     */
    public function versions(): HasMany
    {
        return $this->hasMany(Document::class, 'parent_document_id')->orderBy('version', 'desc');
    }

    /**
     * Scope to get active documents
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope to get documents for a specific user based on their organizational hierarchy
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            // All NIMR documents - everyone should see these for now
            $q->where('visibility_scope', 'all')

                // Headquarters level documents
                ->orWhere(function ($subQ) use ($user) {
                    if ($user->headquarters_id && !$user->centre_id && !$user->station_id) {
                        $subQ->where('visibility_scope', 'headquarters');
                    }
                })

                // Centre level documents
                ->orWhere(function ($subQ) use ($user) {
                    if ($user->centre_id) {
                        $subQ->where('visibility_scope', 'centres')
                            ->orWhere(function ($centreQ) use ($user) {
                                $centreQ->where('visibility_scope', 'specific')
                                    ->whereJsonContains('target_centres', $user->centre_id);
                            });
                    }
                })

                // Station level documents
                ->orWhere(function ($subQ) use ($user) {
                    if ($user->station_id) {
                        $subQ->where('visibility_scope', 'stations')
                            ->orWhere(function ($stationQ) use ($user) {
                                $stationQ->where('visibility_scope', 'specific')
                                    ->whereJsonContains('target_stations', $user->station_id);
                            });
                    }
                })

                // Documents uploaded by this user
                ->orWhere('uploaded_by', $user->id);
        });
    }

    /**
     * Scope alias for whereCanAccess - documents the user can access
     */
    public function scopeWhereCanAccess(Builder $query, User $user): Builder
    {
        return $this->scopeForUser($query, $user)->active();
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file icon based on mime type
     */
    public function getFileIconAttribute(): string
    {
        $mimeType = $this->mime_type;

        if (str_contains($mimeType, 'pdf')) {
            return 'fas fa-file-pdf text-red-500';
        } elseif (str_contains($mimeType, 'word') || str_contains($mimeType, 'document')) {
            return 'fas fa-file-word text-blue-500';
        } elseif (str_contains($mimeType, 'excel') || str_contains($mimeType, 'spreadsheet')) {
            return 'fas fa-file-excel text-green-500';
        } elseif (str_contains($mimeType, 'powerpoint') || str_contains($mimeType, 'presentation')) {
            return 'fas fa-file-powerpoint text-orange-500';
        } elseif (str_contains($mimeType, 'image')) {
            return 'fas fa-file-image text-purple-500';
        } elseif (str_contains($mimeType, 'video')) {
            return 'fas fa-file-video text-pink-500';
        } elseif (str_contains($mimeType, 'audio')) {
            return 'fas fa-file-audio text-yellow-500';
        } elseif (str_contains($mimeType, 'zip') || str_contains($mimeType, 'archive')) {
            return 'fas fa-file-archive text-gray-500';
        } else {
            return 'fas fa-file text-gray-400';
        }
    }

    /**
     * Check if document is an image
     */
    public function isImage(): bool
    {
        return str_contains($this->mime_type, 'image');
    }

    /**
     * Increment download count and update last accessed
     */
    public function recordDownload(): void
    {
        $this->increment('download_count');
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Get category badge color
     */
    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'human_resources' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'ict' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            'internal_audit' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'legal' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'procurement' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'drira' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
            'drcp' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
            'public_relations' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'finance' => 'bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-300',
            'planning' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
        };
    }

    /**
     * Get category display name
     */
    public function getCategoryNameAttribute(): string
    {
        $departments = config('departments');
        return $departments[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }

    /**
     * Get human readable file size
     */
    public function getFileSize(): string
    {
        if (!$this->file_size) {
            return 'Unknown size';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
