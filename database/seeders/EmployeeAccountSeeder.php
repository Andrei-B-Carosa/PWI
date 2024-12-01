<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class EmployeeAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) { // Generate 10 random records
            DB::table('employee_accounts')->insert([
                'emp_id' => $index,
                'c_email' => $faker->unique()->companyEmail,
                'username' => $faker->userName,
                'password' => Hash::make('password123'),
                'bypass_key' => Crypt::encrypt('password123'),
                'is_active' => $faker->boolean(80) ? 1 : 0, // 80% chance of being active
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
                'created_by' => $faker->numberBetween(1, 5),
                'updated_by' => $faker->optional()->numberBetween(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
