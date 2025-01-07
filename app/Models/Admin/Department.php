<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Admin\School; 
use \App\Models\Admin\Employee; 
use \App\Models\Admin\Course;
use \App\Models\Admin\DepartmentWorkingHour; 

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';

    protected $fillable = [
        'school_id',
        'department_id',
        'department_abbreviation',
        'department_name',
        'dept_identifier',
    ];

    //each department belongs to one school
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    // A department has many course
    public function course()
    {
         return $this->hasMany(Course::class, 'department_id');
    }

    //A department has many working hour
    public function working_hour()
    {
         return $this->hasMany(DepartmentWorkingHour::class, 'department_id');
    }
    

    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id', 'department_id');
    }

    // // A department has many Employee
    // public function employees()
    // {
    //     return $this->hasMany(Employee::class);
    // }
}
