<?php

declare(strict_types=1);

namespace VertexSolutions\Sermons\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use VertexSolutions\Core\Services\AIService;
use VertexSolutions\Sermons\Models\Sermon;

class AISermonSuggestController extends Controller
{
    public function __construct(
        private readonly AIService $aiService,
    ) {
    }

    /**
     * Suggest tags and Bible references from sermon content and study notes.
     */
    public function suggest(Request $request, Sermon $sermon): JsonResponse
    {
        $this->authorize('update', $sermon);

        $sermon->load('studyNotes');
        $fullContent = $sermon->full_content ?? implode("\n\n", array_filter([
            $sermon->introduction,
            $sermon->development,
            $sermon->conclusion,
            $sermon->application,
        ]));
        $studyNotesContent = $sermon->studyNotes->map(fn ($n) => $n->reference_text . ': ' . $n->content)->values()->all();

        $result = $this->aiService->suggestTagsAndReferences($fullContent, $studyNotesContent);

        return response()->json([
            'suggested_tags' => $result['suggested_tags'],
            'suggested_references' => $result['suggested_references'],
        ]);
    }
}
