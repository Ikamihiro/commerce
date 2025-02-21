<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\Student;
use App\Services\Moodle\MoodleService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessStudentEnrollment implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Student $student,
        public Course $course,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(MoodleService $moodleService): void
    {
        $moodleService->enrollStudent($this->student, $this->course);
    }
}
