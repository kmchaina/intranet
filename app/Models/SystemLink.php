<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SystemLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'icon',
        'category',
        'color_scheme',
        'opens_new_tab',
        'requires_vpn',
        'is_active',
        'is_featured',
        'show_on_dashboard',
        'access_level',
        'click_count',
        'added_by',
    ];

    protected $casts = [
        'opens_new_tab' => 'boolean',
        'requires_vpn' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_on_dashboard' => 'boolean',
        'click_count' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'added_by');
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_favorite_links')
                    ->withTimestamps();
    }

    public function isFavoritedBy(?User $user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }

        return $this->favoritedBy()->where('user_id', $user->id)->exists();
    }

    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }

    public function getColorClasses(): array
    {
        $colorMap = [
            'blue' => ['bg-blue-100', 'text-blue-800', 'border-blue-300', 'hover:bg-blue-200'],
            'green' => ['bg-green-100', 'text-green-800', 'border-green-300', 'hover:bg-green-200'],
            'red' => ['bg-red-100', 'text-red-800', 'border-red-300', 'hover:bg-red-200'],
            'purple' => ['bg-purple-100', 'text-purple-800', 'border-purple-300', 'hover:bg-purple-200'],
            'yellow' => ['bg-yellow-100', 'text-yellow-800', 'border-yellow-300', 'hover:bg-yellow-200'],
            'gray' => ['bg-gray-100', 'text-gray-800', 'border-gray-300', 'hover:bg-gray-200'],
        ];

        return $colorMap[$this->color_scheme] ?? $colorMap['blue'];
    }

    public static function getCategories(): array
    {
        return [
            'quick_access' => 'Quick Access',
            'general' => 'General Systems',
            'hr' => 'Human Resources',
            'finance' => 'Finance & Accounting',
            'research' => 'Research Tools',
            'technical' => 'Technical Systems',
            'external' => 'External Services',
            'communication' => 'Communication Tools',
        ];
    }

    public static function getAccessLevels(): array
    {
        return [
            'all' => 'All Staff',
            'admin' => 'Administrators Only',
            'hq' => 'Headquarters Only',
            'centre' => 'Research Centres',
            'station' => 'Research Stations',
        ];
    }

    // Scope: limit links visible to a given user based on access_level
    public function scopeForUser($query, ?User $user)
    {
        if (!$user) {
            return $query->where('access_level', 'all');
        }

        return $query->where(function ($q) use ($user) {
            $q->where('access_level', 'all')
              ->orWhere(function ($hqQ) use ($user) {
                  if ($user->headquarters_id && !$user->centre_id && !$user->station_id) {
                      $hqQ->where('access_level', 'hq');
                  }
              })
              ->orWhere(function ($centreQ) use ($user) {
                  // Centre-level links visible to centre staff and station staff belonging to a centre
                  if ($user->centre_id || $user->station_id) {
                      $centreQ->where('access_level', 'centre');
                  }
              })
              ->orWhere(function ($stationQ) use ($user) {
                  if ($user->station_id) {
                      $stationQ->where('access_level', 'station');
                  }
              })
              ->orWhere(function ($adminQ) use ($user) {
                  if ($user->isAdmin()) {
                      $adminQ->where('access_level', 'admin');
                  }
              });
        });
    }

    public static function getColorSchemes(): array
    {
        return [
            'blue' => 'Blue',
            'green' => 'Green',
            'red' => 'Red',
            'purple' => 'Purple',
            'yellow' => 'Yellow',
            'gray' => 'Gray',
        ];
    }
}
