<?php

namespace VertexSolutions\Core\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use VertexSolutions\Core\Services\BibleApiService;

/**
 * API panorama e find para integração com Sermons (Contexto Bíblico, @ linker).
 */
class BiblePanoramaController extends Controller
{
    public function __construct(
        private readonly BibleApiService $bibleApi
    ) {
    }

    /**
     * GET /api/v1/bible/panorama?book_number=1&language=pt
     */
    public function panorama(Request $request): JsonResponse
    {
        $bookNumber = $request->filled('book_number') ? (int) $request->input('book_number') : null;
        $bookId = $request->filled('book_id') ? (int) $request->input('book_id') : null;
        $language = $request->input('language', 'pt');

        if ($bookNumber === null && $bookId !== null) {
            $book = \VertexSolutions\Core\Models\BibleBook::find($bookId);
            $bookNumber = $book?->book_number;
        }

        if ($bookNumber === null || $bookNumber < 1 || $bookNumber > 66) {
            return response()->json([
                'data' => null,
                'message' => 'Parâmetro book_number (1-66) ou book_id válido é obrigatório.',
            ], 400);
        }

        $data = $this->bibleApi->getPanoramaByBookNumber($bookNumber, $language);

        return response()->json(['data' => $data]);
    }

    /**
     * GET /api/v1/bible/find?ref=João 3:16
     */
    public function find(Request $request): JsonResponse
    {
        $ref = trim((string) $request->input('ref', ''));
        if ($ref === '') {
            return response()->json(['message' => 'Parâmetro ref é obrigatório.'], 400);
        }

        $result = $this->bibleApi->findByReference($ref);
        if ($result === null) {
            return response()->json(['message' => 'Referência não encontrada.'], 404);
        }

        return response()->json([
            'data' => [
                'reference' => $result['reference'],
                'book' => $result['book'],
                'book_number' => $result['book_number'],
                'chapter' => $result['chapter'],
                'verses' => $result['verses']->map(fn ($v) => [
                    'id' => $v->id,
                    'verse_number' => $v->verse_number,
                    'text' => $v->text,
                ]),
            ],
        ]);
    }
}
