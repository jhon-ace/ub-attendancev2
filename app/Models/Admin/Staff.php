<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use \App\Models\Admin\School; 

class Staff extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'staff';

    protected $fillable = [
        'school_id',
        'staff_id',
        'staff_firstname',
        'staff_middlename',
        'staff_lastname',
        'staff_rfid',
        'access_type',
    ];

    //each staff belongs to one school
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
