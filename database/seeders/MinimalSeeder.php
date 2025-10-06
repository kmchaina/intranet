<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MinimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create one super admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@nimr.or.tz',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Created Super Admin user:');
        $this->command->info('   Email: admin@nimr.or.tz');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('🚀 You can now test the system!');
    }
}
