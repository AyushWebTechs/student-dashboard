<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $students = Student::all();

        foreach ($students as $student) {
            for ($i = 0; $i < 15; $i++) {
                Attendance::create([
                    'student_id' => $student->id,
                    'date'       => now()->subDays(rand(0, 30))->format('Y-m-d'),
                    'status'     => fake()->randomElement(['Present', 'Absent', 'Leave']),
                ]);
            }
        }
    }
}

