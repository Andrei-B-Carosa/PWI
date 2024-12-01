<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeePositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch data from the relevant tables
        $positions = DB::table('hris_positions')->get();
        $departments = DB::table('hris_departments')->get();
        $classifications = DB::table('hris_classifications')->get();
        $companyLocations = DB::table('hris_company_locations')->get();
        $companies = DB::table('hris_companies')->get();
        $employmentTypes = DB::table('hris_employment_types')->get();
        $sections = DB::table('hris_sections')->get();
        $employees = DB::table('employees')->pluck('id');

        $employeePositions = [];

        foreach ($employees as $empId) {
            $position = $positions->random();
            $department = $departments->random();
            $classification = $classifications->random();
            // $companyLocation = $companyLocations->random();
            // $company = $companies->random();
            $employmentType = $employmentTypes->random();
            $section = $sections->random();

            $employeePositions[] = [
                'emp_id' => $empId,
                'position_id' => $position->id,
                'department_id' => $department->id,
                'company_id' => 1,
                'company_location_id' =>1,
                'section_id' => $section->id,
                'classification_id' => $classification->id,
                'employment_id' => $employmentType->id,
                'date_employed' => Carbon::now(),
                'is_active' => 1,
                'work_status' => 1,
                'is_deleted' => null,
                'created_by' => 1, // Replace with actual user ID if needed
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('hris_employee_positions')->insert($employeePositions);
    }
}
