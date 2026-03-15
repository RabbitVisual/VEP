<?php

namespace VertexSolutions\Academy\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use VertexSolutions\Academy\Models\Course;

class CatalogController extends Controller
{
    public function catalog(Request $request): View
    {
        $query = Course::published()->withCount('modules');

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%");
            });
        }
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $courses = $query->orderBy('title')->paginate(12)->withQueryString();

        return view('academy::memberpanel.catalog', compact('courses'));
    }
}
