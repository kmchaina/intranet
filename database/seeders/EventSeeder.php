<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first(); // Get the first user as event creator

        if (!$user) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $events = [
            [
                'title' => 'NIMR Monthly Research Review',
                'description' => 'Monthly review of ongoing research projects across all NIMR centres. Presentation of progress reports and discussion of challenges and opportunities.',
                'start_datetime' => Carbon::now()->addDays(7)->setTime(9, 0),
                'end_datetime' => Carbon::now()->addDays(7)->setTime(17, 0),
                'all_day' => false,
                'location' => 'NIMR Headquarters Conference Hall',
                'venue' => 'Main Conference Hall',
                'venue_details' => 'First floor, equipped with video conferencing facilities',
                'category' => 'meeting',
                'priority' => 'high',
                'status' => 'published',
                'requires_rsvp' => true,
                'max_attendees' => 50,
                'rsvp_deadline' => Carbon::now()->addDays(5)->setTime(23, 59),
                'visibility_scope' => 'all',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Data Analysis Workshop',
                'description' => 'Hands-on workshop on advanced data analysis techniques using R and Python. Suitable for researchers at all levels.',
                'start_datetime' => Carbon::now()->addDays(14)->setTime(8, 30),
                'end_datetime' => Carbon::now()->addDays(14)->setTime(16, 30),
                'all_day' => false,
                'location' => 'NIMR Training Center',
                'venue' => 'Computer Lab 1',
                'venue_details' => 'Bring your laptop. Refreshments will be provided.',
                'category' => 'training',
                'priority' => 'medium',
                'status' => 'published',
                'requires_rsvp' => true,
                'max_attendees' => 25,
                'rsvp_deadline' => Carbon::now()->addDays(10)->setTime(23, 59),
                'visibility_scope' => 'all',
                'created_by' => $user->id,
            ],
            [
                'title' => 'International Conference on Tropical Medicine',
                'description' => 'Annual international conference bringing together researchers, clinicians, and policymakers to discuss latest advances in tropical medicine research.',
                'start_datetime' => Carbon::now()->addDays(30)->setTime(8, 0),
                'end_datetime' => Carbon::now()->addDays(32)->setTime(18, 0),
                'all_day' => true,
                'location' => 'Dar es Salaam Convention Centre',
                'venue' => 'Main Auditorium',
                'venue_details' => 'Three-day conference with multiple sessions and poster presentations',
                'category' => 'conference',
                'priority' => 'urgent',
                'status' => 'published',
                'requires_rsvp' => true,
                'max_attendees' => 300,
                'rsvp_deadline' => Carbon::now()->addDays(20)->setTime(23, 59),
                'visibility_scope' => 'all',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Field Research Ethics Seminar',
                'description' => 'Seminar on ethical considerations in field-based research, including community engagement and informed consent procedures.',
                'start_datetime' => Carbon::now()->addDays(21)->setTime(14, 0),
                'end_datetime' => Carbon::now()->addDays(21)->setTime(17, 0),
                'all_day' => false,
                'location' => 'NIMR Headquarters',
                'venue' => 'Seminar Room B',
                'venue_details' => 'Interactive session with case studies',
                'category' => 'seminar',
                'priority' => 'medium',
                'status' => 'published',
                'requires_rsvp' => true,
                'max_attendees' => 30,
                'rsvp_deadline' => Carbon::now()->addDays(18)->setTime(23, 59),
                'visibility_scope' => 'centres',
                'created_by' => $user->id,
            ],
            [
                'title' => 'NIMR Annual Staff Retreat',
                'description' => 'Annual staff retreat for team building, strategic planning, and celebration of achievements. A great opportunity to network with colleagues from different departments.',
                'start_datetime' => Carbon::now()->addDays(45)->setTime(0, 0),
                'end_datetime' => Carbon::now()->addDays(47)->setTime(23, 59),
                'all_day' => true,
                'location' => 'Moshi, Kilimanjaro Region',
                'venue' => 'Mountain Resort',
                'venue_details' => 'Three-day retreat with accommodation and meals provided',
                'category' => 'social',
                'priority' => 'high',
                'status' => 'published',
                'requires_rsvp' => true,
                'max_attendees' => 150,
                'rsvp_deadline' => Carbon::now()->addDays(35)->setTime(23, 59),
                'visibility_scope' => 'all',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Laboratory Safety Training',
                'description' => 'Mandatory safety training for all laboratory staff. Covers biosafety protocols, emergency procedures, and equipment handling.',
                'start_datetime' => Carbon::now()->addDays(10)->setTime(9, 0),
                'end_datetime' => Carbon::now()->addDays(10)->setTime(12, 0),
                'all_day' => false,
                'location' => 'NIMR Main Laboratory',
                'venue' => 'Safety Training Room',
                'venue_details' => 'Mandatory for all lab personnel',
                'category' => 'training',
                'priority' => 'urgent',
                'status' => 'published',
                'requires_rsvp' => true,
                'max_attendees' => 40,
                'rsvp_deadline' => Carbon::now()->addDays(7)->setTime(23, 59),
                'visibility_scope' => 'all',
                'created_by' => $user->id,
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        $this->command->info('Sample events created successfully!');
    }
}
