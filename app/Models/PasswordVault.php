<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class PasswordVault extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'website_url',
        'username',
        'encrypted_password',
        'notes',
        'category',
        'folder',
        'is_favorite',
        'last_used_at',
        'password_changed_at',
        'password_strength',
        'requires_2fa',
        'encrypted_2fa_secret',
        'is_shared',
        'shared_with',
        'share_permission',
        'custom_fields',
        'icon',
        'login_count',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'last_used_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'requires_2fa' => 'boolean',
        'is_shared' => 'boolean',
        'shared_with' => 'array',
        'custom_fields' => 'array',
        'login_count' => 'integer',
    ];

    protected $hidden = [
        'encrypted_password',
        'encrypted_2fa_secret',
    ];

    /**
     * Get the user who owns this password entry
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get users this password is shared with
     */
    public function sharedUsers()
    {
        if (!$this->is_shared || !$this->shared_with) {
            return collect();
        }

        return User::whereIn('id', $this->shared_with)->get();
    }

    /**
     * Encrypt and set password
     */
    public function setPassword(string $password): void
    {
        $this->encrypted_password = Crypt::encrypt($password);
        $this->password_changed_at = now();
        $this->password_strength = $this->calculatePasswordStrength($password);
    }

    /**
     * Decrypt and get password
     */
    public function getPassword(): string
    {
        return Crypt::decrypt($this->encrypted_password);
    }

    /**
     * Set 2FA secret
     */
    public function set2FASecret(string $secret): void
    {
        $this->encrypted_2fa_secret = Crypt::encrypt($secret);
        $this->requires_2fa = true;
    }

    /**
     * Get 2FA secret
     */
    public function get2FASecret(): ?string
    {
        if (!$this->encrypted_2fa_secret) {
            return null;
        }

        return Crypt::decrypt($this->encrypted_2fa_secret);
    }

    /**
     * Calculate password strength (1-5 scale)
     */
    private function calculatePasswordStrength(string $password): int
    {
        $score = 0;
        $length = strlen($password);

        // Length scoring
        if ($length >= 8) $score++;
        if ($length >= 12) $score++;
        if ($length >= 16) $score++;

        // Character variety
        if (preg_match('/[a-z]/', $password)) $score++;
        if (preg_match('/[A-Z]/', $password)) $score++;
        if (preg_match('/\d/', $password)) $score++;
        if (preg_match('/[^a-zA-Z\d]/', $password)) $score++;

        // Return 1-5 scale
        return min(5, max(1, intval($score / 2) + 1));
    }

    /**
     * Record password usage
     */
    public function recordUsage(): void
    {
        $this->increment('login_count');
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Scope for user's passwords including shared ones
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere(function ($shared) use ($user) {
                    $shared->where('is_shared', true)
                        ->whereJsonContains('shared_with', $user->id);
                });
        });
    }

    /**
     * Scope for favorites
     */
    public function scopeFavorites(Builder $query): Builder
    {
        return $query->where('is_favorite', true);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope by folder
     */
    public function scopeByFolder(Builder $query, string $folder): Builder
    {
        return $query->where('folder', $folder);
    }

    /**
     * Get password age in days
     */
    public function getPasswordAgeAttribute(): int
    {
        if (!$this->password_changed_at) {
            return 0;
        }

        return $this->password_changed_at->diffInDays(now());
    }

    /**
     * Check if password needs to be changed (older than 90 days)
     */
    public function getNeedsPasswordChangeAttribute(): bool
    {
        return $this->password_age > 90;
    }

    /**
     * Get password strength color for UI
     */
    public function getPasswordStrengthColorAttribute(): string
    {
        return match ($this->password_strength) {
            1 => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            2 => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            3 => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            4 => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            5 => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    /**
     * Get password strength label
     */
    public function getPasswordStrengthLabelAttribute(): string
    {
        return match ($this->password_strength) {
            1 => 'Very Weak',
            2 => 'Weak',
            3 => 'Fair',
            4 => 'Strong',
            5 => 'Very Strong',
            default => 'Unknown',
        };
    }

    /**
     * Get category icon
     */
    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'work' => 'briefcase',
            'personal' => 'user',
            'social' => 'users',
            'banking' => 'credit-card',
            'shopping' => 'shopping-cart',
            'entertainment' => 'film',
            'education' => 'graduation-cap',
            'health' => 'heart',
            default => 'key',
        };
    }
}
