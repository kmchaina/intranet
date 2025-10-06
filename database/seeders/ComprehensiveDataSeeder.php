<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Centre;
use App\Models\Station;
use App\Models\Announcement;
use App\Models\Poll;
use App\Models\Document;
use App\Models\Event;
use App\Models\TodoList;
use App\Models\TrainingModule;
use App\Models\SystemLink;
use App\Models\Feedback;
use App\Models\PasswordVault;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure canonical headquarters departments are present / updated
        $this->call(HeadquartersDepartmentSeeder::class);
        // Ensure we have a test user with admin role
        $admin = User::updateOrCreate(
            ['email' => 'admin@nimr.or.tz'],
            [
                'name' => 'System Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'headquarters_id' => 1,
                'birth_date' => Carbon::today()->subYears(35),
                'birthday_visibility' => 'public',
                'hire_date' => Carbon::today()->subYears(5),
                'show_work_anniversary' => true,
            ]
        );

        $centre = Centre::first();
        $station = Station::with('centre')->first();

        $centreAdmin = $centre
            ? User::where('centre_id', $centre->id)->where('role', 'centre_admin')->first()
            : null;
        $stationAdmin = $station
            ? User::where('station_id', $station->id)->where('role', 'station_admin')->first()
            : null;

        // Regular staff
        $department = Department::first();
        if (!$department) {
            $department = Department::create([
                'name' => 'Research & Development',
                'code' => 'RD01',
                'description' => 'Core research department',
                'status' => 'active',
            ]);
        }
        $staff = User::updateOrCreate(
            ['email' => 'staff@nimr.or.tz'],
            [
                'name' => 'Staff Member',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'staff',
                'centre_id' => $centre?->id,
                'department_id' => $department->id,
                'birth_date' => Carbon::tomorrow(),
                'birthday_visibility' => 'team',
                'hire_date' => Carbon::today()->subYear(),
                'show_work_anniversary' => false,
            ]
        );

        // Create Announcements
        $announcements = [
            [
                'title' => 'Welcome to NIMR Intranet System',
                'content' => '<p>We are excited to introduce our new comprehensive intranet system designed to enhance communication and collaboration across all NIMR facilities.</p><p>This platform includes announcements, document management, polls, and much more!</p>',
                'category' => 'general',
                'priority' => 'high',
                'created_by' => $admin->id,
                'target_scope' => 'all',
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Centre Monthly Meeting - ' . $centre->name,
                'content' => '<p>All staff from ' . $centre->name . ' are invited to attend the monthly coordination meeting.</p><p><strong>Date:</strong> Next Friday<br><strong>Time:</strong> 2:00 PM<br><strong>Venue:</strong> Conference Room</p>',
                'category' => 'event',
                'priority' => 'medium',
                'created_by' => $centreAdmin->id,
                'target_scope' => 'specific',
                'target_centres' => [$centre->id],
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Research Safety Guidelines Update',
                'content' => '<p>Important updates to our research safety protocols have been implemented. All research staff must review and acknowledge these changes.</p><ul><li>Updated PPE requirements</li><li>New chemical storage procedures</li><li>Emergency response protocols</li></ul>',
                'category' => 'urgent',
                'priority' => 'urgent',
                'created_by' => $admin->id,
                'target_scope' => 'all',
                'is_published' => true,
                'published_at' => now()->subDays(1),
                'email_notification' => true,
            ],
            [
                'title' => 'Station Equipment Maintenance - ' . $station->name,
                'content' => '<p>Scheduled maintenance for research equipment at ' . $station->name . ' will take place next week.</p><p>Please plan your research activities accordingly.</p>',
                'category' => 'info',
                'priority' => 'medium',
                'created_by' => $stationAdmin->id,
                'target_scope' => 'specific',
                'target_stations' => [$station->id],
                'is_published' => true,
                'published_at' => now()->subHours(6),
            ],
        ];

        foreach ($announcements as $announcementData) {
            Announcement::updateOrCreate(
                ['title' => $announcementData['title']],
                $announcementData
            );
        }

        // Create Polls
        $polls = [
            [
                'title' => 'Preferred Meeting Time for All-Staff Meetings',
                'description' => 'Help us determine the best time for monthly all-staff meetings that works for everyone.',
                'type' => 'single_choice',
                'options' => ['Morning (9:00 AM)', 'Lunch Time (12:00 PM)', 'Afternoon (2:00 PM)', 'Evening (4:00 PM)'],
                'anonymous' => false,
                'show_results' => true,
                'allow_comments' => true,
                'visibility' => 'public',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Rate Our New Intranet System',
                'description' => 'Please rate your experience with our new intranet system.',
                'type' => 'rating',
                'max_rating' => 5,
                'anonymous' => true,
                'show_results' => true,
                'allow_comments' => true,
                'visibility' => 'public',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Centre Training Preferences',
                'description' => 'What types of training would be most beneficial for our centre?',
                'type' => 'multiple_choice',
                'options' => ['Research Methodology', 'Data Analysis', 'Laboratory Safety', 'Leadership Skills', 'Technical Writing'],
                'anonymous' => false,
                'show_results' => true,
                'allow_comments' => true,
                'visibility' => 'public',
                'status' => 'active',
                'created_by' => $centreAdmin->id,
            ],
            [
                'title' => 'Should we extend library hours?',
                'description' => 'Considering extending our library operating hours.',
                'type' => 'yes_no',
                'anonymous' => false,
                'show_results' => true,
                'allow_comments' => true,
                'visibility' => 'public',
                'status' => 'active',
                'created_by' => $staff->id,
            ],
        ];

        foreach ($polls as $pollData) {
            Poll::updateOrCreate(
                ['title' => $pollData['title']],
                $pollData
            );
        }

        // Create Documents
        $documents = [
            [
                'title' => 'NIMR Research Guidelines 2025',
                'description' => 'Comprehensive research guidelines and procedures for all NIMR facilities.',
                'file_name' => 'nimr_research_guidelines_2025.pdf',
                'original_name' => 'NIMR Research Guidelines 2025.pdf',
                'file_path' => 'documents/nimr_research_guidelines_2025.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 2048576, // 2MB
                'visibility_scope' => 'all',
                'category' => 'policy',
                'tags' => ['research', 'guidelines', 'policy'],
                'access_level' => 'public',
                'uploaded_by' => $admin->id,
                'is_active' => true,
            ],
            [
                'title' => 'Centre Specific Procedures Manual',
                'description' => 'Detailed procedures specific to centre operations and protocols.',
                'file_name' => 'centre_procedures_manual.pdf',
                'original_name' => 'Centre Procedures Manual.pdf',
                'file_path' => 'documents/centre_procedures_manual.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 1536000, // 1.5MB
                'visibility_scope' => 'centres',
                'category' => 'manual',
                'tags' => ['procedures', 'centre', 'operations'],
                'access_level' => 'restricted',
                'uploaded_by' => $centreAdmin->id,
                'is_active' => true,
            ],
            [
                'title' => 'Equipment Safety Data Sheets',
                'description' => 'Safety data sheets for all laboratory equipment.',
                'file_name' => 'equipment_safety_sheets.xlsx',
                'original_name' => 'Equipment Safety Data Sheets.xlsx',
                'file_path' => 'documents/equipment_safety_sheets.xlsx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'file_size' => 512000, // 500KB
                'visibility_scope' => 'all',
                'category' => 'safety',
                'tags' => ['safety', 'equipment', 'laboratory'],
                'access_level' => 'public',
                'uploaded_by' => $stationAdmin->id,
                'is_active' => true,
            ],
            [
                'title' => 'Research Publication Template',
                'description' => 'Standard template for NIMR research publications and reports.',
                'file_name' => 'research_publication_template.docx',
                'original_name' => 'Research Publication Template.docx',
                'file_path' => 'documents/research_publication_template.docx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'file_size' => 256000, // 250KB
                'visibility_scope' => 'all',
                'category' => 'template',
                'tags' => ['template', 'publication', 'research'],
                'access_level' => 'public',
                'uploaded_by' => $staff->id,
                'is_active' => true,
            ],
        ];

        foreach ($documents as $documentData) {
            Document::updateOrCreate(
                ['title' => $documentData['title']],
                $documentData
            );
        }

        // Create Events
        $events = [
            [
                'title' => 'Annual NIMR Research Conference',
                'description' => 'Join us for our annual research conference showcasing the latest findings and innovations from across all NIMR centres.',
                'location' => 'NIMR Headquarters Conference Hall',
                'start_datetime' => now()->addDays(30)->setTime(9, 0, 0),
                'end_datetime' => now()->addDays(30)->setTime(17, 0, 0),
                'max_attendees' => 200,
                'requires_rsvp' => true,
                'rsvp_deadline' => now()->addDays(25),
                'visibility_scope' => 'all',
                'created_by' => $admin->id,
                'status' => 'published',
            ],
            [
                'title' => 'Centre Staff Training Workshop',
                'description' => 'Hands-on training workshop for new research methodologies and equipment.',
                'location' => $centre->name . ' Training Room',
                'start_datetime' => now()->addDays(10)->setTime(14, 0, 0),
                'end_datetime' => now()->addDays(10)->setTime(16, 0, 0),
                'max_attendees' => 25,
                'requires_rsvp' => true,
                'rsvp_deadline' => now()->addDays(7),
                'visibility_scope' => 'centres',
                'target_centres' => [$centre->id],
                'created_by' => $centreAdmin->id,
                'status' => 'published',
            ],
            [
                'title' => 'Monthly Safety Briefing',
                'description' => 'Monthly safety briefing covering recent incidents and updated protocols.',
                'location' => 'Main Conference Room',
                'start_datetime' => now()->addDays(5)->setTime(10, 0, 0),
                'end_datetime' => now()->addDays(5)->setTime(11, 0, 0),
                'max_attendees' => 50,
                'requires_rsvp' => false,
                'visibility_scope' => 'all',
                'created_by' => $admin->id,
                'status' => 'published',
            ],
        ];

        foreach ($events as $eventData) {
            Event::updateOrCreate(
                ['title' => $eventData['title']],
                $eventData
            );
        }

        // Create Training Videos
        $trainingModules = [
            [
                'title' => 'Station Orientation: Working with Community Clinics',
                'description' => 'Key procedures for station staff working with nearby community clinics, including reporting lines and contact protocols.',
                'category' => 'orientation',
                'delivery_mode' => 'video',
                'duration_minutes' => 25,
                'is_active' => true,
                'target_audience' => 'station',
                'resource_link' => 'https://example.com/station-orientation',
                'uploaded_by' => $stationAdmin->id,
            ],
            [
                'title' => 'Centre Safety Refresher',
                'description' => 'Quarterly safety briefing covering PPE, incident reporting, and emergency contacts for centre teams.',
                'category' => 'safety',
                'delivery_mode' => 'document',
                'duration_minutes' => 15,
                'is_active' => true,
                'target_audience' => 'centre',
                'resource_link' => 'https://example.com/centre-safety',
                'uploaded_by' => $centreAdmin->id,
            ],
            [
                'title' => 'HQ Policy Update: Data Governance 2025',
                'description' => 'New data governance requirements for headquarters teams, including retention, archiving, and access controls.',
                'category' => 'policy',
                'delivery_mode' => 'presentation',
                'duration_minutes' => 30,
                'is_active' => true,
                'target_audience' => 'hq',
                'resource_link' => 'https://example.com/data-governance',
                'uploaded_by' => $admin->id,
            ],
        ];

        foreach ($trainingModules as $moduleData) {
            TrainingModule::updateOrCreate(
                ['title' => $moduleData['title']],
                $moduleData
            );
        }

        // Create System Links
        $links = [
            [
                'title' => 'Announce to Station',
                'description' => 'Quick link to create a station announcement.',
                'url' => route('announcements.create'),
                'category' => 'communication',
                'icon' => 'plus',
                'access_level' => 'stations',
                'show_on_dashboard' => true,
                'is_active' => true,
                'added_by' => $stationAdmin->id,
            ],
            [
                'title' => 'Station Staff Directory',
                'description' => 'Manage your station staff contacts.',
                'url' => route('admin.station.users.index'),
                'category' => 'management',
                'icon' => 'users',
                'access_level' => 'stations',
                'show_on_dashboard' => true,
                'is_active' => true,
                'added_by' => $stationAdmin->id,
            ],
            [
                'title' => 'Centre Policy Index',
                'description' => 'Browse centre-level operating policy documents.',
                'url' => route('admin.policies.index'),
                'category' => 'policy',
                'icon' => 'document-text',
                'access_level' => 'centres',
                'show_on_dashboard' => true,
                'is_active' => true,
                'added_by' => $centreAdmin->id,
            ],
            [
                'title' => 'HQ Data Governance Hub',
                'description' => 'Headquarters resource for data governance and compliance updates.',
                'url' => 'https://example.com/hq-governance',
                'category' => 'hq',
                'icon' => 'shield-check',
                'access_level' => 'hq',
                'show_on_dashboard' => true,
                'is_active' => true,
                'added_by' => $admin->id,
            ],
        ];

        foreach ($links as $linkData) {
            SystemLink::updateOrCreate(
                ['title' => $linkData['title']],
                $linkData
            );
        }

        // Create Todo Lists
        $todoLists = [
            [
                'title' => 'System Administration Tasks',
                'description' => 'Daily and weekly system administration tasks.',
                'user_id' => $admin->id,
                'is_shared' => false,
                'category' => 'admin',
                'priority' => 'high',
            ],
            [
                'title' => 'Centre Coordination Tasks',
                'description' => 'Tasks for centre administration and coordination.',
                'user_id' => $centreAdmin->id,
                'is_shared' => false,
                'category' => 'management',
                'priority' => 'medium',
            ],
        ];

        foreach ($todoLists as $todoData) {
            TodoList::updateOrCreate(
                ['title' => $todoData['title'], 'user_id' => $todoData['user_id']],
                $todoData
            );
        }

        // Create Feedback entries
        $feedbacks = [
            [
                'subject' => 'Excellent Intranet System',
                'message' => 'The new intranet system is very user-friendly and has improved our communication significantly.',
                'type' => 'compliment',
                'category' => 'system',
                'priority' => 'medium',
                'submitted_by' => $staff->id,
                'status' => 'resolved',
                'is_anonymous' => false,
            ],
            [
                'subject' => 'Suggestion for Document Search',
                'message' => 'It would be helpful to have advanced search filters for documents by date range and file type.',
                'type' => 'suggestion',
                'category' => 'system',
                'priority' => 'medium',
                'submitted_by' => $centreAdmin->id,
                'status' => 'pending',
                'is_anonymous' => false,
            ],
            [
                'subject' => 'Mobile App Request',
                'message' => 'Please consider developing a mobile app for easier access to announcements and documents while in the field.',
                'type' => 'feature_request',
                'category' => 'system',
                'priority' => 'high',
                'submitted_by' => null,
                'status' => 'under_review',
                'is_anonymous' => true,
            ],
        ];

        foreach ($feedbacks as $feedbackData) {
            Feedback::updateOrCreate(
                ['subject' => $feedbackData['subject']],
                $feedbackData
            );
        }

        // Create Password Vault entries (for admins)
        $passwords = [
            [
                'title' => 'Research Database Admin',
                'username' => 'db_admin',
                'encrypted_password' => 'encrypted_password_123',
                'website_url' => 'https://research-db.nimr.or.tz',
                'notes' => 'Main research database administrator account',
                'category' => 'database',
                'user_id' => $admin->id,
            ],
            [
                'title' => 'Equipment Booking System',
                'username' => 'booking_admin',
                'encrypted_password' => 'encrypted_booking_pass',
                'website_url' => 'https://booking.nimr.or.tz',
                'notes' => 'Equipment booking system admin access',
                'category' => 'system',
                'user_id' => $centreAdmin->id,
            ],
        ];

        foreach ($passwords as $passwordData) {
            PasswordVault::updateOrCreate(
                ['title' => $passwordData['title'], 'user_id' => $passwordData['user_id']],
                $passwordData
            );
        }

        $this->command->info('Comprehensive sample data created successfully!');
        $this->command->info('Test accounts created:');
        $this->command->info('- super.admin@nimr.or.tz (Super Admin) - password: password');
        $this->command->info('- ' . optional($centreAdmin)->email . ' (Centre Admin) - password: password');
        $this->command->info('- ' . optional($stationAdmin)->email . ' (Station Admin) - password: password');
        $this->command->info('- ' . optional($staff)->email . ' (Staff) - password: password');
    }
}
