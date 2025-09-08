<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Headquarters;
use App\Models\Centre;
use App\Models\Station;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get organizational units for assignment
        $headquarters = Headquarters::first();
        $centres = Centre::all();
        $stations = Station::all();
        
        // Create admin user at headquarters
        User::create([
            'name' => 'Dr. John Mwanga',
            'email' => 'admin@nimr.or.tz',
            'password' => Hash::make('password'),
            'headquarters_id' => $headquarters->id,
            'role' => 'super_admin',
        ]);

        // Create HR manager at headquarters
        User::create([
            'name' => 'Ms. Grace Kilonzo',
            'email' => 'hr@nimr.or.tz',
            'password' => Hash::make('password'),
            'headquarters_id' => $headquarters->id,
            'role' => 'hq_admin',
        ]);

        // Create finance manager at headquarters
        User::create([
            'name' => 'Mr. Peter Msigwa',
            'email' => 'finance@nimr.or.tz',
            'password' => Hash::make('password'),
            'headquarters_id' => $headquarters->id,
            'role' => 'hq_admin',
        ]);

        // Create users at Amani Research Centre
        $amaniCentre = $centres->where('name', 'Amani Research Centre')->first();
        if ($amaniCentre) {
            User::create([
                'name' => 'Dr. Sarah Mmbando',
                'email' => 'sarah.mmbando@nimr.or.tz',
                'password' => Hash::make('password'),
                'centre_id' => $amaniCentre->id,
                'role' => 'centre_admin',
            ]);

            User::create([
                'name' => 'Dr. Emmanuel Kaindoa',
                'email' => 'emmanuel.kaindoa@nimr.or.tz',
                'password' => Hash::make('password'),
                'centre_id' => $amaniCentre->id,
                'role' => 'staff',
            ]);
        }

        // Create users at Mwanza Research Centre
        $mwanzaCentre = $centres->where('name', 'Mwanza Research Centre')->first();
        if ($mwanzaCentre) {
            User::create([
                'name' => 'Dr. Reginald Kavishe',
                'email' => 'reginald.kavishe@nimr.or.tz',
                'password' => Hash::make('password'),
                'centre_id' => $mwanzaCentre->id,
                'role' => 'centre_admin',
            ]);
        }

        // Create users at Dodoma Research Centre
        $dodomaCentre = $centres->where('name', 'Dodoma Research Centre')->first();
        if ($dodomaCentre) {
            User::create([
                'name' => 'Dr. Mercy Chiduo',
                'email' => 'mercy.chiduo@nimr.or.tz',
                'password' => Hash::make('password'),
                'centre_id' => $dodomaCentre->id,
                'role' => 'staff',
            ]);
        }

        // Create users at stations
        $amaniHillStation = $stations->where('name', 'Amani Hill Station')->first();
        if ($amaniHillStation) {
            User::create([
                'name' => 'Mr. Frank Sadelwa',
                'email' => 'frank.sadelwa@nimr.or.tz',
                'password' => Hash::make('password'),
                'station_id' => $amaniHillStation->id,
                'role' => 'station_admin',
            ]);
        }

        $ngongongareStation = $stations->where('name', 'Ngongongare Research Station')->first();
        if ($ngongongareStation) {
            User::create([
                'name' => 'Dr. Agnes Mwangoka',
                'email' => 'agnes.mwangoka@nimr.or.tz',
                'password' => Hash::make('password'),
                'station_id' => $ngongongareStation->id,
                'role' => 'staff',
            ]);
        }

        $korogweStation = $stations->where('name', 'Korogwe Station')->first();
        if ($korogweStation) {
            User::create([
                'name' => 'Ms. Beatrice Msonde',
                'email' => 'beatrice.msonde@nimr.or.tz',
                'password' => Hash::make('password'),
                'station_id' => $korogweStation->id,
                'role' => 'staff',
            ]);
        }

        // Create some inactive users for testing
        User::create([
            'name' => 'Dr. Michael Temba',
            'email' => 'michael.temba@nimr.or.tz',
            'password' => Hash::make('password'),
            'headquarters_id' => $headquarters->id,
            'role' => 'staff',
        ]);

        // Create test user for login
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'headquarters_id' => $headquarters->id,
            'role' => 'staff',
        ]);
    }
}
