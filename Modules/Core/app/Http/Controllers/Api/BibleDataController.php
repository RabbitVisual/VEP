<?php

declare(strict_types=1);

namespace VertexSolutions\Core\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use VertexSolutions\Core\Models\BibleBook;
use VertexSolutions\Core\Models\BibleChapter;
use VertexSolutions\Core\Models\BibleInterlinearVerse;
use VertexSolutions\Core\Models\BibleVerse;

/**
 * API for Bible autocomplete and interlinear segments (editor @mentions, exegesis panel).
 */
final class BibleDataController extends Controller
{
    /**
     * GET api/bible/autocomplete?q=Jo
     * GET api/bible/autocomplete?q=João 3&book_id=43
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $q = trim((string) $request->input('q', ''));
        $bookId = $request->input('book_id');

        if ($bookId !== null) {
            return $this->autocompleteChaptersVerses((int) $bookId, $q);
        }

        if ($q === '') {
            return response()->json([]);
        }

        $books = BibleBook::query()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('abbreviation', 'like', '%' . $q . '%');
            })
            ->orderBy('book_number')
            ->limit(10)
            ->get(['id', 'name', 'abbreviation', 'book_number']);

        return response()->json($books->map(fn ($b) => [
            'id' => $b->id,
            'name' => $b->name,
            'abbreviation' => $b->abbreviation,
            'book_number' => $b->book_number,
            'reference' => $b->name,
        ]));
    }

    private function autocompleteChaptersVerses(int $bookId, string $q): JsonResponse
    {
        $book = BibleBook::find($bookId);
        if (! $book) {
            return response()->json([]);
        }

        $name = $book->name;
        $q = trim($q);

        if ($q === '') {
            $chapters = BibleChapter::query()
                ->where('book_id', $bookId)
                ->orderBy('chapter_number')
                ->limit(10)
                ->get(['chapter_number']);
            $items = $chapters->map(fn ($c) => [
                'reference' => $name . ' ' . $c->chapter_number,
                'chapter' => $c->chapter_number,
                'verse' => null,
            ]);

            return response()->json($items);
        }

        if (preg_match('/^(\d+):?(\d*)$/', $q, $m)) {
            $chNum = (int) $m[1];
            $versePrefix = isset($m[2]) && $m[2] !== '' ? (int) $m[2] : null;
        } else {
            $chNum = (int) $q;
            $versePrefix = null;
        }

        $chapter = BibleChapter::query()
            ->where('book_id', $bookId)
            ->where('chapter_number', $chNum)
            ->first();

        if (! $chapter) {
            $chapters = BibleChapter::query()
                ->where('book_id', $bookId)
                ->where('chapter_number', 'like', $q . '%')
                ->orderBy('chapter_number')
                ->limit(10)
                ->get(['chapter_number']);
            $items = $chapters->map(fn ($c) => [
                'reference' => $name . ' ' . $c->chapter_number,
                'chapter' => $c->chapter_number,
                'verse' => null,
            ]);

            return response()->json($items);
        }

        $verses = BibleVerse::query()
            ->where('chapter_id', $chapter->id)
            ->orderBy('verse_number')
            ->get(['verse_number']);

        if ($versePrefix !== null) {
            $verses = $verses->filter(fn ($v) => str_starts_with((string) $v->verse_number, (string) $versePrefix));
        }

        $items = $verses->take(15)->map(fn ($v) => [
            'reference' => $name . ' ' . $chNum . ':' . $v->verse_number,
            'chapter' => $chNum,
            'verse' => $v->verse_number,
        ]);

        return response()->json($items->values());
    }

    /**
     * GET api/bible/interlinear-segments?interlinear_verse_id=123
     */
    public function interlinearSegments(Request $request): JsonResponse
    {
        $id = $request->input('interlinear_verse_id');
        if ($id === null || $id === '') {
            return response()->json(['segments' => []], 400);
        }

        $verse = BibleInterlinearVerse::query()
            ->with(['segments.strong.definitions'])
            ->find((int) $id);

        if (! $verse) {
            return response()->json(['segments' => []]);
        }

        $segments = [];
        foreach ($verse->segments as $seg) {
            $strong = $seg->strong;
            $definitions = [];
            if ($strong) {
                foreach ($strong->definitions ?? [] as $def) {
                    $definitions[] = [
                        'level' => $def->level,
                        'sort_order' => $def->sort_order,
                        'definition_text' => $def->definition_text,
                    ];
                }
                if ($strong->description) {
                    $definitions[] = [
                        'level' => 0,
                        'sort_order' => 0,
                        'definition_text' => $strong->description,
                    ];
                }
            }
            $segments[] = [
                'position' => $seg->position,
                'word_original' => $seg->word_original,
                'morph_tag' => $seg->morph_tag,
                'compound_prefix' => $seg->compound_prefix,
                'strong_number' => $strong?->number,
                'lemma' => $strong?->lemma,
                'lemma_br' => $strong?->lemma_br,
                'part_of_speech' => $strong?->part_of_speech,
                'language' => $strong?->language,
                'definitions' => $definitions,
            ];
        }

        return response()->json(['segments' => $segments]);
    }
}
