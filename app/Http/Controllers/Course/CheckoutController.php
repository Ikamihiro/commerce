<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CheckoutController extends Controller
{
    public function __invoke(Course $course)
    {
        return view('checkout', compact('course'));
    }
}
