<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GracePeriod extends Model
{
    use HasFactory;
    protected $table = 'grace_period';

    protected $fillable = [
        'grace_period',
    ];
}
