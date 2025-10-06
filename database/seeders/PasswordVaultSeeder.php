<?php

namespace Database\Seeders;

use App\Models\PasswordVault;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PasswordVaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use seeded staff account
        $user = User::where('email', 'staff@nimr.or.tz')->first();

        if (!$user) {
            $this->command->info('Staff seed user not found. Skipping password vault seeding.');
            return;
        }

        $faker = Faker::create();

        $passwordEntries = [
            // Work-related passwords
            [
                'title' => 'NIMR Email Account',
                'website_url' => 'https://mail.nimr.or.tz',
                'username' => $user->email,
                'password' => 'NimrEmail2024!',
                'notes' => 'Primary work email account for NIMR communications',
                'category' => 'work',
                'folder' => 'NIMR Systems',
                'is_favorite' => true,
                'icon' => 'envelope',
            ],
            [
                'title' => 'NIMR Intranet Portal',
                'website_url' => 'https://intranet.nimr.or.tz',
                'username' => $user->email,
                'password' => 'IntranetSecure123',
                'notes' => 'Internal portal for accessing NIMR resources and documents',
                'category' => 'work',
                'folder' => 'NIMR Systems',
                'is_favorite' => true,
                'icon' => 'globe',
            ],
            [
                'title' => 'Research Database Access',
                'website_url' => 'https://research.nimr.or.tz',
                'username' => 'researcher_' . $user->id,
                'password' => 'ResearchDB2024#',
                'notes' => 'Access to NIMR research database and publications',
                'category' => 'work',
                'folder' => 'NIMR Systems',
                'is_favorite' => false,
                'icon' => 'database',
            ],
            [
                'title' => 'Laboratory Management System',
                'website_url' => 'https://lab.nimr.or.tz',
                'username' => 'lab_user_' . $user->id,
                'password' => 'LabSystem2024$',
                'notes' => 'Laboratory equipment booking and sample tracking',
                'category' => 'work',
                'folder' => 'NIMR Systems',
                'is_favorite' => false,
                'icon' => 'beaker',
            ],

            // Personal accounts
            [
                'title' => 'Gmail Personal',
                'website_url' => 'https://gmail.com',
                'username' => 'staff.' . $user->id . '@gmail.com',
                'password' => 'PersonalGmail2024!',
                'notes' => 'Personal email account for private communications',
                'category' => 'personal',
                'folder' => 'Personal',
                'is_favorite' => false,
                'icon' => 'envelope',
            ],
            [
                'title' => 'Microsoft OneDrive',
                'website_url' => 'https://onedrive.live.com',
                'username' => strtolower($user->first_name) . '.' . strtolower($user->last_name) . '@outlook.com',
                'password' => 'OneDrive2024Secure',
                'notes' => 'Cloud storage for personal documents and photos',
                'category' => 'personal',
                'folder' => 'Cloud Storage',
                'is_favorite' => false,
                'icon' => 'cloud',
            ],

            // Social Media
            [
                'title' => 'LinkedIn Professional',
                'website_url' => 'https://linkedin.com',
                'username' => strtolower($user->first_name) . '.' . strtolower($user->last_name),
                'password' => 'LinkedIn2024Pro!',
                'notes' => 'Professional networking and career development',
                'category' => 'social',
                'folder' => 'Social Networks',
                'is_favorite' => true,
                'icon' => 'linkedin',
            ],
            [
                'title' => 'WhatsApp Web',
                'website_url' => 'https://web.whatsapp.com',
                'username' => '+255' . $faker->numerify('#########'),
                'password' => 'WhatsApp2024#',
                'notes' => 'WhatsApp web access for messaging',
                'category' => 'social',
                'folder' => 'Social Networks',
                'is_favorite' => false,
                'icon' => 'message-circle',
            ],

            // Banking
            [
                'title' => 'CRDB Bank Online',
                'website_url' => 'https://online.crdbbank.co.tz',
                'username' => 'CRDB' . $faker->numerify('####'),
                'password' => 'CRDBSecure2024$',
                'notes' => 'Primary bank account for salary and savings',
                'category' => 'banking',
                'folder' => 'Financial',
                'is_favorite' => true,
                'icon' => 'credit-card',
                'requires_2fa' => true,
            ],
            [
                'title' => 'NMB Bank Mobile',
                'website_url' => 'https://www.nmbbank.co.tz',
                'username' => 'NMB' . $faker->numerify('######'),
                'password' => 'NMBMobile2024!',
                'notes' => 'Secondary bank account for personal expenses',
                'category' => 'banking',
                'folder' => 'Financial',
                'is_favorite' => false,
                'icon' => 'smartphone',
                'requires_2fa' => true,
            ],

            // Shopping
            [
                'title' => 'Jumia Tanzania',
                'website_url' => 'https://jumia.co.tz',
                'username' => strtolower($user->first_name) . $faker->numerify('###'),
                'password' => 'Jumia2024Shop!',
                'notes' => 'Online shopping for electronics and household items',
                'category' => 'shopping',
                'folder' => 'E-commerce',
                'is_favorite' => false,
                'icon' => 'shopping-cart',
            ],

            // Entertainment
            [
                'title' => 'Netflix Subscription',
                'website_url' => 'https://netflix.com',
                'username' => strtolower($user->first_name) . '.netflix@gmail.com',
                'password' => 'Netflix2024Stream',
                'notes' => 'Family Netflix account for movies and series',
                'category' => 'entertainment',
                'folder' => 'Streaming',
                'is_favorite' => true,
                'icon' => 'tv',
            ],
            [
                'title' => 'Spotify Premium',
                'website_url' => 'https://spotify.com',
                'username' => strtolower($user->first_name) . '.music@gmail.com',
                'password' => 'SpotifyMusic2024',
                'notes' => 'Music streaming service for work and relaxation',
                'category' => 'entertainment',
                'folder' => 'Streaming',
                'is_favorite' => false,
                'icon' => 'music',
            ],

            // Education
            [
                'title' => 'Coursera Learning',
                'website_url' => 'https://coursera.org',
                'username' => $user->email,
                'password' => 'CourseraLearn2024',
                'notes' => 'Online courses for professional development',
                'category' => 'education',
                'folder' => 'Learning',
                'is_favorite' => false,
                'icon' => 'book-open',
            ],

            // Health
            [
                'title' => 'MyHealth Portal',
                'website_url' => 'https://health.gov.tz',
                'username' => 'patient_' . $faker->numerify('######'),
                'password' => 'HealthPortal2024',
                'notes' => 'Personal health records and appointment booking',
                'category' => 'health',
                'folder' => 'Medical',
                'is_favorite' => false,
                'icon' => 'heart',
            ],
        ];

        foreach ($passwordEntries as $entryData) {
            $password = $entryData['password'];
            unset($entryData['password']);

            $passwordVault = new PasswordVault([
                'user_id' => $user->id,
                ...$entryData,
                'login_count' => $faker->numberBetween(0, 25),
                'last_used_at' => $faker->optional(0.7)->dateTimeBetween('-30 days', 'now'),
            ]);

            $passwordVault->setPassword($password);
            $passwordVault->save();
        }

        $this->command->info('Created ' . count($passwordEntries) . ' sample password vault entries successfully!');
    }
}
