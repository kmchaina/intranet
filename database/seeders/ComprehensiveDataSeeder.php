<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Centre;
use App\Models\Station;
use App\Models\Department;
use App\Models\Announcement;
use App\Models\Poll;
use App\Models\Document;
use App\Models\Event;
use App\Models\TodoList;
use App\Models\TrainingVideo;
use App\Models\SystemLink;
use App\Models\Feedback;
use App\Models\PasswordVault;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    public function run(): void
    {
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

        // Centre admin
        $centre = Centre::first();
        $centreAdmin = User::updateOrCreate(
            ['email' => 'centre.admin@nimr.or.tz'],
            [
                'name' => 'Centre Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'centre_admin',
                'centre_id' => $centre->id,
                'birth_date' => Carbon::today()->addDays(5),
                'birthday_visibility' => 'public',
                'hire_date' => Carbon::today()->subYears(3),
                'show_work_anniversary' => true,
            ]
        );

        // Station admin
        $station = Station::first();
        $stationAdmin = User::updateOrCreate(
            ['email' => 'station.admin@nimr.or.tz'],
            [
                'name' => 'Station Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'station_admin',
                'centre_id' => $station->centre_id,
                'station_id' => $station->id,
                'birth_date' => Carbon::today(),
                'birthday_visibility' => 'public',
                'hire_date' => Carbon::today()->subYears(2),
                'show_work_anniversary' => true,
            ]
        );

        // Regular staff
        $department = Department::first();
        $staff = User::updateOrCreate(
            ['email' => 'staff@nimr.or.tz'],
            [
                'name' => 'Staff Member',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'staff',
                'centre_id' => $centre->id,
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
        $videos = [
            [
                'title' => 'Laboratory Safety Fundamentals',
                'description' => 'Essential safety procedures and protocols for laboratory work.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration_minutes' => 15,
                'category' => 'safety',
                'target_audience' => 'all',
                'uploaded_by' => $admin->id,
                'is_active' => true,
            ],
            [
                'title' => 'Data Collection Best Practices',
                'description' => 'Learn the best practices for collecting and managing research data.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration_minutes' => 22,
                'category' => 'technical',
                'target_audience' => 'all',
                'uploaded_by' => $centreAdmin->id,
                'is_active' => true,
            ],
            [
                'title' => 'Equipment Maintenance Procedures',
                'description' => 'Step-by-step guide for maintaining laboratory equipment.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration_minutes' => 18,
                'category' => 'technical',
                'target_audience' => 'all',
                'uploaded_by' => $stationAdmin->id,
                'is_active' => true,
            ],
        ];

        foreach ($videos as $videoData) {
            TrainingVideo::updateOrCreate(
                ['title' => $videoData['title']],
                $videoData
            );
        }

        // Create System Links
        $links = [
            [
                'title' => 'NIMR Official Website',
                'description' => 'Official NIMR website with public information and resources.',
                'url' => 'https://www.nimr.or.tz',
                'category' => 'external',
                'icon' => 'fas fa-globe',
                'access_level' => 'all',
                'is_active' => true,
                'added_by' => $admin->id,
            ],
            [
                'title' => 'Research Database Portal',
                'description' => 'Access to internal research databases and publications.',
                'url' => 'https://research.nimr.or.tz',
                'category' => 'research',
                'icon' => 'fas fa-database',
                'access_level' => 'all',
                'is_active' => true,
                'added_by' => $admin->id,
            ],
            [
                'title' => 'Equipment Booking System',
                'description' => 'Book laboratory equipment and meeting rooms.',
                'url' => '/equipment-booking',
                'category' => 'technical',
                'icon' => 'fas fa-calendar-alt',
                'access_level' => 'all',
                'is_active' => true,
                'added_by' => $centreAdmin->id,
            ],
            [
                'title' => 'IT Support Portal',
                'description' => 'Submit IT support requests and access technical resources.',
                'url' => '/it-support',
                'category' => 'technical',
                'icon' => 'fas fa-headset',
                'access_level' => 'all',
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
        $this->command->info('- admin@nimr.or.tz (Super Admin) - password: password');
        $this->command->info('- centre.admin@nimr.or.tz (Centre Admin) - password: password');
        $this->command->info('- station.admin@nimr.or.tz (Station Admin) - password: password');
        $this->command->info('- staff@nimr.or.tz (Staff) - password: password');
    }
}
