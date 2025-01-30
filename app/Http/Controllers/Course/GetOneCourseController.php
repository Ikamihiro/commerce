<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;

class GetOneCourseController extends Controller
{
    public function __invoke(Course $course)
    {
        return view('course', compact('course'));
    }
}
