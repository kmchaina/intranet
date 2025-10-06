<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            HierarchySeeder::class,
            UserSeeder::class,
            SystemLinkSeeder::class,
            EventSeeder::class,
            NewsSeeder::class,
            TrainingVideoSeeder::class,
            TodoListSeeder::class,
            BadgeSeeder::class,
            ComprehensiveDataSeeder::class,
        ]);
    }
}
