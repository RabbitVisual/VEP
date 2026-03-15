<?php

namespace VertexSolutions\Sermons\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use VertexSolutions\Core\Services\BibleApiService;
use VertexSolutions\Sermons\Models\SermonStudyNote;

class SermonStudyNoteController extends Controller
{
    public function __construct(
        private readonly BibleApiService $bibleApi
    ) {
    }
    /**
     * GET /api/v1/sermons/study-notes – list current user's study notes (optional filters).
     */
    public function index(Request $request): JsonResponse
    {
        $query = SermonStudyNote::where('user_id', $request->user()->id)
            ->orderBy('updated_at', 'desc');

        if ($request->filled('reference_text')) {
            $query->where('reference_text', 'like', '%' . $request->input('reference_text') . '%');
        }
        if ($request->filled('sermon_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('sermon_id', $request->input('sermon_id'))
                    ->orWhereNull('sermon_id');
            });
        }
        if ($request->boolean('global_only')) {
            $query->where('is_global', true);
        }

        $notes = $query->paginate(min(max((int) $request->input('per_page', 20), 1), 50));

        return response()->json([
            'data' => $notes->items(),
            'meta' => [
                'current_page' => $notes->currentPage(),
                'last_page' => $notes->lastPage(),
                'per_page' => $notes->perPage(),
                'total' => $notes->total(),
            ],
        ]);
    }

    /**
     * POST /api/v1/sermons/study-notes – create a study note.
     * Resolves book_id, chapter_id, verse_id from reference_text via BibleApiService::findByReference when not provided.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reference_text' => 'required|string|max:100',
            'sermon_id' => 'nullable|exists:sermons,id',
            'book_id' => 'nullable|exists:bible_books,id',
            'chapter_id' => 'nullable|exists:bible_chapters,id',
            'verse_id' => 'nullable|exists:bible_verses,id',
            'content' => 'required|string',
            'is_global' => 'nullable|boolean',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['is_global'] = $validated['is_global'] ?? false;

        if (empty($validated['book_id']) || empty($validated['chapter_id'])) {
            $found = $this->bibleApi->findByReference($validated['reference_text']);
            if ($found && isset($found['book'], $found['chapter'], $found['verses']) && $found['verses']->isNotEmpty()) {
                $validated['book_id'] = $validated['book_id'] ?? $found['book']->id;
                $validated['chapter_id'] = $validated['chapter_id'] ?? $found['chapter']->id;
                $validated['verse_id'] = $validated['verse_id'] ?? $found['verses']->first()->id;
            }
        }

        $note = SermonStudyNote::create($validated);

        return response()->json(['data' => $note], 201);
    }

    /**
     * GET /api/v1/sermons/study-notes/{id} – show (owner only).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $note = SermonStudyNote::find($id);
        if (! $note || $note->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Nota não encontrada.'], 404);
        }

        return response()->json(['data' => $note]);
    }

    /**
     * PUT /api/v1/sermons/study-notes/{id} – update (owner only).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $note = SermonStudyNote::find($id);
        if (! $note || $note->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Nota não encontrada.'], 404);
        }

        $validated = $request->validate([
            'reference_text' => 'sometimes|string|max:100',
            'content' => 'sometimes|string',
            'is_global' => 'nullable|boolean',
        ]);

        $note->update($validated);

        return response()->json(['data' => $note->fresh()]);
    }

    /**
     * DELETE /api/v1/sermons/study-notes/{id} – delete (owner only).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $note = SermonStudyNote::find($id);
        if (! $note || $note->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Nota não encontrada.'], 404);
        }

        $note->delete();

        return response()->json(['data' => ['success' => true]]);
    }
}
