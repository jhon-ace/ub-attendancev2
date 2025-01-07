<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SAOController extends Controller
{
    public function index()
    {
        return view('Sao.index');
    }
}
