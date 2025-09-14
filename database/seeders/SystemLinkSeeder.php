<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemLink;
use App\Models\User;
use Faker\Factory as Faker;

class SystemLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get admin users to assign as creators
        $users = User::take(3)->get();

        if ($users->count() === 0) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $systemLinks = [
            // Quick Access - Most Used Internal Systems
            [
                'title' => 'NIMR Staff Portal',
                'description' => 'Access your employee dashboard, leave requests, and personal information',
                'url' => 'https://staff.nimr.or.tz',
                'icon' => '👤',
                'category' => 'quick_access',
                'color_scheme' => 'blue',
                'access_level' => 'all',
                'is_featured' => true,
                'show_on_dashboard' => true,
                'opens_new_tab' => true,
            ],
            [
                'title' => 'Email System',
                'description' => 'Access NIMR webmail and email management',
                'url' => 'https://mail.nimr.or.tz',
                'icon' => '📧',
                'category' => 'communication',
                'color_scheme' => 'green',
                'access_level' => 'all',
                'is_featured' => true,
                'show_on_dashboard' => true,
                'opens_new_tab' => true,
            ],
            [
                'title' => 'Research Management System',
                'description' => 'Manage research projects, proposals, and documentation',
                'url' => 'https://research.nimr.or.tz',
                'icon' => '🔬',
                'category' => 'research',
                'color_scheme' => 'purple',
                'access_level' => 'centre',
                'is_featured' => true,
                'show_on_dashboard' => true,
                'opens_new_tab' => true,
                'requires_vpn' => true,
            ],

            // HR Systems
            [
                'title' => 'HR Information System',
                'description' => 'Human resources management, payroll, and employee records',
                'url' => 'https://hr.nimr.or.tz',
                'icon' => '👥',
                'category' => 'hr',
                'color_scheme' => 'blue',
                'access_level' => 'hq',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
                'requires_vpn' => true,
            ],
            [
                'title' => 'Leave Management',
                'description' => 'Submit and track leave applications and approvals',
                'url' => 'https://leave.nimr.or.tz',
                'icon' => '🏖️',
                'category' => 'hr',
                'color_scheme' => 'yellow',
                'access_level' => 'all',
                'is_featured' => false,
                'show_on_dashboard' => true,
                'opens_new_tab' => true,
            ],
            [
                'title' => 'Training Portal',
                'description' => 'Access training materials, courses, and certification tracking',
                'url' => 'https://training.nimr.or.tz',
                'icon' => '🎓',
                'category' => 'hr',
                'color_scheme' => 'green',
                'access_level' => 'all',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
            ],

            // Finance Systems
            [
                'title' => 'Financial Management System',
                'description' => 'Budget tracking, expense management, and financial reporting',
                'url' => 'https://finance.nimr.or.tz',
                'icon' => '💰',
                'category' => 'finance',
                'color_scheme' => 'green',
                'access_level' => 'hq',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
                'requires_vpn' => true,
            ],
            [
                'title' => 'Procurement System',
                'description' => 'Submit procurement requests and track purchasing processes',
                'url' => 'https://procurement.nimr.or.tz',
                'icon' => '🛒',
                'category' => 'finance',
                'color_scheme' => 'blue',
                'access_level' => 'centre',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
                'requires_vpn' => true,
            ],

            // Research Tools
            [
                'title' => 'Research Data Management',
                'description' => 'Secure storage and management of research data and datasets',
                'url' => 'https://rdm.nimr.or.tz',
                'icon' => '📊',
                'category' => 'research',
                'color_scheme' => 'purple',
                'access_level' => 'centre',
                'is_featured' => true,
                'show_on_dashboard' => true,
                'opens_new_tab' => true,
                'requires_vpn' => true,
            ],
            [
                'title' => 'Laboratory Information System',
                'description' => 'Manage lab samples, test results, and laboratory workflows',
                'url' => 'https://lims.nimr.or.tz',
                'icon' => '🧪',
                'category' => 'research',
                'color_scheme' => 'red',
                'access_level' => 'centre',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
                'requires_vpn' => true,
            ],
            [
                'title' => 'REDCap Data Capture',
                'description' => 'Secure web application for building and managing research databases',
                'url' => 'https://redcap.nimr.or.tz',
                'icon' => '📋',
                'category' => 'research',
                'color_scheme' => 'red',
                'access_level' => 'centre',
                'is_featured' => true,
                'show_on_dashboard' => true,
                'opens_new_tab' => true,
                'requires_vpn' => true,
            ],

            // Technical Systems
            [
                'title' => 'IT Service Desk',
                'description' => 'Submit IT support tickets and track technical issues',
                'url' => 'https://helpdesk.nimr.or.tz',
                'icon' => '🔧',
                'category' => 'technical',
                'color_scheme' => 'gray',
                'access_level' => 'all',
                'is_featured' => false,
                'show_on_dashboard' => true,
                'opens_new_tab' => true,
            ],
            [
                'title' => 'VPN Access Portal',
                'description' => 'Download VPN client and manage remote access credentials',
                'url' => 'https://vpn.nimr.or.tz',
                'icon' => '🔒',
                'category' => 'technical',
                'color_scheme' => 'red',
                'access_level' => 'all',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
            ],
            [
                'title' => 'Network Storage',
                'description' => 'Access shared network drives and file repositories',
                'url' => 'https://storage.nimr.or.tz',
                'icon' => '💾',
                'category' => 'technical',
                'color_scheme' => 'blue',
                'access_level' => 'all',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
                'requires_vpn' => true,
            ],

            // External Services
            [
                'title' => 'PubMed Research',
                'description' => 'Search biomedical literature and research publications',
                'url' => 'https://pubmed.ncbi.nlm.nih.gov',
                'icon' => '📚',
                'category' => 'external',
                'color_scheme' => 'blue',
                'access_level' => 'all',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
            ],
            [
                'title' => 'WHO Resources',
                'description' => 'World Health Organization resources and guidelines',
                'url' => 'https://www.who.int',
                'icon' => '🌍',
                'category' => 'external',
                'color_scheme' => 'blue',
                'access_level' => 'all',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
            ],
            [
                'title' => 'Tanzania Health Portal',
                'description' => 'Ministry of Health and national health information',
                'url' => 'https://www.moh.go.tz',
                'icon' => '🇹🇿',
                'category' => 'external',
                'color_scheme' => 'green',
                'access_level' => 'all',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
            ],

            // Communication Tools
            [
                'title' => 'Microsoft Teams',
                'description' => 'Team collaboration, meetings, and instant messaging',
                'url' => 'https://teams.microsoft.com',
                'icon' => '💬',
                'category' => 'communication',
                'color_scheme' => 'purple',
                'access_level' => 'all',
                'is_featured' => true,
                'show_on_dashboard' => true,
                'opens_new_tab' => true,
            ],
            [
                'title' => 'Zoom Meetings',
                'description' => 'Video conferencing and virtual meetings platform',
                'url' => 'https://zoom.us',
                'icon' => '📹',
                'category' => 'communication',
                'color_scheme' => 'blue',
                'access_level' => 'all',
                'is_featured' => false,
                'show_on_dashboard' => false,
                'opens_new_tab' => true,
            ],
        ];

        foreach ($systemLinks as $linkData) {
            $creator = $users->random();
            
            SystemLink::create([
                'title' => $linkData['title'],
                'description' => $linkData['description'],
                'url' => $linkData['url'],
                'icon' => $linkData['icon'],
                'category' => $linkData['category'],
                'color_scheme' => $linkData['color_scheme'],
                'access_level' => $linkData['access_level'],
                'is_featured' => $linkData['is_featured'],
                'show_on_dashboard' => $linkData['show_on_dashboard'],
                'opens_new_tab' => $linkData['opens_new_tab'],
                'requires_vpn' => $linkData['requires_vpn'] ?? false,
                'is_active' => true,
                'click_count' => $faker->numberBetween(10, 500),
                'added_by' => $creator->id,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Created ' . count($systemLinks) . ' system links successfully!');
    }
}
