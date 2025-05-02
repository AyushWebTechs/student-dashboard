<?php

namespace Database\Seeders;

use App\Models\Performance;
use App\Models\Student;
use Illuminate\Database\Seeder;

class PerformanceSeeder extends Seeder
{
    public function run()
    {
        $students = Student::all();
        $subjects = ['Math', 'Science', 'English', 'History', 'Geography'];

        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                Performance::create([
                    'student_id' => $student->id,
                    'subject'    => $subject,
                    'score'      => rand(50, 100),
                    'test_date'  => now()->subDays(rand(5, 40))->format('Y-m-d'),
                    'remarks'    => fake()->optional()->sentence,
                ]);
            }
        }
    }
}

