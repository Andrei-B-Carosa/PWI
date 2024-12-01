<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrisApprovingOfficer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hris_approving_officers')->insert([
            [
                'emp_id' => 3,
                'company_id' => 1,
                'company_location_id'=>1,
                'department_id'=>1,
                'section_id'=>1,
                'remarks'=>null,
                'approver_level'=>1,
                'is_required'=>1,
                'is_final_approver'=>1,
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'emp_id' => 4,
                'company_id' => 1,
                'company_location_id'=>1,
                'department_id'=>1,
                'section_id'=>1,
                'remarks'=>null,
                'approver_level'=>2,
                'is_required'=>1,
                'is_final_approver'=>0,
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
