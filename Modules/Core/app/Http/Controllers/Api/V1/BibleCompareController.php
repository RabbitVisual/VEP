<?php

declare(strict_types=1);

namespace VertexSolutions\Core\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use VertexSolutions\Core\Services\BibleApiService;

/**
 * API v1 Bible compare endpoint.
 * GET /api/v1/bible/compare?v1=&v2=&book_number=&chapter=&verse= (optional)
 * Returns JSON aligned with DB: bible_versions (abbreviation, name), bible_verses (id, verse_number, text).
 */
class BibleCompareController extends Controller
{
    public function __construct(
        private readonly BibleApiService $bibleApi
    ) {
    }

    /**
     * Compare verses between two versions. v1/v2 = version abbreviation (e.g. KJF, ACF).
     *
     * @return JsonResponse { data: { v1: { abbreviation, name, verses: [{ id, verse_number, text }] }, v2: { ... } } }
     */
    public function compare(Request $request): JsonResponse
    {
        $v1 = $request->input('v1');
        $v2 = $request->input('v2');
        $bookNumber = (int) $request->input('book_number');
        $chapter = (int) $request->input('chapter');
        $verse = $request->filled('verse') ? (int) $request->input('verse') : null;

        if (empty($v1) || empty($v2) || $bookNumber < 1 || $chapter < 1) {
            return response()->json([
                'message' => 'Parâmetros obrigatórios: v1, v2 (abreviação da versão), book_number, chapter.',
            ], 400);
        }

        $result = $this->bibleApi->compare($v1, $v2, $bookNumber, $chapter, $verse);
        if ($result === null) {
            return response()->json(['message' => 'Versões ou livro não encontrados.'], 404);
        }

        $formatVerses = function ($verses) {
            return $verses->map(fn ($v) => [
                'id' => $v->id,
                'verse_number' => $v->verse_number,
                'text' => $v->text,
            ])->values()->all();
        };

        return response()->json([
            'data' => [
                'v1' => [
                    'abbreviation' => $result['v1']['abbreviation'],
                    'name' => $result['v1']['name'],
                    'verses' => $formatVerses($result['v1']['verses']),
                ],
                'v2' => [
                    'abbreviation' => $result['v2']['abbreviation'],
                    'name' => $result['v2']['name'],
                    'verses' => $formatVerses($result['v2']['verses']),
                ],
            ],
        ]);
    }

    /**
     * GET /api/v1/bible/search?q=
     * Try exact reference first; otherwise full-text search. Returns JSON { data: array|object }.
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        if ($query === '') {
            return response()->json(['data' => []]);
        }

        $findResult = $this->bibleApi->findByReference($query);
        if ($findResult !== null) {
            $verses = $findResult['verses']->map(fn ($v) => [
                'verse_number' => $v->verse_number,
                'text' => $v->text,
            ])->values()->all();
            return response()->json([
                'data' => [
                    'type' => 'exact',
                    'reference' => $findResult['reference'],
                    'book_number' => $findResult['book_number'],
                    'chapter_number' => $findResult['chapter'],
                    'verses' => $verses,
                    'full_chapter_url' => $findResult['full_chapter_url'],
                ],
            ]);
        }

        $verses = $this->bibleApi->search($query, 10)->load('chapter.book');
        $items = $verses->map(fn ($v) => [
            'id' => $v->id,
            'reference' => $v->full_reference ?? ($v->chapter->book->name ?? '') . ' ' . ($v->chapter->chapter_number ?? '') . ':' . ($v->verse_number ?? ''),
            'text' => $v->text,
            'type' => 'search',
            'book_number' => $v->chapter->book->book_number ?? null,
            'chapter_number' => $v->chapter->chapter_number ?? null,
            'verse_number' => $v->verse_number ?? null,
        ])->values()->all();

        return response()->json(['data' => $items]);
    }
}
