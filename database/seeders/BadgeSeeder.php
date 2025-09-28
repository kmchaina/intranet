<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'code' => 'first_login',
                'name' => 'First Login',
                'category' => 'milestone',
                'metric' => 'auth.login',
                'threshold' => 1,
                'description' => 'Logged in for the first time',
                'repeatable' => false,
            ],
            [
                'code' => 'reader_lvl1',
                'name' => 'Reader I',
                'category' => 'engagement',
                'metric' => 'announcement.read',
                'threshold' => 10,
                'description' => 'Read 10 announcements',
                'repeatable' => false,
            ],
            [
                'code' => 'reader_lvl2',
                'name' => 'Reader II',
                'category' => 'engagement',
                'metric' => 'announcement.read',
                'threshold' => 50,
                'description' => 'Read 50 announcements',
                'repeatable' => false,
            ],
            [
                'code' => 'doc_consumer',
                'name' => 'Document Explorer',
                'category' => 'engagement',
                'metric' => 'document.view',
                'threshold' => 25,
                'description' => 'Viewed 25 documents',
                'repeatable' => false,
            ],
            [
                'code' => 'poll_participant',
                'name' => 'Poll Participant',
                'category' => 'participation',
                'metric' => 'poll.respond',
                'threshold' => 5,
                'description' => 'Responded to 5 polls',
                'repeatable' => false,
            ],
            [
                'code' => 'vault_user',
                'name' => 'Vault User',
                'category' => 'productivity',
                'metric' => 'vault.access',
                'threshold' => 5,
                'description' => 'Accessed the vault 5 times',
                'repeatable' => false,
            ],
        ];

        foreach ($badges as $data) {
            Badge::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
