<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    use HasFactory;
    protected $table = 'fingerprint';

    protected $fillable = [
        'fingerprint_status',
    ];

}
