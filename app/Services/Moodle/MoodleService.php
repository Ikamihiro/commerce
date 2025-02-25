<?php

namespace App\Services\Moodle;

use App\Mail\StudentEnrolled;
use App\Mail\StudentUnenrolled;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MoodleService
{
    public function __construct(
        protected MoodleClient $client,
    ) {}

    public function enrollStudent(Student $student, Course $course): void
    {
        DB::beginTransaction();

        try {
            $this->client->request('enrol_student', [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'role_id' => 5,
            ]);

            Mail::to($student->email)->send(new StudentEnrolled(
                course: $course,
                student: $student,
            ));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    public function unenrollStudent(Student $student, Course $course): void
    {
        // TODO: Unenroll the student from the course

        Mail::to($student->email)
            ->send(new StudentUnenrolled(
                course: $course,
                student: $student,
            ));
    }
}
