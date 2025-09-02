<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Centre;
use App\Models\Station;
use App\Models\Headquarters;
use Illuminate\Support\Facades\Hash;

class RoleAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $headquarters = Headquarters::first();

        // Create Super Admin (System Administrator)
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@nimr.or.tz',
            'password' => Hash::make('password'),
            'headquarters_id' => $headquarters->id,
            'centre_id' => null,
            'station_id' => null,
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // Create HQ Admin
        User::create([
            'name' => 'HQ Administrator',
            'email' => 'hq.admin@nimr.or.tz',
            'password' => Hash::make('password'),
            'headquarters_id' => $headquarters->id,
            'centre_id' => null,
            'station_id' => null,
            'role' => 'hq_admin',
            'email_verified_at' => now(),
        ]);

        // Create Centre Admins for each centre
        $centres = Centre::all();
        $centreAdminEmails = [
            'Amani Research Centre' => 'amani.admin@nimr.or.tz',
            'Dodoma Research Centre' => 'dodoma.admin@nimr.or.tz',
            'Mabibo Traditional Medicine Centre' => 'mabibo.admin@nimr.or.tz',
            'Mbeya Research Centre' => 'mbeya.admin@nimr.or.tz',
            'Muhimbili Research Centre' => 'muhimbili.admin@nimr.or.tz',
            'Mwanza Research Centre' => 'mwanza.admin@nimr.or.tz',
            'Tanga Research Centre' => 'tanga.admin@nimr.or.tz',
        ];

        foreach ($centres as $centre) {
            $email = $centreAdminEmails[$centre->name] ?? strtolower(str_replace(' ', '.', $centre->name)) . '.admin@nimr.or.tz';

            User::create([
                'name' => $centre->name . ' Administrator',
                'email' => $email,
                'password' => Hash::make('password'),
                'headquarters_id' => $headquarters->id,
                'centre_id' => $centre->id,
                'station_id' => null,
                'role' => 'centre_admin',
                'email_verified_at' => now(),
            ]);
        }

        // Create Station Admins for each station
        $stations = Station::with('centre')->get();
        $stationAdminEmails = [
            'Amani Hill Station' => 'amani.hill.admin@nimr.or.tz',
            'Gonja Station' => 'gonja.admin@nimr.or.tz',
            'Ngongongare Research Station' => 'ngongongare.admin@nimr.or.tz',
            'Tukuyu Research Station' => 'tukuyu.admin@nimr.or.tz',
            'Korogwe Station' => 'korogwe.admin@nimr.or.tz',
            'Tabora Research Station' => 'tabora.admin@nimr.or.tz',
        ];

        foreach ($stations as $station) {
            $email = $stationAdminEmails[$station->name] ?? strtolower(str_replace(' ', '.', $station->name)) . '.admin@nimr.or.tz';

            User::create([
                'name' => $station->name . ' Administrator',
                'email' => $email,
                'password' => Hash::make('password'),
                'headquarters_id' => $headquarters->id,
                'centre_id' => $station->centre_id,
                'station_id' => $station->id,
                'role' => 'station_admin',
                'email_verified_at' => now(),
            ]);
        }

        // Create some regular staff members for testing
        $sampleStaff = [
            [
                'name' => 'Dr. John Mwanga',
                'email' => 'john.mwanga@nimr.or.tz',
                'centre' => 'Muhimbili Research Centre',
                'station' => null,
            ],
            [
                'name' => 'Dr. Mary Kilimo',
                'email' => 'mary.kilimo@nimr.or.tz',
                'centre' => 'Amani Research Centre',
                'station' => null,
            ],
            [
                'name' => 'Dr. Peter Ngoma',
                'email' => 'peter.ngoma@nimr.or.tz',
                'centre' => 'Amani Research Centre',
                'station' => 'Amani Hill Station',
            ],
            [
                'name' => 'Sarah Moshi',
                'email' => 'sarah.moshi@nimr.or.tz',
                'centre' => 'Mwanza Research Centre',
                'station' => null,
            ],
        ];

        foreach ($sampleStaff as $staff) {
            $centre = Centre::where('name', $staff['centre'])->first();
            $station = $staff['station'] ? Station::where('name', $staff['station'])->first() : null;

            User::create([
                'name' => $staff['name'],
                'email' => $staff['email'],
                'password' => Hash::make('password'),
                'headquarters_id' => $headquarters->id,
                'centre_id' => $centre?->id,
                'station_id' => $station?->id,
                'role' => 'staff',
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('✅ Created role assignments:');
        $this->command->info('   - 1 Super Admin');
        $this->command->info('   - 1 HQ Admin');
        $this->command->info('   - ' . $centres->count() . ' Centre Admins');
        $this->command->info('   - ' . $stations->count() . ' Station Admins');
        $this->command->info('   - ' . count($sampleStaff) . ' Regular Staff');
        $this->command->info('');
        $this->command->info('🔑 Default password for all users: "password"');
        $this->command->info('📧 All users are email verified');
    }
}
