<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'station_id',
        'centre_id',
        'headquarters_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function headquarters(): BelongsTo
    {
        return $this->belongsTo(Headquarters::class);
    }

    public function centre(): BelongsTo
    {
        return $this->belongsTo(Centre::class);
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the parent entity (headquarters, centre, or station)
     */
    public function getParentAttribute()
    {
        if ($this->station_id) {
            return $this->station;
        } elseif ($this->centre_id) {
            return $this->centre;
        } elseif ($this->headquarters_id) {
            return $this->headquarters;
        }
        return null;
    }

    /**
     * Get the parent type
     */
    public function getParentTypeAttribute()
    {
        if ($this->station_id) {
            return 'station';
        } elseif ($this->centre_id) {
            return 'centre';
        } elseif ($this->headquarters_id) {
            return 'headquarters';
        }
        return null;
    }
}
