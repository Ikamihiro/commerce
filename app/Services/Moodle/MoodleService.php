<?php

namespace App\Services\Moodle;

use App\Mail\StudentEnrolled;
use App\Mail\StudentUnenrolled;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;

class MoodleService
{
    public function enrollStudent(Student $student, Course $course): void
    {
        // Enroll the student in the course

        Mail::to($student->email)
            ->send(new StudentEnrolled());
    }

    public function unenrollStudent(Student $student, Course $course): void
    {
        // Unenroll the student from the course

        Mail::to($student->email)
            ->send(new StudentUnenrolled());
    }
}
