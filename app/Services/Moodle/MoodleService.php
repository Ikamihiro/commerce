<?php

namespace App\Services\Moodle;

use App\Models\Course;
use App\Models\Student;

class MoodleService
{
    public function enrollStudent(Student $student, Course $course): void
    {
        // Enroll the student in the course
    }

    public function unenrollStudent(Student $student, Course $course): void
    {
        // Unenroll the student from the course
    }
}
