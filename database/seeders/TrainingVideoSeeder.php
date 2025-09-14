<?php

namespace Database\Seeders;

use App\Models\TrainingVideo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TrainingVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get some users to be uploaders
        $users = User::take(5)->get();

        if ($users->count() === 0) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $trainingVideos = [
            // Research Methods Videos
            [
                'title' => 'Introduction to Malaria Vector Research Methods',
                'description' => 'Comprehensive overview of modern malaria vector research methodologies, including collection techniques, species identification, and insecticide resistance testing protocols used at NIMR.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Placeholder URL
                'video_type' => 'youtube',
                'category' => 'research',
                'duration_minutes' => 45,
                'target_audience' => 'centre',
                'is_featured' => true,
                'is_active' => true,
                'view_count' => $faker->numberBetween(150, 500),
            ],
            [
                'title' => 'PCR Techniques for Mosquito Species Identification',
                'description' => 'Step-by-step guide to PCR-based identification of Anopheles mosquito species, including DNA extraction, primer selection, and gel electrophoresis interpretation.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'technical',
                'duration_minutes' => 35,
                'target_audience' => 'centre',
                'is_featured' => true,
                'is_active' => true,
                'view_count' => $faker->numberBetween(80, 250),
            ],
            [
                'title' => 'Field Collection Methods for Disease Vectors',
                'description' => 'Practical training on various field collection methods including CDC light traps, human landing catches, and pyrethrum spray catches for vector surveillance.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'research',
                'duration_minutes' => 55,
                'target_audience' => 'station',
                'is_featured' => false,
                'is_active' => true,
                'view_count' => $faker->numberBetween(120, 300),
            ],

            // Laboratory Safety & Techniques
            [
                'title' => 'Biosafety Level 2 Laboratory Protocols',
                'description' => 'Essential biosafety protocols for BSL-2 laboratories, including proper use of biological safety cabinets, waste disposal procedures, and emergency response protocols.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'safety',
                'duration_minutes' => 30,
                'target_audience' => 'all',
                'is_featured' => true,
                'is_active' => true,
                'view_count' => $faker->numberBetween(200, 450),
            ],
            [
                'title' => 'Proper Handling of Infectious Disease Samples',
                'description' => 'Comprehensive training on safe handling, storage, and processing of infectious disease samples including blood, tissue, and pathogen cultures.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'safety',
                'duration_minutes' => 40,
                'target_audience' => 'centre',
                'is_featured' => false,
                'is_active' => true,
                'view_count' => $faker->numberBetween(90, 220),
            ],
            [
                'title' => 'Laboratory Equipment Calibration and Maintenance',
                'description' => 'Regular maintenance procedures for common laboratory equipment including microscopes, centrifuges, spectrophotometers, and PCR machines.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'technical',
                'duration_minutes' => 25,
                'target_audience' => 'centre',
                'is_featured' => false,
                'is_active' => true,
                'view_count' => $faker->numberBetween(60, 180),
            ],

            // Software and Data Analysis
            [
                'title' => 'R Programming for Epidemiological Data Analysis',
                'description' => 'Introduction to R programming for epidemiological research including data visualization, statistical analysis, and mapping of disease patterns.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'software',
                'duration_minutes' => 60,
                'target_audience' => 'centre',
                'is_featured' => true,
                'is_active' => true,
                'view_count' => $faker->numberBetween(100, 280),
            ],
            [
                'title' => 'GIS Mapping for Vector Control Programs',
                'description' => 'Using Geographic Information Systems (GIS) for mapping vector breeding sites, disease transmission patterns, and planning control interventions.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'software',
                'duration_minutes' => 50,
                'target_audience' => 'hq',
                'is_featured' => false,
                'is_active' => true,
                'view_count' => $faker->numberBetween(70, 190),
            ],
            [
                'title' => 'Laboratory Information Management Systems (LIMS)',
                'description' => 'Training on using LIMS for sample tracking, data management, and quality control in research laboratories.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'software',
                'duration_minutes' => 35,
                'target_audience' => 'centre',
                'is_featured' => false,
                'is_active' => true,
                'view_count' => $faker->numberBetween(85, 160),
            ],

            // Administration and HR
            [
                'title' => 'NIMR Research Ethics and Compliance',
                'description' => 'Overview of research ethics principles, IRB procedures, informed consent processes, and compliance requirements for human subjects research.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'hr',
                'duration_minutes' => 40,
                'target_audience' => 'all',
                'is_featured' => true,
                'is_active' => true,
                'view_count' => $faker->numberBetween(180, 350),
            ],
            [
                'title' => 'Grant Writing for Health Research',
                'description' => 'Effective strategies for writing competitive research grant proposals, including budgeting, timeline development, and stakeholder engagement.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'general',
                'duration_minutes' => 55,
                'target_audience' => 'hq',
                'is_featured' => false,
                'is_active' => true,
                'view_count' => $faker->numberBetween(95, 230),
            ],
            [
                'title' => 'Scientific Publication and Peer Review Process',
                'description' => 'Guide to preparing manuscripts for scientific publication, understanding the peer review process, and responding to reviewer comments.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'general',
                'duration_minutes' => 45,
                'target_audience' => 'centre',
                'is_featured' => false,
                'is_active' => true,
                'view_count' => $faker->numberBetween(110, 280),
            ],

            // New Employee Orientation
            [
                'title' => 'Welcome to NIMR: Institutional Overview',
                'description' => 'Comprehensive introduction to the National Institute for Medical Research, its mission, history, organizational structure, and key research programs.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'orientation',
                'duration_minutes' => 30,
                'target_audience' => 'all',
                'is_featured' => true,
                'is_active' => true,
                'view_count' => $faker->numberBetween(250, 500),
            ],
            [
                'title' => 'NIMR Policies and Procedures Handbook',
                'description' => 'Overview of institutional policies including HR procedures, research guidelines, financial regulations, and code of conduct for NIMR staff.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'orientation',
                'duration_minutes' => 25,
                'target_audience' => 'all',
                'is_featured' => false,
                'is_active' => true,
                'view_count' => $faker->numberBetween(180, 320),
            ],

            // Specialized Research Topics
            [
                'title' => 'Community Engagement in Health Research',
                'description' => 'Best practices for engaging communities in health research, including stakeholder mapping, participatory research methods, and ethical considerations.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'research',
                'duration_minutes' => 42,
                'target_audience' => 'station',
                'is_featured' => false,
                'is_active' => true,
                'view_count' => $faker->numberBetween(75, 190),
            ],
            [
                'title' => 'Advanced Microscopy Techniques for Parasitology',
                'description' => 'Advanced microscopy methods for parasite identification including fluorescence microscopy, confocal imaging, and digital image analysis.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'category' => 'technical',
                'duration_minutes' => 38,
                'target_audience' => 'centre',
                'is_featured' => false,
                'is_active' => true,
                'view_count' => $faker->numberBetween(65, 150),
            ],
        ];

        foreach ($trainingVideos as $videoData) {
            TrainingVideo::create([
                'title' => $videoData['title'],
                'description' => $videoData['description'],
                'video_url' => $videoData['video_url'],
                'video_type' => $videoData['video_type'],
                'category' => $videoData['category'],
                'duration_minutes' => $videoData['duration_minutes'],
                'target_audience' => $videoData['target_audience'],
                'is_featured' => $videoData['is_featured'],
                'is_active' => $videoData['is_active'],
                'view_count' => $videoData['view_count'],
                'uploaded_by' => $users->random()->id,
            ]);
        }

        $this->command->info('Created ' . count($trainingVideos) . ' sample training videos successfully!');
    }
}
