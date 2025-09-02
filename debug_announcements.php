<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\Announcement;
use App\Models\User;

// Get user
$user = User::where('email', 'test@example.com')->first();
if (!$user) {
    echo "User not found!\n";
    exit;
}

echo "=== USER INFO ===\n";
echo "ID: " . $user->id . "\n";
echo "Email: " . $user->email . "\n";
echo "Role: " . $user->role . "\n";
echo "Centre ID: " . ($user->centre_id ?? 'null') . "\n";
echo "Station ID: " . ($user->station_id ?? 'null') . "\n";
echo "Headquarters ID: " . ($user->headquarters_id ?? 'null') . "\n\n";

echo "=== LATEST ANNOUNCEMENTS ===\n";
$allAnnouncements = Announcement::latest()->take(3)->get();
foreach ($allAnnouncements as $announcement) {
    echo "ID: " . $announcement->id . "\n";
    echo "Title: " . $announcement->title . "\n";
    echo "Published: " . ($announcement->is_published ? 'YES' : 'NO') . "\n";
    echo "Published At: " . $announcement->published_at . "\n";
    echo "Target Scope: " . $announcement->target_scope . "\n";
    echo "Expires At: " . ($announcement->expires_at ?? 'Never') . "\n";
    echo "---\n";
}

echo "\n=== PUBLISHED ANNOUNCEMENTS ===\n";
$publishedAnnouncements = Announcement::published()->get();
echo "Count: " . $publishedAnnouncements->count() . "\n";

echo "\n=== ANNOUNCEMENTS FOR USER ===\n";
$userAnnouncements = Announcement::published()->forUser($user)->get();
echo "Count: " . $userAnnouncements->count() . "\n";

foreach ($userAnnouncements as $announcement) {
    echo "- " . $announcement->title . " (Scope: " . $announcement->target_scope . ")\n";
}
