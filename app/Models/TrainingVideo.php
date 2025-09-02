<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'video_type',
        'thumbnail_url',
        'category',
        'duration_minutes',
        'is_featured',
        'is_active',
        'target_audience',
        'uploaded_by',
        'view_count',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'view_count' => 'integer',
        'duration_minutes' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }

    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_minutes) {
            return 'Duration not specified';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    public function getEmbedUrlAttribute(): string
    {
        if ($this->video_type === 'youtube') {
            // Convert YouTube watch URL to embed URL with better parameters
            $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
            if (preg_match($pattern, $this->video_url, $matches)) {
                $videoId = $matches[1];
                // Use simpler embed URL without origin restriction
                return "https://www.youtube-nocookie.com/embed/{$videoId}?rel=0&modestbranding=1";
            }
        } elseif ($this->video_type === 'vimeo') {
            // Convert Vimeo URL to embed URL
            $pattern = '/vimeo\.com\/(\d+)/';
            if (preg_match($pattern, $this->video_url, $matches)) {
                return "https://player.vimeo.com/video/" . $matches[1] . "?title=0&byline=0&portrait=0";
            }
        }

        return $this->video_url;
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public static function getCategories(): array
    {
        return [
            'general' => 'General Training',
            'technical' => 'Technical Skills',
            'hr' => 'HR & Administration',
            'safety' => 'Safety & Compliance',
            'research' => 'Research Methods',
            'software' => 'Software Training',
            'orientation' => 'New Employee Orientation',
        ];
    }

    public static function getTargetAudiences(): array
    {
        return [
            'all' => 'All Staff',
            'hq' => 'Headquarters Only',
            'centre' => 'Research Centres',
            'station' => 'Research Stations',
        ];
    }
}
