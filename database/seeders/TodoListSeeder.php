<?php

namespace Database\Seeders;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TodoListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use seeded staff account
        $user = User::where('role', 'staff')->whereNotNull('centre_id')->first();

        if (!$user) {
            $this->command->info('No centre staff user found. Skipping todo list seeding.');
            return;
        }

        $faker = Faker::create();

        $todoEntries = [
            // Research-related tasks
            [
                'title' => 'Complete Malaria Research Proposal',
                'description' => 'Finalize the research proposal for the malaria vector control study in coastal Tanzania. Include methodology, budget, and timeline sections.',
                'status' => 'in_progress',
                'priority' => 'high',
                'project' => 'Malaria Vector Control Study',
                'category' => 'research',
                'due_date' => Carbon::now()->addDays(5),
                'estimated_hours' => 8,
                'actual_hours' => 4,
                'progress_percentage' => 50,
                'tags' => ['research', 'proposal', 'malaria', 'deadline'],
                'color' => '#EF4444',
            ],
            [
                'title' => 'Review Literature on Mosquito Resistance',
                'description' => 'Conduct comprehensive literature review on insecticide resistance patterns in Anopheles mosquitoes across East Africa.',
                'status' => 'todo',
                'priority' => 'medium',
                'project' => 'Malaria Vector Control Study',
                'category' => 'research',
                'due_date' => Carbon::now()->addDays(10),
                'estimated_hours' => 12,
                'tags' => ['literature', 'resistance', 'mosquito'],
                'color' => '#3B82F6',
            ],
            [
                'title' => 'Prepare Field Equipment List',
                'description' => 'Create detailed inventory of all field equipment needed for vector collection and testing including traps, reagents, and storage materials.',
                'status' => 'todo',
                'priority' => 'medium',
                'project' => 'Malaria Vector Control Study',
                'category' => 'fieldwork',
                'due_date' => Carbon::now()->addDays(7),
                'estimated_hours' => 3,
                'tags' => ['equipment', 'fieldwork', 'inventory'],
                'color' => '#10B981',
            ],

            // Administrative tasks
            [
                'title' => 'Submit Monthly Research Report',
                'description' => 'Compile and submit the monthly progress report to the research director including activities, challenges, and next steps.',
                'status' => 'review',
                'priority' => 'urgent',
                'project' => 'Administrative',
                'category' => 'admin',
                'due_date' => Carbon::now()->addDays(2),
                'estimated_hours' => 4,
                'actual_hours' => 3,
                'progress_percentage' => 90,
                'tags' => ['report', 'monthly', 'administration'],
                'color' => '#DC2626',
            ],
            [
                'title' => 'Update Staff Training Records',
                'description' => 'Review and update training certificates for all laboratory staff. Ensure compliance with safety protocols and certification requirements.',
                'status' => 'todo',
                'priority' => 'low',
                'project' => 'Administrative',
                'category' => 'admin',
                'due_date' => Carbon::now()->addDays(15),
                'estimated_hours' => 2,
                'tags' => ['training', 'compliance', 'staff'],
                'color' => '#6B7280',
            ],

            // Laboratory work
            [
                'title' => 'Calibrate PCR Machines',
                'description' => 'Perform routine calibration and maintenance of all PCR machines in the molecular lab. Document calibration results and any issues found.',
                'status' => 'done',
                'priority' => 'high',
                'project' => 'Laboratory Management',
                'category' => 'lab',
                'due_date' => Carbon::now()->subDays(2),
                'completed_at' => Carbon::now()->subDays(1),
                'estimated_hours' => 4,
                'actual_hours' => 4.5,
                'progress_percentage' => 100,
                'tags' => ['calibration', 'pcr', 'maintenance'],
                'color' => '#059669',
            ],
            [
                'title' => 'Order Laboratory Reagents',
                'description' => 'Submit purchase order for molecular biology reagents including DNA extraction kits, PCR reagents, and gel electrophoresis supplies.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'project' => 'Laboratory Management',
                'category' => 'lab',
                'due_date' => Carbon::now()->addDays(3),
                'estimated_hours' => 2,
                'actual_hours' => 1,
                'progress_percentage' => 60,
                'tags' => ['reagents', 'ordering', 'supplies'],
                'color' => '#7C3AED',
            ],
            [
                'title' => 'Analyze Mosquito DNA Samples',
                'description' => 'Process 50 mosquito samples for species identification and insecticide resistance marker analysis using established protocols.',
                'status' => 'todo',
                'priority' => 'high',
                'project' => 'Malaria Vector Control Study',
                'category' => 'lab',
                'due_date' => Carbon::now()->addDays(8),
                'estimated_hours' => 16,
                'tags' => ['dna', 'analysis', 'mosquito', 'resistance'],
                'color' => '#F59E0B',
            ],

            // Meetings and collaborations
            [
                'title' => 'Attend WHO Vector Control Meeting',
                'description' => 'Participate in the regional WHO meeting on vector control strategies. Present preliminary findings from current research.',
                'status' => 'todo',
                'priority' => 'high',
                'project' => 'Collaborations',
                'category' => 'meeting',
                'due_date' => Carbon::now()->addDays(12),
                'estimated_hours' => 8,
                'tags' => ['WHO', 'meeting', 'presentation', 'collaboration'],
                'color' => '#EC4899',
            ],
            [
                'title' => 'Review PhD Student Progress',
                'description' => 'Meet with PhD student to review research progress, discuss challenges, and plan next phase of the thesis project.',
                'status' => 'todo',
                'priority' => 'medium',
                'project' => 'Student Supervision',
                'category' => 'supervision',
                'due_date' => Carbon::now()->addDays(4),
                'estimated_hours' => 2,
                'tags' => ['supervision', 'phd', 'student', 'progress'],
                'color' => '#8B5CF6',
            ],

            // Training and development
            [
                'title' => 'Complete Biosafety Training Module',
                'description' => 'Finish the online biosafety training module on handling infectious disease samples and update certification records.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'project' => 'Professional Development',
                'category' => 'training',
                'due_date' => Carbon::now()->addDays(6),
                'estimated_hours' => 3,
                'actual_hours' => 1,
                'progress_percentage' => 30,
                'tags' => ['biosafety', 'training', 'certification'],
                'color' => '#14B8A6',
            ],
            [
                'title' => 'Prepare Workshop Presentation on Vector Biology',
                'description' => 'Create presentation slides for the upcoming workshop on vector biology and disease transmission for field staff training.',
                'status' => 'todo',
                'priority' => 'medium',
                'project' => 'Training Delivery',
                'category' => 'training',
                'due_date' => Carbon::now()->addDays(14),
                'estimated_hours' => 6,
                'tags' => ['presentation', 'workshop', 'vector-biology', 'training'],
                'color' => '#F97316',
            ],

            // Publication and writing
            [
                'title' => 'Draft Manuscript on Resistance Patterns',
                'description' => 'Write first draft of manuscript on insecticide resistance patterns observed in field collections. Include data analysis and discussion.',
                'status' => 'todo',
                'priority' => 'high',
                'project' => 'Publications',
                'category' => 'writing',
                'due_date' => Carbon::now()->addDays(20),
                'estimated_hours' => 24,
                'tags' => ['manuscript', 'publication', 'resistance', 'writing'],
                'color' => '#84CC16',
            ],

            // Personal development
            [
                'title' => 'Update Professional LinkedIn Profile',
                'description' => 'Update LinkedIn profile with recent publications, conference presentations, and current research interests.',
                'status' => 'todo',
                'priority' => 'low',
                'project' => 'Personal Branding',
                'category' => 'personal',
                'due_date' => Carbon::now()->addDays(30),
                'estimated_hours' => 1,
                'tags' => ['linkedin', 'profile', 'networking'],
                'color' => '#6366F1',
            ],

            // Equipment and infrastructure
            [
                'title' => 'Inspect Field Station Equipment',
                'description' => 'Conduct monthly inspection of field station equipment including generators, freezers, and sampling equipment. Document any maintenance needs.',
                'status' => 'todo',
                'priority' => 'medium',
                'project' => 'Infrastructure',
                'category' => 'maintenance',
                'due_date' => Carbon::now()->addDays(9),
                'estimated_hours' => 4,
                'tags' => ['inspection', 'equipment', 'field-station', 'maintenance'],
                'color' => '#78716C',
            ],
        ];

        foreach ($todoEntries as $entryData) {
            $todo = new TodoList([
                'user_id' => $user->id,
                'title' => $entryData['title'],
                'description' => $entryData['description'],
                'status' => $entryData['status'],
                'priority' => $entryData['priority'],
                'project' => $entryData['project'],
                'category' => $entryData['category'] ?? 'general',
                'due_date' => $entryData['due_date'],
                'estimated_hours' => $entryData['estimated_hours'] ?? null,
                'actual_hours' => $entryData['actual_hours'] ?? null,
                'progress_percentage' => $entryData['progress_percentage'] ?? 0,
                'tags' => $entryData['tags'] ?? null,
                'color' => $entryData['color'] ?? '#6B7280',
                'is_completed' => $entryData['status'] === 'done',
                'completed_at' => $entryData['completed_at'] ?? null,
                'sort_order' => $faker->numberBetween(1, 100),
                'view_count' => $faker->numberBetween(0, 15),
                'last_activity_at' => $faker->optional(0.8)->dateTimeBetween('-7 days', 'now'),
            ]);

            $todo->save();
        }

        $this->command->info('Created ' . count($todoEntries) . ' sample todo entries for ' . $user->email . ' successfully!');
    }
}
