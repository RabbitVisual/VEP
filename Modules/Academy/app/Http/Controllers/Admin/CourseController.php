<?php

namespace VertexSolutions\Academy\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use VertexSolutions\Academy\Models\Course;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::withCount('modules');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%");
            });
        }

        $courses = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('academy::pastoralpanel.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('academy::admin.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'level' => 'required|in:iniciante,intermediário,avançado',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('academy/covers', 'public');
        }

        Course::create($validated);

        return redirect()->route('pastoral.academy.courses.index')->with('success', 'Curso criado com sucesso.');
    }

    public function show(Course $course): View
    {
        $course->load(['modules.lessons']);

        return view('academy::pastoralpanel.courses.show', compact('course'));
    }

    public function edit(Course $course): View
    {
        return view('academy::pastoralpanel.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'level' => 'required|in:iniciante,intermediário,avançado',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('academy/covers', 'public');
        }

        $course->update($validated);

        return redirect()->route('pastoral.academy.courses.index')->with('success', 'Curso atualizado.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('pastoral.academy.courses.index')->with('success', 'Curso removido.');
    }
}
