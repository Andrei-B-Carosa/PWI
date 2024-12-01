<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrisEmployeeLeaveBalance extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hris_employee_leave_balances')->insert([
            [
                'emp_id' => 2,
                'leave_type_id' => 1, // Example: Vacation Leave (VL)
                'leave_balance' => 15,
                'is_active' => 1,
                'is_deleted' => null,
                'created_by' => 1, // Admin or system user
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'emp_id' => 2,
                'leave_type_id' => 2, // Example: Sick Leave (SL)
                'leave_balance' => 10,
                'is_active' => 1,
                'is_deleted' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'emp_id' => 2,
                'leave_type_id' => 3, // Example: Emergency Leave (EL)
                'leave_balance' => 5,
                'is_active' => 1,
                'is_deleted' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}
