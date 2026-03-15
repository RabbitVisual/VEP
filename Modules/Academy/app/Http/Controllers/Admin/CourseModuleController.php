<?php

namespace VertexSolutions\Academy\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use VertexSolutions\Academy\Models\Course;
use VertexSolutions\Academy\Models\CourseModule;

class CourseModuleController extends Controller
{
    public function create(Course $course): View
    {
        $maxOrder = $course->modules()->max('order') ?? 0;

        return view('academy::pastoralpanel.modules.create', ['course' => $course, 'maxOrder' => $maxOrder]);
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['course_id'] = $course->id;
        $validated['order'] = $validated['order'] ?? (($course->modules()->max('order') ?? 0) + 1);

        CourseModule::create($validated);

        return redirect()->route('pastoral.academy.courses.show', $course)->with('success', 'Módulo criado.');
    }

    public function edit(CourseModule $module): View
    {
        $module->load('course');

        return view('academy::pastoralpanel.modules.edit', ['module' => $module]);
    }

    public function update(Request $request, CourseModule $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $module->update($validated);

        return redirect()->route('pastoral.academy.courses.show', $module->course)->with('success', 'Módulo atualizado.');
    }

    public function destroy(CourseModule $module): RedirectResponse
    {
        $course = $module->course;
        $module->delete();

        return redirect()->route('pastoral.academy.courses.show', $course)->with('success', 'Módulo removido.');
    }
}
