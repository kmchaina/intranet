<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'delivery_mode',
        'duration_minutes',
        'is_active',
        'target_audience',
        'resource_link',
        'attachment_path',
        'uploaded_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public static function categories(): array
    {
        return [
            'policy' => 'Policy & Compliance',
            'technical' => 'Technical Skills',
            'orientation' => 'Orientation',
            'safety' => 'Safety & Wellbeing',
            'administrative' => 'Administrative',
        ];
    }

    public static function audiences(): array
    {
        return [
            'all' => 'All Staff',
            'hq' => 'Headquarters Staff',
            'centre' => 'Centre Staff',
            'station' => 'Station Staff',
        ];
    }
}
