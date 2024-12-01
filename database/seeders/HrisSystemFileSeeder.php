<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrisSystemFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hris_system_files')->insert([
            // [
            //     'name' => 'Home',
            //     'href' => 'home',
            //     'icon' => null,
            //     'folder' => null,
            //     'sub_menu' => 0,
            //     'status' => 1,
            //     'is_deleted' => 0,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Profile',
            //     'href' => 'profile',
            //     'icon' => null,
            //     'folder' => 'profile',
            //     'sub_menu' => 0,
            //     'status' => 1,
            //     'is_deleted' => 0,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Request',
            //     'href' => 'request',
            //     'icon' => null,
            //     'sub_menu' => 1,
            //     'folder' => 'request',
            //     'status' => 1,
            //     'is_deleted' => 0,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            [
                'name' => 'Dashboard',
                'href' => 'dashboard',
                'icon' => null,
                'sub_menu' => 0,
                'folder' => 'dashboard',
                'status' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '201 Employee',
                'href' => '201_employee',
                'icon' => null,
                'sub_menu' => 0,
                'folder' => '201_employee',
                'status' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User Management',
                'href' => 'user_management',
                'icon' => null,
                'sub_menu' => 1,
                'folder' => 'user_management',
                'status' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Approvals',
                'href' => 'approvals',
                'icon' => null,
                'sub_menu' => 1,
                'folder' => 'approvals',
                'status' => 1,
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Settings',
                'href' => 'settings',
                'icon' => null,
                'sub_menu' => 1,
                'folder' => 'settings',
                'status' => 1,
                'is_deleted' => 0,
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
