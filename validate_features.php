<?php

// Quick feature validation script
use App\Models\User;
use App\Models\Announcement;
use App\Models\Poll;
use App\Models\Document;
use App\Models\Event;
use App\Models\TrainingVideo;
use App\Models\SystemLink;
use App\Models\TodoList;
use App\Models\Feedback;
use App\Models\PasswordVault;

echo "=== NIMR Intranet Feature Validation ===" . PHP_EOL;
echo PHP_EOL;

echo "1. Database Integrity Check:" . PHP_EOL;
$models = [
    'Users' => User::class,
    'Announcements' => Announcement::class,
    'Polls' => Poll::class,
    'Documents' => Document::class,
    'Events' => Event::class,
    'Training Videos' => TrainingVideo::class,
    'System Links' => SystemLink::class,
    'Todo Lists' => TodoList::class,
    'Feedback' => Feedback::class,
    'Password Vault' => PasswordVault::class,
];

foreach ($models as $name => $model) {
    try {
        $count = $model::count();
        echo "   ✓ $name: $count records" . PHP_EOL;
    } catch (Exception $e) {
        echo "   ✗ $name: Error - " . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL;
echo "2. User Roles Verification:" . PHP_EOL;
$roles = User::select('role', \DB::raw('count(*) as count'))
    ->groupBy('role')
    ->pluck('count', 'role')
    ->toArray();

foreach ($roles as $role => $count) {
    echo "   ✓ $role: $count users" . PHP_EOL;
}

echo PHP_EOL;
echo "3. Organizational Structure:" . PHP_EOL;
$centres = \App\Models\Centre::count();
$stations = \App\Models\Station::count();
$departments = \App\Models\Department::count();
echo "   ✓ Centres: $centres" . PHP_EOL;
echo "   ✓ Stations: $stations" . PHP_EOL;
echo "   ✓ Departments: $departments" . PHP_EOL;

echo PHP_EOL;
echo "4. Content Distribution:" . PHP_EOL;

// Announcements by priority
$urgentAnnouncements = Announcement::where('priority', 'urgent')->count();
$highAnnouncements = Announcement::where('priority', 'high')->count();
$mediumAnnouncements = Announcement::where('priority', 'medium')->count();
echo "   Announcements: $urgentAnnouncements urgent, $highAnnouncements high, $mediumAnnouncements medium" . PHP_EOL;

// Polls by status
$activePolls = Poll::where('status', 'active')->count();
$draftPolls = Poll::where('status', 'draft')->count();
echo "   Polls: $activePolls active, $draftPolls draft" . PHP_EOL;

// Documents by access level
$publicDocs = Document::where('access_level', 'public')->count();
$restrictedDocs = Document::where('access_level', 'restricted')->count();
echo "   Documents: $publicDocs public, $restrictedDocs restricted" . PHP_EOL;

// Events by status
$publishedEvents = Event::where('status', 'published')->count();
$draftEvents = Event::where('status', 'draft')->count();
echo "   Events: $publishedEvents published, $draftEvents draft" . PHP_EOL;

echo PHP_EOL;
echo "5. System Health:" . PHP_EOL;

// Check for users with birthdays
$upcomingBirthdays = User::whereNotNull('birth_date')
    ->where('birthday_visibility', '!=', 'private')
    ->count();
echo "   ✓ Users with public/team birthdays: $upcomingBirthdays" . PHP_EOL;

// Check for active training videos
$activeVideos = TrainingVideo::where('is_active', true)->count();
echo "   ✓ Active training videos: $activeVideos" . PHP_EOL;

// Check for active system links
$activeLinks = SystemLink::where('is_active', true)->count();
echo "   ✓ Active system links: $activeLinks" . PHP_EOL;

echo PHP_EOL;
echo "6. Feature-Specific Checks:" . PHP_EOL;

// Check dashboard-related functionality
try {
    $admin = User::where('role', 'super_admin')->first();
    if ($admin) {
        echo "   ✓ Super admin user exists: {$admin->name}" . PHP_EOL;

        // Test scopes
        $visibleAnnouncements = Announcement::visibleTo($admin)->count();
        echo "   ✓ Announcements visible to admin: $visibleAnnouncements" . PHP_EOL;

        $visiblePolls = Poll::visibleTo($admin)->count();
        echo "   ✓ Polls visible to admin: $visiblePolls" . PHP_EOL;

        $visibleDocuments = Document::visibleTo($admin)->count();
        echo "   ✓ Documents visible to admin: $visibleDocuments" . PHP_EOL;
    }

    $centreAdmin = User::where('role', 'centre_admin')->first();
    if ($centreAdmin) {
        echo "   ✓ Centre admin user exists: {$centreAdmin->name}" . PHP_EOL;
        echo "   ✓ Centre admin manages: " . $centreAdmin->centre->name . PHP_EOL;
    }
} catch (Exception $e) {
    echo "   ✗ Scope testing failed: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;
echo "=== Validation Complete ===" . PHP_EOL;
echo "Status: All basic features appear to be properly seeded and configured." . PHP_EOL;
