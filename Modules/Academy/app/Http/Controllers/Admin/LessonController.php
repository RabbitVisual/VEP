<?php

namespace VertexSolutions\Academy\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use VertexSolutions\Academy\Models\CourseModule;
use VertexSolutions\Academy\Models\Lesson;

class LessonController extends Controller
{
    public function create(CourseModule $module): View
    {
        $module->load('course');
        $maxOrder = $module->lessons()->max('order') ?? 0;

        return view('academy::pastoralpanel.lessons.create', ['module' => $module, 'maxOrder' => $maxOrder]);
    }

    public function store(Request $request, CourseModule $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'duration_in_minutes' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['module_id'] = $module->id;
        $validated['duration_in_minutes'] = $validated['duration_in_minutes'] ?? 0;
        $validated['order'] = $validated['order'] ?? (($module->lessons()->max('order') ?? 0) + 1);

        Lesson::create($validated);

        return redirect()->route('pastoral.academy.courses.show', $module->course)->with('success', 'Aula criada.');
    }

    public function edit(Lesson $lesson): View
    {
        $lesson->load('module.course');

        return view('academy::pastoralpanel.lessons.edit', ['lesson' => $lesson]);
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'duration_in_minutes' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        $lesson->update($validated);

        return redirect()->route('pastoral.academy.courses.show', $lesson->module->course)->with('success', 'Aula atualizada.');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $course = $lesson->module->course;
        $lesson->delete();

        return redirect()->route('pastoral.academy.courses.show', $course)->with('success', 'Aula removida.');
    }
}
