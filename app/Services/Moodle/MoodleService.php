<?php

namespace App\Services\Moodle;

use App\Mail\StudentEnrolled;
use App\Mail\StudentUnenrolled;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MoodleService
{
    protected const STUDENT_ROLE_ID = 5;

    public function __construct(
        protected MoodleClient $client,
    ) {}

    public function enrollStudent(Student $student, Course $course): void
    {
        Log::info('Enrolling student to course', [
            'student' => $student->toArray(),
            'course' => $course->toArray(),
        ]);

        DB::beginTransaction();

        try {
            $user = $this->createUserToStudent($student);

            Log::info('User created', [
                'user' => $user,
            ]);

            $enrollmentResponse = $this->client->request('enrol_manual_enrol_users', [
                'enrolments' => [
                    [
                        'userid' => $user['id'],
                        'courseid' => $course->external_id,
                        'roleid' => self::STUDENT_ROLE_ID,
                    ]
                ]
            ]);

            Log::info('Student enrolled to course', [
                'enrollment' => $enrollmentResponse,
            ]);

            Mail::to($student->email)->send(new StudentEnrolled(
                course: $course,
                student: $student,
            ));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function unenrollStudent(Student $student, Course $course): void
    {
        // TODO: Unenroll the student from the course

        Mail::to($student->email)->send(new StudentUnenrolled(
            course: $course,
            student: $student,
        ));
    }

    public function createUserToStudent(Student $student): array
    {
        $userExists = $this->client->request('core_user_get_users_by_field', [
            'field' => 'username',
            'values' => [$student->email],
        ]);

        if (!empty($userExists)) {
            Log::info('User already exists', [
                'user' => $userExists,
            ]);

            return $userExists[0];
        }

        $studentData = [
            'username' => $student->email,
            'password' => $student->password,
            'firstname' => $student->first_name,
            'lastname' => $student->last_name,
            'email' => $student->email,
        ];

        Log::info('Creating user to student', [
            'student' => $studentData,
        ]);

        $response = $this->client->request('core_user_create_users', [
            'users' => [$studentData],
        ]);

        Log::info('User create response', [
            'response' => $response,
        ]);

        return $response[0];
    }
}
