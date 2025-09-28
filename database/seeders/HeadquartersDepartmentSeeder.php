<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Headquarters;
use Illuminate\Support\Str;

class HeadquartersDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hq = Headquarters::where('is_active', true)->first();
        if (!$hq) {
            $this->command?->warn('No active headquarters found; skipping HQ departments seeding.');
            return;
        }

        $departments = [
            [ 'name' => 'Research & Scientific Programs', 'code' => 'RSP', 'description' => 'Coordinates institute-wide research strategy, program oversight, and scientific quality.' ],
            [ 'name' => 'Ethics & Compliance', 'code' => 'ETH', 'description' => 'Manages ethics review, regulatory adherence, and compliance monitoring.' ],
            [ 'name' => 'Internal Audit', 'code' => 'IAD', 'description' => 'Performs independent audits, risk assessments, and control evaluations.' ],
            [ 'name' => 'Legal', 'code' => 'LEG', 'description' => 'Provides legal advisory, contract review, and policy interpretation.' ],
            [ 'name' => 'ICT', 'code' => 'ICT', 'description' => 'Manages digital infrastructure, systems support, and cybersecurity.' ],
            [ 'name' => 'Procurement', 'code' => 'PROC', 'description' => 'Oversees sourcing, vendor management, and acquisition processes.' ],
            [ 'name' => 'Public Relations', 'code' => 'PRC', 'description' => 'Handles media relations, branding, and institutional communications.' ],
            [ 'name' => 'Finance', 'code' => 'FIN', 'description' => 'Responsible for budgeting, financial reporting, and fiscal stewardship.' ],
            [ 'name' => 'Planning', 'code' => 'PLN', 'description' => 'Leads strategic planning, institutional performance tracking, and reporting.' ],
            [ 'name' => 'Human Resource', 'code' => 'HR', 'description' => 'Manages recruitment, employee lifecycle, welfare, and capacity development.' ],
        ];

        foreach ($departments as $data) {
            Department::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'headquarters_id' => $hq->id,
                    'centre_id' => null,
                    'station_id' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
