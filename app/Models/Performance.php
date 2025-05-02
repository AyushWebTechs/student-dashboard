<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject',
        'score',
        'test_date',
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
