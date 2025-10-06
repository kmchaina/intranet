<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Headquarters;
use App\Models\Centre;
use App\Models\Station;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $headquarters = Headquarters::firstOrCreate(
            ['code' => 'NIMR-HQ'],
            [
                'name' => 'National Institute for Medical Research (NIMR)',
                'description' => 'Central headquarters for NIMR hierarchy',
                'location' => 'Dar es Salaam',
                'is_active' => true,
            ]
        );

        $formatIdentifier = function (string $name): string {
            $cleaned = str_ireplace([
                'research',
                'centre',
                'center',
                'station',
                'traditional',
                'medicine',
            ], '', $name);

            return strtolower(preg_replace('/\s+|[-_]+/', '', trim($cleaned))) ?: strtolower(preg_replace('/\s+|[-_]+/', '', $name));
        };

        // Ensure top-level accounts exist
        User::updateOrCreate(
            ['email' => 'super.admin@nimr.or.tz'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'headquarters_id' => $headquarters->id,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'hq.admin@nimr.or.tz'],
            [
                'name' => 'HQ Admin',
                'password' => Hash::make('password'),
                'role' => 'hq_admin',
                'headquarters_id' => $headquarters->id,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'hq.staff@nimr.or.tz'],
            [
                'name' => 'HQ Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'headquarters_id' => $headquarters->id,
                'email_verified_at' => now(),
            ]
        );

        $centres = Centre::all();
        foreach ($centres as $centre) {
            $centreSlug = $formatIdentifier($centre->name);

            User::updateOrCreate(
                ['email' => "{$centreSlug}.admin@nimr.or.tz"],
                [
                    'name' => "Centre Admin {$centre->name}",
                    'password' => Hash::make('password'),
                    'role' => 'centre_admin',
                    'centre_id' => $centre->id,
                    'email_verified_at' => now(),
                ]
            );

            User::updateOrCreate(
                ['email' => "{$centreSlug}.staff@nimr.or.tz"],
                [
                    'name' => "Centre Staff {$centre->name}",
                    'password' => Hash::make('password'),
                    'role' => 'staff',
                    'centre_id' => $centre->id,
                    'email_verified_at' => now(),
                ]
            );
        }

        $stations = Station::with('centre')->get();
        foreach ($stations as $station) {
            $stationSlug = $formatIdentifier($station->name);

            User::updateOrCreate(
                ['email' => "{$stationSlug}.admin@nimr.or.tz"],
                [
                    'name' => "Station Admin {$station->name}",
                    'password' => Hash::make('password'),
                    'role' => 'station_admin',
                    'centre_id' => $station->centre_id,
                    'station_id' => $station->id,
                    'email_verified_at' => now(),
                ]
            );

            User::updateOrCreate(
                ['email' => "{$stationSlug}.staff@nimr.or.tz"],
                [
                    'name' => "Station Staff {$station->name}",
                    'password' => Hash::make('password'),
                    'role' => 'staff',
                    'centre_id' => $station->centre_id,
                    'station_id' => $station->id,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
