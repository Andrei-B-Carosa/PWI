<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrisLeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hris_leave_types')->insert([
            [
                'name' => 'Vacation Leave',
                'description' => 'Vacation Leave',
                'code' => 'VL',
                'company_id' => 1,
                'is_active' => 1,
                'gender_type'=>null,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sick Leave',
                'description' => 'Sick Leave',
                'code' => 'SL',
                'company_id' => 1,
                'is_active' => 1,
                'gender_type'=>null,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maternity Leave',
                'description' => 'Maternity Leave',
                'code' => 'ML',
                'company_id' => 1,
                'is_active' => 1,
                'gender_type'=>2,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
