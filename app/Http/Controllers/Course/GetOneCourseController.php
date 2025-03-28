<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;

class GetOneCourseController extends Controller
{
    public function __invoke(Course $course)
    {
        $course->load([
            'modules',
            'questions',
        ]);

        return view('course', compact('course'));
    }
}
