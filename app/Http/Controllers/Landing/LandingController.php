<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function __invoke(Request $request)
    {
        $courses = Course::query()
            // ->where('order', '>', 0)
            ->orderBy('order', 'asc')
            ->limit(6)
            ->get();

        return view('welcome', compact('courses'));
    }
}
