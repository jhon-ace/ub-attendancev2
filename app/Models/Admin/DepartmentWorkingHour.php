<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Admin\School; 
use \App\Models\Admin\Department; 

class DepartmentWorkingHour extends Model
{
    use HasFactory;
    protected $table = 'working_hour';

    protected $fillable = [
        'school_id',
        'department_id', //FK
        'day_of_week',
        'morning_start_time',
        'morning_end_time',
        'afternoon_start_time',
        'afternoon_end_time'
    ];

    //each_working_hour belongs to one school
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    //each working_hour belongs to one department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    
}
