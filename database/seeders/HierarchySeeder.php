<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Headquarters;
use App\Models\Centre;
use App\Models\Station;
use App\Models\Department;

class HierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create NIMR Headquarters
        $headquarters = Headquarters::create([
            'name' => 'National Institute for Medical Research (NIMR)',
            'code' => 'NIMR-HQ',
            'description' => 'Central administrative body overseeing all centres, stations, and institute-wide operations',
            'location' => 'Dar es Salaam, Tanzania',
            'is_active' => true,
        ]);

        // Create Research Centres under Headquarters
        $amani = Centre::create([
            'name' => 'Amani Research Centre',
            'code' => 'ARC',
            'description' => 'Main research centre in Amani focusing on vector control and malaria research',
            'location' => 'Muheza, Tanga',
            'headquarters_id' => $headquarters->id,
            'is_active' => true,
        ]);

        $dodoma = Centre::create([
            'name' => 'Dodoma Research Centre',
            'code' => 'DRC',
            'description' => 'Research centre in Dodoma focusing on public health research',
            'location' => 'Dodoma',
            'headquarters_id' => $headquarters->id,
            'is_active' => true,
        ]);

        $mabibo = Centre::create([
            'name' => 'Mabibo Traditional Medicine Centre',
            'code' => 'MTMC',
            'description' => 'Traditional medicine research centre',
            'location' => 'Dar es Salaam',
            'headquarters_id' => $headquarters->id,
            'is_active' => true,
        ]);

        $mbeya = Centre::create([
            'name' => 'Mbeya Research Centre',
            'code' => 'MRC',
            'description' => 'Medical research centre in Mbeya',
            'location' => 'Mbeya',
            'headquarters_id' => $headquarters->id,
            'is_active' => true,
        ]);

        $muhimbili = Centre::create([
            'name' => 'Muhimbili Research Centre',
            'code' => 'MURC',
            'description' => 'Research centre affiliated with Muhimbili University',
            'location' => 'Dar es Salaam',
            'headquarters_id' => $headquarters->id,
            'is_active' => true,
        ]);

        $mwanza = Centre::create([
            'name' => 'Mwanza Research Centre',
            'code' => 'MWC',
            'description' => 'Research centre in Mwanza focusing on regional health issues',
            'location' => 'Mwanza',
            'headquarters_id' => $headquarters->id,
            'is_active' => true,
        ]);

        $tanga = Centre::create([
            'name' => 'Tanga Research Centre',
            'code' => 'TRC',
            'description' => 'Research centre in Tanga region',
            'location' => 'Tanga',
            'headquarters_id' => $headquarters->id,
            'is_active' => true,
        ]);

        // Create Research Stations under their respective Centres

        // Amani Research Centre Stations
        $amaniHill = Station::create([
            'name' => 'Amani Hill Station',
            'code' => 'AHS',
            'description' => 'Hill station for field research and vector control',
            'location' => 'Amani Hills, Muheza',
            'centre_id' => $amani->id,
            'is_active' => true,
        ]);

        $gonja = Station::create([
            'name' => 'Gonja Station',
            'code' => 'GS',
            'description' => 'Field research station in Gonja',
            'location' => 'Gonja, Tanga',
            'centre_id' => $amani->id,
            'is_active' => true,
        ]);

        // Mabibo Traditional Medicine Centre Station
        $ngongongare = Station::create([
            'name' => 'Ngongongare Research Station',
            'code' => 'NRS',
            'description' => 'Research station focusing on traditional medicine',
            'location' => 'Ngongongare, Dodoma',
            'centre_id' => $mabibo->id,
            'is_active' => true,
        ]);

        // Mbeya Research Centre Station
        $tukuyu = Station::create([
            'name' => 'Tukuyu Research Station',
            'code' => 'TRS',
            'description' => 'Research station in Tukuyu',
            'location' => 'Tukuyu, Mbeya',
            'centre_id' => $mbeya->id,
            'is_active' => true,
        ]);

        // Tanga Research Centre Station
        $korogwe = Station::create([
            'name' => 'Korogwe Station',
            'code' => 'KS',
            'description' => 'Research station in Korogwe',
            'location' => 'Korogwe, Tanga',
            'centre_id' => $tanga->id,
            'is_active' => true,
        ]);

        // Tabora Research Station (semi-independent, under Headquarters oversight)
        $tabora = Station::create([
            'name' => 'Tabora Research Station',
            'code' => 'TABS',
            'description' => 'Semi-independent research station under headquarters oversight',
            'location' => 'Tabora',
            'centre_id' => null, // Direct to headquarters
            'is_active' => true,
        ]);

        // Create Departments

        // Headquarters Departments
        Department::create([
            'name' => 'Executive Management',
            'code' => 'EXEC',
            'description' => 'Executive leadership and strategic management',
            'headquarters_id' => $headquarters->id,
            'centre_id' => null,
            'station_id' => null,
            'is_active' => true,
        ]);

        Department::create([
            'name' => 'Human Resources',
            'code' => 'HR',
            'description' => 'Institute-wide human resources management',
            'headquarters_id' => $headquarters->id,
            'centre_id' => null,
            'station_id' => null,
            'is_active' => true,
        ]);

        Department::create([
            'name' => 'Finance & Administration',
            'code' => 'FIN',
            'description' => 'Financial management and administration',
            'headquarters_id' => $headquarters->id,
            'centre_id' => null,
            'station_id' => null,
            'is_active' => true,
        ]);

        // Amani Hill Station Departments
        Department::create([
            'name' => 'Malaria Research',
            'code' => 'MAL',
            'description' => 'Department focusing on malaria research and vector control',
            'headquarters_id' => null,
            'centre_id' => null,
            'station_id' => $amaniHill->id,
            'is_active' => true,
        ]);

        Department::create([
            'name' => 'Vector Control',
            'code' => 'VEC',
            'description' => 'Department for vector control research and implementation',
            'headquarters_id' => null,
            'centre_id' => null,
            'station_id' => $amaniHill->id,
            'is_active' => true,
        ]);

        Department::create([
            'name' => 'Laboratory Services',
            'code' => 'LAB',
            'description' => 'Laboratory and diagnostic services',
            'headquarters_id' => null,
            'centre_id' => null,
            'station_id' => $amaniHill->id,
            'is_active' => true,
        ]);

        // Dodoma Centre Department (centre-level)
        Department::create([
            'name' => 'Public Health Research',
            'code' => 'PHR',
            'description' => 'Public health research and policy development',
            'headquarters_id' => null,
            'centre_id' => $dodoma->id,
            'station_id' => null,
            'is_active' => true,
        ]);

        // Ngongongare Station Departments
        Department::create([
            'name' => 'Traditional Medicine',
            'code' => 'TM',
            'description' => 'Traditional medicine research and documentation',
            'headquarters_id' => null,
            'centre_id' => null,
            'station_id' => $ngongongare->id,
            'is_active' => true,
        ]);

        // Korogwe Station Departments
        Department::create([
            'name' => 'Clinical Research',
            'code' => 'CLI',
            'description' => 'Clinical research and trials',
            'headquarters_id' => null,
            'centre_id' => null,
            'station_id' => $korogwe->id,
            'is_active' => true,
        ]);

        Department::create([
            'name' => 'Community Health',
            'code' => 'COM',
            'description' => 'Community health research and outreach',
            'headquarters_id' => null,
            'centre_id' => null,
            'station_id' => $korogwe->id,
            'is_active' => true,
        ]);

        // Tukuyu Station Department
        Department::create([
            'name' => 'Infectious Diseases',
            'code' => 'ID',
            'description' => 'Infectious diseases research and control',
            'headquarters_id' => null,
            'centre_id' => null,
            'station_id' => $tukuyu->id,
            'is_active' => true,
        ]);
    }
}
