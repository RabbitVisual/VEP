<?php

namespace VertexSolutions\Sermons\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use VertexSolutions\Sermons\Models\BibleSeries;

class BibleSeriesController extends Controller
{
    public function index(): View
    {
        $series = BibleSeries::where('status', 'published')
            ->withCount(['sermons', 'studies'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('sermons::memberpanel.series.index', compact('series'));
    }

    public function show(BibleSeries $series): View
    {
        if ($series->status !== 'published') {
            abort(404);
        }

        $series->load([
            'sermons' => function ($q) {
                $q->published()->orderBy('created_at', 'desc');
            },
            'studies' => function ($q) {
                $q->where('status', 'published')->orderBy('created_at', 'desc');
            },
        ]);

        return view('sermons::memberpanel.series.show', compact('series'));
    }
}
