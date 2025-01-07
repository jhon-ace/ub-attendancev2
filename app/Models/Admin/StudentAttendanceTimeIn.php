<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Admin\School; 
use \App\Models\Admin\Employee;
use \App\Models\Admin\Department; 
use \App\Models\Admin\Student; 

class StudentAttendanceTimeIn extends Model
{
    use HasFactory;
    protected $table = 'students_time_in_attendance';

    protected $fillable = [
        'student_id', //FK
        'check_in_time',
        'status',
    ];

    public function school()
    {
        return $this->course->school();
    }
    public function department()
    {
        return $this->course->department();
    }

    // public function course()
    // {
    //     return $this->belongsTo(Course::class, 'course_id');
    // }
    // Each attendance record belongs to one student
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }


}
