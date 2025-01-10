<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use \App\Models\Admin\School; 
use \App\Models\Admin\Employee; 



class Employee extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = 'employees';

    // protected $guard_name = 'employee'; // Ensure this matches your guard
    
    protected $fillable = [
        'school_id',
        'department_id',
        'employee_photo',
        'employee_id',
        'employee_lastname',
        'employee_firstname',
        'employee_middlename',
        'employee_rfid',
        'role',
        'username',
        'password',

    ];

    //each employee belongs to one school
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    // Each employee belongs to one departments
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

     // Each employee can have many attendance records
    public function attendanceTimeIn()
    {
        return $this->hasMany(EmployeeAttendanceTimeIn::class);
    }

     // Each employee can have many attendance records
    public function attendanceTimeOut()
    {
        return $this->hasMany(EmployeeAttendanceTimeOut::class);
    }

    

}
