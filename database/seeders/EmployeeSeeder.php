<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) { // Generate 10 random records
            DB::table('employees')->insert([
                'emp_image' => $faker->optional()->imageUrl(200, 200, 'people'),
                'emp_no' => 'EMP-' . str_pad($index, 5, '0', STR_PAD_LEFT),

                'fname' => $faker->firstName,
                'mname' => $faker->optional()->firstName,
                'lname' => $faker->lastName,
                'ext' => $faker->optional()->suffix,
                'title' => $faker->optional()->jobTitle,
                'p_email' => $faker->unique()->safeEmail,
                'birthday' => $faker->date(),
                'birthplace' => $faker->city,
                'sex' => $faker->randomElement([1, 2]), // 1: Male, 2: Female
                'civil_status' => $faker->randomElement([1, 2, 3, 4]), // Example statuses
                'height' => $faker->numberBetween(150, 200), // height in cm
                'weight' => $faker->numberBetween(50, 100), // weight in kg
                'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'sss' => $faker->numerify('##-#######-#'),
                'pagibig' => $faker->numerify('####-####-####'),
                'philhealth' => $faker->numerify('####-####-####'),
                'gsis' => $faker->numerify('####-####-####'),
                'tin' => $faker->numerify('####-###-###'),
                'citizenship' => 'Filipino',
                'dual_citizenship' => $faker->optional()->country,
                'current_address' => $faker->address,
                'mobile_number' => $faker->phoneNumber,
                'telephone_number' => $faker->optional()->phoneNumber,

                // Spouse information
                'spouse_fname' => $faker->optional()->firstName,
                'spouse_lname' => $faker->optional()->lastName,
                'spouse_mname' => $faker->optional()->firstName,
                'spouse_occupation' => $faker->optional()->jobTitle,
                'spouse_employer' => $faker->optional()->company,
                'spouse_business_address' => $faker->optional()->address,

                // Parents' information
                'father_fname' => $faker->firstNameMale,
                'father_lname' => $faker->lastName,
                'father_mname' => $faker->optional()->firstName,
                'father_ext' => $faker->optional()->suffix,
                'mother_fname' => $faker->firstNameFemale,
                'mother_lname' => $faker->lastName,
                'mother_mname' => $faker->optional()->firstName,

                'is_active' => $faker->boolean(80) ? 1 : 0, // 80% chance of being active
                'created_by' => $faker->numberBetween(1, 5),
                'updated_by' => $faker->optional()->numberBetween(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
                'is_deleted' => 0,
                'deleted_by' => null,
                'deleted_at' => null,
            ]);
        }
    }
}
