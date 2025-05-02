<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $classes = ['Class 8A', 'Class 9B', 'Class 10C', 'Class 11A'];

        for ($i = 1; $i <= 30; $i++) {
            Student::create([
                'student_id'     => 'STU-2025-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'first_name'     => fake()->firstName,
                'last_name'      => fake()->lastName,
                'email'          => fake()->unique()->safeEmail,
                'phone_number'   => fake()->phoneNumber,
                'dob'            => fake()->date('Y-m-d', '2010-12-31'),
                'gender'         => fake()->randomElement(['Male', 'Female']),
                'class'          => fake()->randomElement($classes),
                'enrollment_date'=> fake()->date('Y-m-d', '2023-09-01'),
                'address'        => fake()->address,
                'status'         => fake()->randomElement(['Active', 'On Leave', 'Inactive']),
                'parent_name'    => fake()->name,
                'relationship'   => fake()->randomElement(['Father', 'Mother', 'Guardian']),
                'email_parent'   => fake()->safeEmail,
                'phone_parent'   => fake()->phoneNumber,
            ]);
        }
    }
}

