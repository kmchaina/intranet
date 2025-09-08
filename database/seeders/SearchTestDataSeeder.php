<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Announcement;
use App\Models\News;
use App\Models\Document;

class SearchTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $admin = $users->where('role', 'super_admin')->first();
        $centreAdmin = $users->where('role', 'centre_admin')->first();
        $staff = $users->where('role', 'staff')->first();

        // Create initial announcements for system demonstration
        Announcement::create([
            'title' => 'Welcome to NIMR Intranet System',
            'content' => 'Welcome to the new NIMR Intranet System. This platform will enhance communication and collaboration across all NIMR facilities.',
            'created_by' => $admin->id,
            'target_scope' => 'all',
            'priority' => 'high',
            'expires_at' => now()->addMonths(6),
        ]);

        Announcement::create([
            'title' => 'System Features Overview',
            'content' => 'The intranet system includes announcements, document management, global search, and user-specific dashboards. Explore the features and provide feedback.',
            'created_by' => $admin->id,
            'target_scope' => 'all',
            'priority' => 'medium',
            'expires_at' => now()->addMonths(3),
        ]);

        // Create initial news items
        News::create([
            'title' => 'NIMR Intranet System Launch',
            'content' => 'NIMR has successfully launched its new intranet system to improve internal communication and document management across all research facilities.',
            'author_id' => $admin->id,
            'location' => 'NIMR Headquarters',
            'is_featured' => true,
            'status' => 'published',
            'published_at' => now(),
        ]);

        News::create([
            'title' => 'Digital Transformation Initiative',
            'content' => 'NIMR continues its digital transformation efforts to enhance research capabilities and administrative efficiency.',
            'author_id' => $admin->id,
            'location' => 'Dar es Salaam',
            'is_featured' => false,
            'status' => 'published',
            'published_at' => now()->subDays(1),
        ]);
    }
}
