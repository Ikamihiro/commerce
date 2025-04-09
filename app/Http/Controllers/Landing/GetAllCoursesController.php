<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class GetAllCoursesController extends Controller
{
    public function __invoke(Request $request)
    {
        $courses = Course::latest()
            ->when($request->search, function ($query) use ($request) {
                $query
                    ->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            })->get();

        return view('courses', compact('courses'));
    }
}
