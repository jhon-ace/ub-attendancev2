<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageHandler extends Model
{
    use HasFactory;

    protected $table = 'image_handler';

    protected $fillable = [
        'value'
    ];
}
