<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use \App\Models\Admin\School; 
use \App\Models\Admin\Department; 
use \App\Models\Admin\Course; 

class Student extends Model
{
    use HasFactory, HasRoles;
    protected $table = 'students';

    protected $fillable = [
        'course_id',
        'student_id',
        'student_photo',
        'student_lastname',
        'student_firstname',
        'student_middlename',
        'student_year/grade',
        'student_rfid',
        'student_status',
    ];

    //each student belongs to one course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // public function school()
    // {
    //     return $this->belongsTo(School::class, 'school_id');
    // }

    public function school()
    {
        return $this->course->school();
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

         // Each student can have many attendance records
    public function attendanceTimeInStudent()
    {
        return $this->hasMany(StudentAttendanceTimeIn::class);
    }

     // Each student can have many attendance records
    public function attendanceTimeOutStudent()
    {
        return $this->hasMany(StudentAttendanceTimeOut::class);
    }

}
