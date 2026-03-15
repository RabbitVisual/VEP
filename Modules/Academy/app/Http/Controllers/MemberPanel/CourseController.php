<?php

namespace VertexSolutions\Academy\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use VertexSolutions\Academy\Models\Course;
use VertexSolutions\Academy\Services\CourseProgressService;

class CourseController extends Controller
{
    public function __construct(
        protected CourseProgressService $progressService
    ) {}

    public function show(Course $course): View
    {
        $course->load(['modules.lessons']);
        $enrollment = $course->enrollments()->where('user_id', auth()->id())->first();

        $progressPercent = 0;
        $unlockedLessons = [];
        if ($enrollment) {
            $progressPercent = $this->progressService->getProgressPercent($enrollment);
            foreach ($course->modules as $module) {
                foreach ($module->lessons as $lesson) {
                    if ($this->progressService->isLessonUnlocked($enrollment, $lesson)) {
                        $unlockedLessons[$lesson->id] = true;
                    }
                }
            }
        }

        return view('academy::memberpanel.courses.show', [
            'course' => $course,
            'enrollment' => $enrollment,
            'progressPercent' => $progressPercent,
            'unlockedLessons' => $unlockedLessons,
        ]);
    }
}
