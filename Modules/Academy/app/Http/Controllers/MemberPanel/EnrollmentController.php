<?php

namespace VertexSolutions\Academy\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use VertexSolutions\Academy\Models\Course;
use VertexSolutions\Academy\Models\Enrollment;
use VertexSolutions\Academy\Services\CourseProgressService;

class EnrollmentController extends Controller
{
    public function __construct(
        protected CourseProgressService $progressService
    ) {}

    public function enroll(Course $course): RedirectResponse
    {
        if ($course->status !== Course::STATUS_PUBLISHED) {
            abort(404);
        }

        Enrollment::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $course->id,
            ],
            ['status' => Enrollment::STATUS_ACTIVE]
        );

        return redirect()->route('painel.academy.courses.show', $course)->with('success', 'Matrícula realizada.');
    }

    public function myCourses(): View
    {
        $user = auth()->user();

        $enrollments = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $enrollments = $enrollments->map(function (Enrollment $enrollment) {
            $enrollment->progress_percent = $this->progressService->getProgressPercent($enrollment);

            return $enrollment;
        });

        return view('academy::memberpanel.courses.my-courses', [
            'enrollments' => $enrollments,
        ]);
    }
}
