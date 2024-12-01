<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hris_departments')->insert([
            [
                'name' => 'Human Resource Department',
                'description' => 'Human Resource Department',
                'is_active' => 1,
                'is_deleted' => null,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Management Information System',
                'description' => 'Management Information System',
                'is_active' => 1,
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
