<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name', 
        'email',
        'phone_number',
        'dob',
        'gender',
        'class',
        'enrollment_date',
        'address',
        'status',
        'parent_name',
        'relationship',
        'email_parent',
        'phone_parent'
    ];

    protected $casts = [
        'dob' => 'date',
        'enrollment_date' => 'date'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function performances() 
    {
        return $this->hasMany(Performance::class);
    }

}
    