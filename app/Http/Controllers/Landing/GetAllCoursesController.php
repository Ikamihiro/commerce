<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetAllCoursesController extends Controller
{
    public function __invoke()
    {
        return view('landing.courses.index');
    }
}
