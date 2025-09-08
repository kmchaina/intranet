<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;

class TestAnnouncementSeeder2 extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create test users if they don't exist
        $hqUser = User::firstOrCreate([
            'email' => 'hq.admin@nimr.or.tz'
        ], [
            'name' => 'HQ Administrator',
            'password' => bcrypt('password'),
            'role' => 'hq_admin',
            'headquarters_id' => 1,
            'email_verified_at' => now(),
        ]);

        $centreUser = User::firstOrCreate([
            'email' => 'centre.admin@nimr.or.tz'
        ], [
            'name' => 'Centre Administrator',
            'password' => bcrypt('password'),
            'role' => 'centre_admin',
            'headquarters_id' => 1,
            'centre_id' => 1, // Amani Research Centre
            'email_verified_at' => now(),
        ]);

        $stationUser = User::firstOrCreate([
            'email' => 'station.admin@nimr.or.tz'
        ], [
            'name' => 'Station Administrator',
            'password' => bcrypt('password'),
            'role' => 'station_admin',
            'headquarters_id' => 1,
            'centre_id' => 1, // Amani Research Centre
            'station_id' => 1, // Amani Hill Station
            'email_verified_at' => now(),
        ]);

        // Create test announcements

        // 1. HQ-only announcement (only HQ users should see this)
        Announcement::create([
            'title' => 'HQ Only: Executive Meeting',
            'content' => 'This is an announcement only for headquarters staff.',
            'category' => 'general',
            'priority' => 'medium',
            'created_by' => $hqUser->id,
            'target_scope' => 'headquarters',
            'is_published' => true,
            'published_at' => now(),
        ]);

        // 2. All NIMR staff announcement (everyone should see this)
        Announcement::create([
            'title' => 'All Staff: Annual Conference',
            'content' => 'This announcement is for all NIMR staff across all levels.',
            'category' => 'event',
            'priority' => 'high',
            'created_by' => $hqUser->id,
            'target_scope' => 'all',
            'is_published' => true,
            'published_at' => now(),
        ]);

        // 3. Centre-only announcement (only centre users should see this)
        Announcement::create([
            'title' => 'Amani Centre: Local Meeting',
            'content' => 'This is an announcement only for Amani Research Centre staff.',
            'category' => 'general',
            'priority' => 'medium',
            'created_by' => $centreUser->id,
            'target_scope' => 'my_centre',
            'is_published' => true,
            'published_at' => now(),
        ]);

        // 4. Centre + stations announcement (centre and its station users should see this)
        Announcement::create([
            'title' => 'Amani Centre & Stations: Research Update',
            'content' => 'This announcement is for Amani Centre and all its stations.',
            'category' => 'info',
            'priority' => 'medium',
            'created_by' => $centreUser->id,
            'target_scope' => 'my_centre_stations',
            'is_published' => true,
            'published_at' => now(),
        ]);

        // 5. Station-only announcement (only station users should see this)
        Announcement::create([
            'title' => 'Amani Hill Station: Equipment Maintenance',
            'content' => 'This announcement is only for Amani Hill Station staff.',
            'category' => 'urgent',
            'priority' => 'high',
            'created_by' => $stationUser->id,
            'target_scope' => 'my_station',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->command->info('Test announcements created successfully!');
        $this->command->info('Test users created with email/password:');
        $this->command->info('- hq.admin@nimr.or.tz / password');
        $this->command->info('- centre.admin@nimr.or.tz / password');
        $this->command->info('- station.admin@nimr.or.tz / password');
    }
}
