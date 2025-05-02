<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Student API Routes
Route::get('/students', [App\Http\Controllers\StudentController::class, 'getStudents']);
Route::get('/students/{id}', [App\Http\Controllers\StudentController::class, 'getStudentDetails']);
Route::get('/performance-distribution', [App\Http\Controllers\StudentController::class, 'getPerformanceDistribution']);
Route::get('/quick-stats', [App\Http\Controllers\StudentController::class, 'getQuickStats']);
Route::post('/students', [App\Http\Controllers\StudentController::class, 'addStudent']);
Route::get('/students-stats', [App\Http\Controllers\StudentController::class, 'getStudentStats']);
Route::post('/students/import', [App\Http\Controllers\StudentController::class, 'importStudents']);
Route::get('/students/export', [App\Http\Controllers\StudentController::class, 'exportStudents']);
