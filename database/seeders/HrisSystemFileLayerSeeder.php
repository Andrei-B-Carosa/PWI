<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrisSystemFileLayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hris_system_file_layers')->insert([
            // [
            //     'file_id' => 3,
            //     'name' => 'Overtime Request',
            //     'href' => 'overtime_request',
            //     'folder' => null,
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
            //     'file_id' => 3,
            //     'name' => 'Leave Request',
            //     'href' => 'leave_request',
            //     'folder' => null,
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
            //     'file_id' => 3,
            //     'name' => 'OB Request',
            //     'href' => 'ob_request',
            //     'folder' => null,
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
            //     'file_id' => 5,
            //     'name' => 'Employee Masterlist',
            //     'href' => 'employee_masterlist',
            //     'folder' => null,
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
            //     'file_id' => 6,
            //     'name' => 'Role List',
            //     'href' => 'role_list',
            //     'folder' => null,
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
            //     'file_id' => 6,
            //     'name' => 'Permissions',
            //     'href' => 'permissions',
            //     'folder' => null,
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
            //     'file_id' => 7,
            //     'name' => '',
            //     'href' => 'approve_leave',
            //     'folder' => null,
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
            //     'file_id' => 8,
            //     'name' => 'Leave Setting',
            //     'href' => 'leave_setting',
            //     'folder' => 'leave_setting',
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
            //     'file_id' => 8,
            //     'name' => 'File Maintenance',
            //     'href' => 'file_maintenance',
            //     'folder' => null,
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
            //     'file_id' => 8,
            //     'name' => 'Group Settings',
            //     'href' => 'group_settings',
            //     'folder' => null,
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
            //     'file_id' => 7,
            //     'name' => 'Overtime Requisition',
            //     'href' => 'overtime_requisition',
            //     'folder' => null,
            //     'status' => 1,

            //     'is_deleted' => null,
            //     'deleted_by' => null,
            //     'deleted_at' => null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'file_id'=>7,
            //     'name'=>'Application for Leave',
            //     'href'=>'application_for_leave',
            //     'folder'=>null,
            //     'status'=>1,
            //     'is_deleted'=>null,
            //     'deleted_by'=>null,
            //     'deleted_at'=>null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'file_id'=>7,
            //     'name'=>'Official Business',
            //     'href'=>'official_business',
            //     'folder'=>null,
            //     'status'=>1,
            //     'is_deleted'=>null,
            //     'deleted_by'=>null,
            //     'deleted_at'=>null,
            //     'created_by' => 1,
            //     'updated_by' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]
        ]);
    }
}
