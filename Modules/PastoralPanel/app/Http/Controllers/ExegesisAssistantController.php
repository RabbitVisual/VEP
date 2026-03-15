<?php

declare(strict_types=1);

namespace VertexSolutions\PastoralPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\TheologicalMarkdownConverter;
use VertexSolutions\Core\Services\AIService;
use VertexSolutions\Core\Services\BibleApiService;
use VertexSolutions\Core\Models\BibleInterlinearVerse;

class ExegesisAssistantController extends Controller
{
    public function __construct(
        private readonly AIService $aiService,
        private readonly BibleApiService $bibleApi,
    ) {
    }

    public function index(): View
    {
        $versions = $this->bibleApi->getVersions();
        $defaultVersionId = $versions->first()?->id;
        $books = $defaultVersionId ? $this->bibleApi->getBooks($defaultVersionId) : collect();

        return view('pastoralpanel::exegesis-assistant.index', [
            'versions' => $versions,
            'books' => $books,
        ]);
    }

    public function chapters(Request $request): JsonResponse
    {
        $request->validate(['book_id' => 'required|integer']);
        $chapters = $this->bibleApi->getChapters($request->integer('book_id'));

        return response()->json(['chapters' => $chapters->map(fn ($c) => ['id' => $c->id, 'chapter_number' => $c->chapter_number])]);
    }

    public function verses(Request $request): JsonResponse
    {
        $request->validate([
            'book_id' => 'required|integer',
            'chapter_number' => 'required|integer',
        ]);
        $book = \VertexSolutions\Core\Models\BibleBook::find($request->integer('book_id'));
        if (! $book) {
            return response()->json(['verses' => []]);
        }
        $chapter = \VertexSolutions\Core\Models\BibleChapter::where('book_id', $book->id)
            ->where('chapter_number', $request->integer('chapter_number'))
            ->first();
        if (! $chapter) {
            return response()->json(['verses' => []]);
        }
        $verses = $this->bibleApi->getVerses($chapter->id);

        return response()->json([
            'verses' => $verses->map(fn ($v) => ['id' => $v->id, 'verse_number' => $v->verse_number]),
            'book_number' => $book->book_number,
            'chapter_number' => $chapter->chapter_number,
        ]);
    }

    /**
     * Resolve bible_verses.id + book_number, chapter_number to bible_interlinear_verses.id for the first source.
     */
    public function interlinearVerse(Request $request): JsonResponse
    {
        $request->validate([
            'book_number' => 'required|integer',
            'chapter_number' => 'required|integer',
            'verse_number' => 'required|integer',
        ]);
        $iv = BibleInterlinearVerse::query()
            ->where('book_number', $request->integer('book_number'))
            ->where('chapter_number', $request->integer('chapter_number'))
            ->where('verse_number', $request->integer('verse_number'))
            ->first();

        return response()->json([
            'interlinear_verse_id' => $iv?->id,
            'reference' => $iv
                ? $iv->book_number . '-' . $iv->chapter_number . ':' . $iv->verse_number
                : null,
        ]);
    }

    /**
     * GET pastoral/exegesis-assistant/interlinear-data?interlinear_verse_id=X
     * Returns segments with Strong (word_original, strong_number, lemma, definitions, language) for the central panel.
     */
    public function interlinearData(Request $request): JsonResponse
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

    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'interlinear_verse_id' => 'required|integer',
            'message' => 'required|string|max:2000',
        ]);
        $verseId = $request->integer('interlinear_verse_id');
        $message = $request->string('message')->trim()->toString();

        try {
            $result = $this->aiService->exegesisChat($verseId, 'interlinear', $message);
            $contentHtml = TheologicalMarkdownConverter::convert($result['content'] ?? '');

            return response()->json([
                'content' => $result['content'],
                'content_html' => $contentHtml,
                'usage' => $result['usage'] ?? null,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'ai_unavailable',
                'ai_unavailable' => true,
                'message' => 'O assistente de IA está temporariamente indisponível (serviço externo ou limite de uso). Você ainda pode estudar o texto original e Strong abaixo.',
            ], 503);
        }
    }
}
