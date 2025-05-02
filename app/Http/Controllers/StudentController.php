<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\Performance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        return view('students.index');
    }

    public function getStudents(Request $request)
    {
        $query = Student::query();

        // Apply filters
        if ($request->has('class') && $request->class !== 'all') {
            $query->where('class', $request->class);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('performance') && $request->performance !== 'all') {
            $query->whereHas('performances', function($q) use ($request) {
                $q->select('student_id', DB::raw('AVG(score) as avg_score'))
                  ->groupBy('student_id')
                  ->having('avg_score', $this->getPerformanceOperator($request->performance));
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Calculate performance and attendance
        $students = $query->with(['attendances', 'performances'])->paginate(10);

        // Transform the data to include calculated fields
        $students->getCollection()->transform(function ($student) {
            // Calculate average performance
            $performance = $student->performances->avg('score') ?? 0;
            
            // Calculate attendance rate
            $totalAttendance = $student->attendances->count();
            $presentAttendance = $student->attendances->where('status', 'Present')->count();
            $attendanceRate = $totalAttendance > 0 ? round(($presentAttendance / $totalAttendance) * 100) : 0;

            return [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'class' => $student->class,
                'phone_number' => $student->phone_number,
                'status' => $student->status,
                'performance' => round($performance, 1),
                'attendance' => $attendanceRate,
                'avatar_url' => $student->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->first_name . ' ' . $student->last_name)
            ];
        });

        // Get unique values for filters
        $classes = Student::select('class')->distinct()->pluck('class');
        $statuses = Student::select('status')->distinct()->pluck('status');

        return response()->json([
            'data' => $students,
            'filters' => [
                'classes' => $classes,
                'statuses' => $statuses
            ]
        ]);
    }

    private function getPerformanceOperator($performance)
    {
        return match($performance) {
            'excellent' => '>= 90',
            'good' => 'BETWEEN 80 AND 89',
            'average' => 'BETWEEN 70 AND 79',
            'below_average' => 'BETWEEN 60 AND 69',
            'poor' => '< 60',
            default => '>= 0'
        };
    }

    public function getStudentDetails($id)
    {
        $student = Student::with(['attendances', 'performances'])->findOrFail($id);

        // Calculate performance by subject
        $performanceData = $student->performances()
            ->select('subject', DB::raw('AVG(score) as average_score'))
            ->groupBy('subject')
            ->get();

        // Calculate attendance statistics
        $attendanceData = $student->attendances()
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'student' => [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'class' => $student->class,
                'phone_number' => $student->phone_number,
                'status' => $student->status,
                'dob' => $student->dob,
                'gender' => $student->gender,
                'address' => $student->address,
                'parent_name' => $student->parent_name,
                'relationship' => $student->relationship,
                'email_parent' => $student->email_parent,
                'phone_parent' => $student->phone_parent,
                'avatar_url' => $student->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->first_name . ' ' . $student->last_name)
            ],
            'performance' => $performanceData,
            'attendance' => $attendanceData
        ]);
    }

    public function getPerformanceDistribution(Request $request)
    {
        $class = $request->query('class', 'all');
        
        // First, get the average scores for each student
        $query = DB::table('students')
            ->join('performances', 'students.id', '=', 'performances.student_id')
            ->select(
                'students.class',
                'students.id',
                DB::raw('AVG(performances.score) as average_score')
            )
            ->where('performances.test_date', '>=', '2025-03-01')
            ->where('performances.test_date', '<=', '2025-04-30')
            ->groupBy('students.id', 'students.class');

        if ($class !== 'all') {
            $query->where('students.class', $class);
        }

        // Then, use the subquery to count students in each range
        $distribution = DB::table(DB::raw("({$query->toSql()}) as student_scores"))
            ->mergeBindings($query)
            ->select(
                'class',
                DB::raw('COUNT(CASE WHEN average_score < 60 THEN 1 END) as below_60'),
                DB::raw('COUNT(CASE WHEN average_score BETWEEN 60 AND 70 THEN 1 END) as between_60_70'),
                DB::raw('COUNT(CASE WHEN average_score BETWEEN 70 AND 80 THEN 1 END) as between_70_80'),
                DB::raw('COUNT(CASE WHEN average_score BETWEEN 80 AND 90 THEN 1 END) as between_80_90'),
                DB::raw('COUNT(CASE WHEN average_score >= 90 THEN 1 END) as above_90')
            )
            ->groupBy('class')
            ->get();

        // If a specific class is selected, return a single object instead of an array
        if ($class !== 'all') {
            $distribution = $distribution->first() ?? (object)[
                'class' => $class,
                'below_60' => 0,
                'between_60_70' => 0,
                'between_70_80' => 0,
                'between_80_90' => 0,
                'above_90' => 0
            ];
            // Convert to array with single item for consistent frontend handling
            $distribution = [$distribution];
        }

        // Get unique classes for the filter
        $classes = Student::select('class')->distinct()->pluck('class');

        return response()->json([
            'distribution' => $distribution,
            'classes' => $classes
        ]);
    }

    public function getQuickStats()
    {
        $totalStudents = Student::count();
        
        // Calculate average performance across all students
        $averagePerformance = DB::table('performances')
            ->select(DB::raw('AVG(score) as avg_score'))
            ->first()->avg_score ?? 0;

        // Calculate average attendance rate
        $attendanceStats = DB::table('attendances')
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
            )
            ->first();
        
        $averageAttendance = $attendanceStats->total > 0 
            ? round(($attendanceStats->present / $attendanceStats->total) * 100, 1)
            : 0;

        // Count at-risk students (average performance below 60)
        $atRiskStudents = DB::table('students')
            ->select('students.id')
            ->join('performances', 'students.id', '=', 'performances.student_id')
            ->groupBy('students.id')
            ->having(DB::raw('AVG(performances.score)'), '<', 60)
            ->count();

        return response()->json([
            'total_students' => $totalStudents,
            'average_performance' => round($averagePerformance, 1),
            'average_attendance' => $averageAttendance,
            'at_risk_students' => $atRiskStudents
        ]);
    }

    public function addStudent(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone_number' => 'required|string|max:20',
            'class' => 'required|string|max:50',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'parent_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:50',
            'email_parent' => 'required|email',
            'phone_parent' => 'required|string|max:20',
            'status' => 'required|in:active,inactive,on leave'
        ]);

        try {
            DB::beginTransaction();

            // Generate student ID
            $studentId = 'STU-' . date('Y') . '-' . str_pad(Student::count() + 1, 3, '0', STR_PAD_LEFT);

            $student = Student::create([
                'student_id' => $studentId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'class' => $request->class,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'address' => $request->address,
                'parent_name' => $request->parent_name,
                'relationship' => $request->relationship,
                'email_parent' => $request->email_parent,
                'phone_parent' => $request->phone_parent,
                'status' => $request->status,
                'enrollment_date' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student added successfully',
                'student' => $student
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getStudentStats()
    {
        // Current month stats
        $currentMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');
        $lastSemester = now()->subMonths(6)->format('Y-m');

        // Total Students
        $currentTotalStudents = Student::whereYear('enrollment_date', '<=', now()->year)
            ->whereMonth('enrollment_date', '<=', now()->month)
            ->count();
        
        $lastMonthTotalStudents = Student::whereYear('enrollment_date', '<=', now()->subMonth()->year)
            ->whereMonth('enrollment_date', '<=', now()->subMonth()->month)
            ->count();
        
        $totalStudentsChange = $lastMonthTotalStudents > 0 
            ? round((($currentTotalStudents - $lastMonthTotalStudents) / $lastMonthTotalStudents) * 100, 1)
            : 0;

        // Average Performance - Updated to handle future dates
        $currentPerformance = Performance::where('test_date', '>=', '2025-03-01')
            ->where('test_date', '<=', '2025-04-30')
            ->avg('score') ?? 0;
        
        $lastSemesterPerformance = Performance::where('test_date', '>=', '2025-01-01')
            ->where('test_date', '<', '2025-03-01')
            ->avg('score') ?? 0;
        
        $performanceChange = $lastSemesterPerformance > 0 
            ? round((($currentPerformance - $lastSemesterPerformance) / $lastSemesterPerformance) * 100, 1)
            : 0;

        // Attendance Rate
        $currentAttendance = Attendance::whereYear('date', '>=', now()->subMonth()->year)
            ->whereMonth('date', '>=', now()->subMonth()->month)
            ->where('status', 'Present')
            ->count();
        
        $currentTotalAttendance = Attendance::whereYear('date', '>=', now()->subMonth()->year)
            ->whereMonth('date', '>=', now()->subMonth()->month)
            ->count();
        
        $lastMonthAttendance = Attendance::whereYear('date', '>=', now()->subMonths(2)->year)
            ->whereMonth('date', '>=', now()->subMonths(2)->month)
            ->whereYear('date', '<', now()->subMonth()->year)
            ->whereMonth('date', '<', now()->subMonth()->month)
            ->where('status', 'Present')
            ->count();
        
        $lastMonthTotalAttendance = Attendance::whereYear('date', '>=', now()->subMonths(2)->year)
            ->whereMonth('date', '>=', now()->subMonths(2)->month)
            ->whereYear('date', '<', now()->subMonth()->year)
            ->whereMonth('date', '<', now()->subMonth()->month)
            ->count();
        
        $currentAttendanceRate = $currentTotalAttendance > 0 
            ? round(($currentAttendance / $currentTotalAttendance) * 100)
            : 0;
        
        $lastMonthAttendanceRate = $lastMonthTotalAttendance > 0 
            ? round(($lastMonthAttendance / $lastMonthTotalAttendance) * 100)
            : 0;
        
        $attendanceChange = $lastMonthAttendanceRate > 0 
            ? round($currentAttendanceRate - $lastMonthAttendanceRate, 1)
            : 0;

        // At-Risk Students - Updated to handle future dates
        $currentAtRisk = DB::table('students')
            ->join('performances', 'students.id', '=', 'performances.student_id')
            ->where('performances.test_date', '>=', '2025-03-01')
            ->where('performances.test_date', '<=', '2025-04-30')
            ->select('students.id')
            ->groupBy('students.id')
            ->having(DB::raw('AVG(performances.score)'), '<', 60)
            ->count();
        
        $lastMonthAtRisk = DB::table('students')
            ->join('performances', 'students.id', '=', 'performances.student_id')
            ->where('performances.test_date', '>=', '2025-01-01')
            ->where('performances.test_date', '<', '2025-03-01')
            ->select('students.id')
            ->groupBy('students.id')
            ->having(DB::raw('AVG(performances.score)'), '<', 60)
            ->count();
        
        $atRiskChange = $lastMonthAtRisk > 0 
            ? round((($currentAtRisk - $lastMonthAtRisk) / $lastMonthAtRisk) * 100, 1)
            : 0;

        return response()->json([
            'total_students' => [
                'value' => $currentTotalStudents,
                'change' => $totalStudentsChange,
                'change_type' => $totalStudentsChange >= 0 ? 'increase' : 'decrease'
            ],
            'average_performance' => [
                'value' => round($currentPerformance, 1),
                'change' => $performanceChange,
                'change_type' => $performanceChange >= 0 ? 'increase' : 'decrease'
            ],
            'attendance_rate' => [
                'value' => $currentAttendanceRate,
                'change' => $attendanceChange,
                'change_type' => $attendanceChange >= 0 ? 'increase' : 'decrease'
            ],
            'at_risk_students' => [
                'value' => $currentAtRisk,
                'change' => $atRiskChange,
                'change_type' => $atRiskChange >= 0 ? 'increase' : 'decrease'
            ]
        ]);
    }

    public function importStudents(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file uploaded'
                ], 400);
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            if (!in_array($extension, ['csv', 'xlsx'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file format. Please upload CSV or Excel file.'
                ], 400);
            }

            // Read the file
            $data = [];
            if ($extension === 'csv') {
                $handle = fopen($file->getPathname(), 'r');
                $headers = fgetcsv($handle);
                while (($row = fgetcsv($handle)) !== false) {
                    $data[] = array_combine($headers, $row);
                }
                fclose($handle);
            } else {
                // For Excel files, you'll need to implement Excel reading logic
                // You can use packages like PhpSpreadsheet
                return response()->json([
                    'success' => false,
                    'message' => 'Excel import not implemented yet'
                ], 400);
            }

            // Validate and import data
            $imported = 0;
            $errors = [];
            DB::beginTransaction();

            foreach ($data as $index => $row) {
                try {
                    // Validate required fields
                    $validator = Validator::make($row, [
                        'first_name' => 'required|string|max:255',
                        'last_name' => 'required|string|max:255',
                        'email' => 'required|email|unique:students,email',
                        'class' => 'required|string|max:50',
                        'status' => 'required|in:active,inactive,on leave'
                    ]);

                    if ($validator->fails()) {
                        $errors[] = "Row " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                        continue;
                    }

                    // Generate student ID
                    $studentId = 'STU-' . date('Y') . '-' . str_pad(Student::count() + 1, 3, '0', STR_PAD_LEFT);

                    // Create student
                    Student::create([
                        'student_id' => $studentId,
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'email' => $row['email'],
                        'class' => $row['class'],
                        'status' => $row['status'],
                        'enrollment_date' => now()
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            if ($imported > 0) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => "Successfully imported $imported students" . (count($errors) > 0 ? " with " . count($errors) . " errors" : ""),
                    'imported' => $imported,
                    'errors' => $errors
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No students were imported',
                    'errors' => $errors
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error importing students: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportStudents(Request $request)
    {
        try {
            $query = Student::query();

            // Apply filters if provided
            if ($request->has('class') && $request->class !== 'all') {
                $query->where('class', $request->class);
            }
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
            if ($request->has('performance') && $request->performance !== 'all') {
                $query->whereHas('performances', function($q) use ($request) {
                    $q->select('student_id', DB::raw('AVG(score) as avg_score'))
                      ->groupBy('student_id')
                      ->having('avg_score', $this->getPerformanceOperator($request->performance));
                });
            }

            $students = $query->get();

            // Prepare CSV data
            $headers = [
                'Student ID',
                'First Name',
                'Last Name',
                'Email',
                'Class',
                'Status',
                'Phone Number',
                'Date of Birth',
                'Gender',
                'Address',
                'Parent Name',
                'Parent Relationship',
                'Parent Email',
                'Parent Phone',
                'Enrollment Date'
            ];

            $callback = function() use ($students, $headers) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for proper Excel encoding
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Add headers
                fputcsv($file, $headers);

                // Add student data
                foreach ($students as $student) {
                    fputcsv($file, [
                        $student->student_id,
                        $student->first_name,
                        $student->last_name,
                        $student->email,
                        $student->class,
                        $student->status,
                        $student->phone_number,
                        $student->dob,
                        $student->gender,
                        $student->address,
                        $student->parent_name,
                        $student->relationship,
                        $student->email_parent,
                        $student->phone_parent,
                        $student->enrollment_date
                    ]);
                }

                fclose($file);
            };

            $filename = 'students_export_' . date('Y-m-d_His') . '.csv';

            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting students: ' . $e->getMessage()
            ], 500);
        }
    }
}
