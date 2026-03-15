<?php

namespace VertexSolutions\Academy\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use VertexSolutions\Academy\Models\Course;
use VertexSolutions\Academy\Models\Enrollment;

class EnrollmentController extends Controller
{
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
}
