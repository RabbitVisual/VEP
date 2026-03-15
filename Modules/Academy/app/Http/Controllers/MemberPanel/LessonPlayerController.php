<?php

namespace VertexSolutions\Academy\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use App\Services\TheologicalMarkdownConverter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use VertexSolutions\Academy\Models\Enrollment;
use VertexSolutions\Academy\Models\Lesson;
use VertexSolutions\Academy\Services\CourseProgressService;

class LessonPlayerController extends Controller
{
    public function __construct(
        protected CourseProgressService $progressService
    ) {}

    public function player(Enrollment $enrollment, Lesson $lesson): View|JsonResponse
    {
        if ($enrollment->user_id !== auth()->id()) {
            abort(403);
        }
        if ($lesson->module->course_id !== $enrollment->course_id) {
            abort(404);
        }
        if (! $this->progressService->isLessonUnlocked($enrollment, $lesson)) {
            abort(403, 'Aula ainda não liberada.');
        }

        $lesson->load('module.course');
        $enrollment->load('course');
        $contentHtml = $lesson->content ? TheologicalMarkdownConverter::convert($lesson->content) : '';

        $nextLesson = $this->progressService->getNextLesson($enrollment, $lesson);

        return view('academy::memberpanel.player', [
            'enrollment' => $enrollment,
            'lesson' => $lesson,
            'contentHtml' => $contentHtml,
            'nextLesson' => $nextLesson,
        ]);
    }

    public function completeLesson(Request $request, Enrollment $enrollment, Lesson $lesson): JsonResponse
    {
        if ($enrollment->user_id !== auth()->id()) {
            return response()->json(['success' => false], 403);
        }
        if ($lesson->module->course_id !== $enrollment->course_id) {
            return response()->json(['success' => false], 404);
        }

        $watchedSeconds = (int) $request->input('watched_seconds', 0);
        $this->progressService->markLessonComplete($enrollment, $lesson, $watchedSeconds);
        $certificate = $this->progressService->checkAndIssueCertificate($enrollment);

        $nextLesson = $this->progressService->getNextLesson($enrollment, $lesson);
        $nextUrl = $nextLesson
            ? route('painel.academy.player', ['enrollment' => $enrollment, 'lesson' => $nextLesson])
            : null;

        return response()->json([
            'success' => true,
            'next_lesson_url' => $nextUrl,
            'certificate_issued' => $certificate !== null,
            'certificate_download_url' => $certificate ? route('painel.academy.certificates.download', $certificate) : null,
        ]);
    }
}
