<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'headquarters_id',
        'centre_id',
        'station_id',
        'role',
        'birth_date',
        'birthday_visibility',
        'hire_date',
        'show_work_anniversary',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'hire_date' => 'date',
            'show_work_anniversary' => 'boolean',
        ];
    }

    public function headquarters()
    {
        return $this->belongsTo(Headquarters::class);
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Announcements created by this user
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    /**
     * Announcements read by this user
     */
    public function readAnnouncements(): BelongsToMany
    {
        return $this->belongsToMany(Announcement::class, 'announcement_reads')
            ->withPivot('read_at');
    }

    /**
     * System links favorited by this user
     */
    public function favoriteLinks(): BelongsToMany
    {
        return $this->belongsToMany(SystemLink::class, 'user_favorite_links')
            ->withTimestamps();
    }

    /**
     * Get the user's primary organizational level
     */
    public function getPrimaryLevelAttribute()
    {
        if ($this->department_id) {
            return 'department';
        } elseif ($this->station_id) {
            return 'station';
        } elseif ($this->centre_id) {
            return 'centre';
        } elseif ($this->headquarters_id) {
            return 'headquarters';
        }
        return null;
    }

    /**
     * Check if user can create announcements
     */
    public function canCreateAnnouncements(): bool
    {
        return in_array($this->role, ['super_admin', 'hq_admin', 'centre_admin', 'station_admin']);
    }

    /**
     * Check if user can manage (edit/delete) a specific announcement
     */
    public function canManageAnnouncement(Announcement $announcement): bool
    {
        // Super admin can manage all announcements
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Users can always manage their own announcements
        if ($announcement->created_by === $this->id) {
            return true;
        }

        // HQ admin can manage announcements from their level or below
        if ($this->isHqAdmin()) {
            return true;
        }

        // Centre admin can manage announcements from users in their centre
        if ($this->isCentreAdmin() && $this->centre_id) {
            $creator = $announcement->creator;
            return $creator && $creator->centre_id === $this->centre_id;
        }

        // Station admin can manage announcements from users in their station
        if ($this->isStationAdmin() && $this->station_id) {
            $creator = $announcement->creator;
            return $creator && $creator->station_id === $this->station_id;
        }

        return false;
    }

    /**
     * Check if user is admin at any level
     */
    public function isAdmin(): bool
    {
        return $this->role !== 'staff';
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is HQ admin
     */
    public function isHqAdmin(): bool
    {
        return $this->role === 'hq_admin';
    }

    /**
     * Check if user is centre admin
     */
    public function isCentreAdmin(): bool
    {
        return $this->role === 'centre_admin';
    }

    /**
     * Check if user is station admin
     */
    public function isStationAdmin(): bool
    {
        return $this->role === 'station_admin';
    }

    /**
     * Check if user can manage polls
     */
    public function canManagePolls(): bool
    {
        return $this->isAdmin(); // All admin levels can manage polls
    }

    

    /**
     * Check if user can update a document
     */
    public function canUpdateDocument(Document $document): bool
    {
        // Super admin can update any document
        if ($this->role === 'super_admin') {
            return true;
        }

        // Users can update documents they uploaded
        if ($document->uploaded_by === $this->id) {
            return true;
        }

        // Admins can update documents in their scope
        if (in_array($this->role, ['hq_admin', 'centre_admin', 'station_admin'])) {
            // Check if the document is within their administrative scope
            if ($this->role === 'hq_admin') {
                return true; // HQ admin can update any document
            }

            if ($this->role === 'centre_admin' && $this->centre_id) {
                // Centre admin can update documents from their centre
                $uploader = $document->uploader;
                return $uploader && $uploader->centre_id === $this->centre_id;
            }

            if ($this->role === 'station_admin' && $this->station_id) {
                // Station admin can update documents from their station
                $uploader = $document->uploader;
                return $uploader && $uploader->station_id === $this->station_id;
            }
        }

        return false;
    }

    /**
     * Check if user can download a document
     */
    public function canDownloadDocument(Document $document): bool
    {
        // Check if user can access the document first
        $canAccess = Document::active()
            ->forUser($this)
            ->where('id', $document->id)
            ->exists();

        if (!$canAccess) {
            return false;
        }

        // If document doesn't require special download permission, allow download
        if (!$document->requires_download_permission) {
            return true;
        }

        // Super admin can download anything
        if ($this->role === 'super_admin') {
            return true;
        }

        // Users can download their own documents
        if ($document->uploaded_by === $this->id) {
            return true;
        }

        // Admins can download documents in their scope
        if (in_array($this->role, ['hq_admin', 'centre_admin', 'station_admin'])) {
            return true;
        }

        // Check document access level
        if ($document->access_level === 'public') {
            return true;
        }

        if ($document->access_level === 'restricted') {
            // Restricted documents require at least staff role
            return in_array($this->role, ['staff', 'centre_admin', 'station_admin', 'hq_admin', 'super_admin']);
        }

        // Confidential documents require admin role
        if ($document->access_level === 'confidential') {
            return in_array($this->role, ['centre_admin', 'station_admin', 'hq_admin', 'super_admin']);
        }

        return false;
    }

    /**
     * Check if user can delete a document
     */
    public function canDeleteDocument(Document $document): bool
    {
        // Super admin can delete any document
        if ($this->role === 'super_admin') {
            return true;
        }

        // Users can delete documents they uploaded
        if ($document->uploaded_by === $this->id) {
            return true;
        }

        // Admins can delete documents in their scope
        if (in_array($this->role, ['hq_admin', 'centre_admin', 'station_admin'])) {
            // Check if the document is within their administrative scope
            if ($this->role === 'hq_admin') {
                return true; // HQ admin can delete any document
            }

            if ($this->role === 'centre_admin' && $this->centre_id) {
                // Centre admin can delete documents from their centre
                $uploader = $document->uploader;
                return $uploader && $uploader->centre_id === $this->centre_id;
            }

            if ($this->role === 'station_admin' && $this->station_id) {
                // Station admin can delete documents from their station
                $uploader = $document->uploader;
                return $uploader && $uploader->station_id === $this->station_id;
            }
        }

        return false;
    }

    // Birthday and Anniversary Helper Methods
    public function hasBirthday(): bool
    {
        return !is_null($this->birth_date);
    }

    public function isBirthdayToday(): bool
    {
        if (!$this->hasBirthday()) {
            return false;
        }

        return $this->birth_date->format('m-d') === now()->format('m-d');
    }

    public function isBirthdayThisWeek(): bool
    {
        if (!$this->hasBirthday()) {
            return false;
        }

        $today = now();
        $birthdate = $this->birth_date->setYear($today->year);

        // If birthday already passed this year, check next year
        if ($birthdate->lt($today)) {
            $birthdate = $birthdate->addYear();
        }

        return $birthdate->between($today, $today->copy()->addWeek());
    }

    public function getAge(): ?int
    {
        if (!$this->hasBirthday()) {
            return null;
        }

        return $this->birth_date->age;
    }

    public function hasWorkAnniversary(): bool
    {
        return !is_null($this->hire_date) && $this->show_work_anniversary;
    }

    public function isWorkAnniversaryToday(): bool
    {
        if (!$this->hasWorkAnniversary()) {
            return false;
        }

        return $this->hire_date->format('m-d') === now()->format('m-d');
    }

    public function getYearsOfService(): ?int
    {
        if (!$this->hire_date) {
            return null;
        }

        return $this->hire_date->diffInYears(now());
    }

    public function canViewBirthday(User $viewer): bool
    {
        if ($this->id === $viewer->id) {
            return true; // Can always see own birthday
        }

        if (!$this->hasBirthday()) {
            return false;
        }

        return match ($this->birthday_visibility) {
            'public' => true,
            'team' => $this->isInSameTeam($viewer),
            'private' => false,
            default => false,
        };
    }

    public function isInSameTeam(User $user): bool
    {
        // Same headquarters, centre, or station
        return $this->headquarters_id === $user->headquarters_id ||
            $this->centre_id === $user->centre_id ||
            $this->station_id === $user->station_id;
    }

    // Static methods for queries
    public static function birthdaysToday()
    {
        return static::whereNotNull('birth_date')
            ->whereIn('birthday_visibility', ['public', 'team'])
            ->whereRaw('DATE_FORMAT(birth_date, "%m-%d") = ?', [now()->format('m-d')]);
    }

    public static function birthdaysThisWeek()
    {
        $today = now();
        $nextWeek = $today->copy()->addWeek();

        return static::whereNotNull('birth_date')
            ->whereIn('birthday_visibility', ['public', 'team'])
            ->where(function ($query) use ($today, $nextWeek) {
                for ($date = $today->copy(); $date <= $nextWeek; $date->addDay()) {
                    $query->orWhereRaw('DATE_FORMAT(birth_date, "%m-%d") = ?', [$date->format('m-d')]);
                }
            });
    }

    public static function workAnniversariesToday()
    {
        return static::whereNotNull('hire_date')
            ->where('show_work_anniversary', true)
            ->whereRaw('DATE_FORMAT(hire_date, "%m-%d") = ?', [now()->format('m-d')]);
    }
}
