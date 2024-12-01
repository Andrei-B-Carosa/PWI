<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrisRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hris_roles')->insert([
            [
                'name' => 'Admin',
                'description' => 'Administrator with full access',
                'enable_bypass_approver' => 1,
                'is_active' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employee',
                'description' => 'Regular employee role with limited access',
                'enable_bypass_approver' => null,
                'is_active' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
