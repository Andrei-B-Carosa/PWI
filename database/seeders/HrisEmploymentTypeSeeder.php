<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrisEmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hris_employment_types')->insert([
            [
                'name' => 'Trainee',
                'is_active' => 1,
                'is_deleted' => null,
                'created_by' => 1, // Admin or system user
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Probationary',
                'is_active' => 1,
                'is_deleted' => null,
                'created_by' => 1, // Admin or system user
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Regular',
                'is_active' => 1,
                'is_deleted' => null,
                'created_by' => 1, // Admin or system user
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Part-Time',
                'is_active' => 1,
                'is_deleted' => null,
                'created_by' => 1, // Admin or system user
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
