<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Admin\School; 
use \App\Models\Admin\Department; 
use \App\Models\Admin\Employee; 

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';

    protected $fillable = [
        'school_id',
        'department_id',
        'course_id',
        'course_logo',
        'course_abbreviation',
        'course_name',
    ];


    //each courses belongs to one school
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    // Each courses belongs to one departments
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // A course has many student
    public function students()
    {
        return $this->hasMany(Student::class, 'course_id');
    }

    
}
