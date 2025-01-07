<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use \App\Models\Admin\Department; 
use \App\Models\Admin\Staff; 
use \App\Models\Admin\Employee; 
use \App\Models\Admin\Student; 

class School extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'schools';

    protected $fillable = [
        'abbreviation',
        'school_name',
    ];



        // A school has many department
    public function department()
    {
        return $this->hasMany(Department::class);
    }

    // A school has many course
    public function course()
    {
        return $this->hasMany(Course::class);
    }
    

        // A school has many employees
    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

    // A school has many staff
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }



    // A school has many students
    public function student()
    {
        return $this->hasMany(Student::class);
    }













    // // A school has many staff
    // public function staff()
    // {
    //     return $this->hasMany(Staff::class);
    // }

    // // A school has many employee
    // public function employee()
    // {
    //     return $this->hasMany(Employee::class);
    // }

    // // A school has many student
    // public function student()
    // {
    //     return $this->hasMany(Student::class);
    // }
    
}
