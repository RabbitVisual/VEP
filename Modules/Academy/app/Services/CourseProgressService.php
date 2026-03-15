<?php

namespace VertexSolutions\Academy\Services;

use VertexSolutions\Academy\Models\Certificate;
use VertexSolutions\Academy\Models\Enrollment;
use VertexSolutions\Academy\Models\Lesson;
use VertexSolutions\Academy\Models\LessonProgress;

class CourseProgressService
{
    public function markLessonComplete(Enrollment $enrollment, Lesson $lesson, int $watchedSeconds = 0): void
    {
        LessonProgress::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'is_completed' => true,
                'watched_seconds' => $watchedSeconds,
            ]
        );
    }

    public function getProgressPercent(Enrollment $enrollment): int
    {
        $course = $enrollment->course;
        $total = $course->lessons()->count();
        if ($total === 0) {
            return 0;
        }
        $completed = $enrollment->lessonProgress()->where('is_completed', true)->count();

        return (int) round(($completed / $total) * 100);
    }

    public function checkAndIssueCertificate(Enrollment $enrollment): ?Certificate
    {
        if ($this->getProgressPercent($enrollment) < 100) {
            return null;
        }

        $enrollment->update([
            'status' => Enrollment::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        $existing = Certificate::where('user_id', $enrollment->user_id)
            ->where('course_id', $enrollment->course_id)
            ->first();

        if ($existing) {
            return $existing;
        }

        return Certificate::create([
            'user_id' => $enrollment->user_id,
            'course_id' => $enrollment->course_id,
        ]);
    }

    public function getNextLesson(Enrollment $enrollment, Lesson $currentLesson): ?Lesson
    {
        $module = $currentLesson->module;
        $nextInModule = $module->lessons()->where('order', '>', $currentLesson->order)->orderBy('order')->first();
        if ($nextInModule) {
            return $nextInModule;
        }

        $nextModule = $enrollment->course->modules()->where('order', '>', $module->order)->orderBy('order')->first();
        if ($nextModule) {
            return $nextModule->lessons()->orderBy('order')->first();
        }

        return null;
    }

    public function isLessonUnlocked(Enrollment $enrollment, Lesson $lesson): bool
    {
        $course = $enrollment->course;
        $firstLesson = $course->modules()->orderBy('order')->first()?->lessons()->orderBy('order')->first();
        if (! $firstLesson) {
            return false;
        }
        if ($lesson->id === $firstLesson->id) {
            return true;
        }

        $lessonOrder = $lesson->order;
        $module = $lesson->module;

        $previousInModule = $module->lessons()->where('order', '<', $lessonOrder)->orderByDesc('order')->first();
        if ($previousInModule) {
            return $enrollment->lessonProgress()->where('lesson_id', $previousInModule->id)->where('is_completed', true)->exists();
        }

        $prevModule = $course->modules()->where('order', '<', $module->order)->orderByDesc('order')->first();
        if (! $prevModule) {
            return false;
        }
        $lastLessonOfPrev = $prevModule->lessons()->orderByDesc('order')->first();
        if (! $lastLessonOfPrev) {
            return true;
        }

        return $enrollment->lessonProgress()->where('lesson_id', $lastLessonOfPrev->id)->where('is_completed', true)->exists();
    }
}
