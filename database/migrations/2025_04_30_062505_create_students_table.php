<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique(); // STU-2025-001
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->date('dob');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->enum('class', ['Class 8A', 'Class 9B', 'Class 10C', 'Class 11A']);
            $table->date('enrollment_date');
            $table->text('address');
            $table->enum('status', ['Active', 'On Leave', 'Inactive'])->default('Active');
        
            // Parent Info
            $table->string('parent_name');
            $table->string('relationship');
            $table->string('email_parent');
            $table->string('phone_parent');
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
