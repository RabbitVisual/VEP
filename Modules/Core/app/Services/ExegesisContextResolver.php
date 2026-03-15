<?php

declare(strict_types=1);

namespace VertexSolutions\Core\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use VertexSolutions\Core\Models\BibleBook;
use VertexSolutions\Core\Models\BibleInterlinearVerse;
use VertexSolutions\Core\Models\BibleVerse;
use VertexSolutions\Sermons\Models\BibleCommentary;

/**
 * Resolves verse identifiers to book/chapter/verse and fetches exegesis context
 * from bible_interlinear_segments, bible_strong_definitions, and bible_commentaries.
 */
final class ExegesisContextResolver
{
    public function __construct(
        private readonly BibleVerseResolver $verseResolver = new BibleVerseResolver
    ) {
    }

    /**
     * Get exegesis context for a verse: segments (interlinear), Strong definitions, commentaries.
     *
     * @return array{segments: array<int, array>, strong_definitions: array<int, array>, commentaries: array<int, array>, reference: string, book_number: int, chapter_number: int, verse_number: int}
     */
    public function resolve(int $verseId, string $verseType = 'bible'): array
    {
        $ref = $this->verseResolver->toBookChapterVerse($verseId, $verseType);
        if ($ref === null) {
            return $this->emptyContext();
        }

        $bookNumber = $ref['book_number'];
        $chapterNumber = $ref['chapter_number'];
        $verseNumber = $ref['verse_number'];
        $bookName = $ref['book_name'] ?? $this->bookNumberToName($bookNumber);

        $lexiconForever = config('core.ai.cache.lexicon_forever', true);
        $cacheKey = 'ai_exegesis_forever_' . $bookNumber . '_' . $chapterNumber . '_' . $verseNumber;

        $interlinearData = function () use ($bookNumber, $chapterNumber, $verseNumber) {
            $interlinearVerses = BibleInterlinearVerse::query()
                ->where('book_number', $bookNumber)
                ->where('chapter_number', $chapterNumber)
                ->where('verse_number', $verseNumber)
                ->with(['segments.strong.definitions'])
                ->get();

            $segments = [];
            $strongDefinitions = [];
            $seenStrongIds = [];

            foreach ($interlinearVerses as $iv) {
                foreach ($iv->segments as $seg) {
                    $strong = $seg->strong;
                    $segments[] = [
                        'position' => $seg->position,
                        'word_original' => $seg->word_original,
                        'morph_tag' => $seg->morph_tag,
                        'compound_prefix' => $seg->compound_prefix,
                        'strong_number' => $strong?->number,
                        'strong_lemma' => $strong?->lemma,
                        'strong_lemma_br' => $strong?->lemma_br,
                        'strong_part_of_speech' => $strong?->part_of_speech,
                        'language' => $strong?->language ?? null,
                    ];
                    if ($strong && ! isset($seenStrongIds[$strong->id])) {
                        $seenStrongIds[$strong->id] = true;
                        $lang = $strong->language ?? null;
                        foreach ($strong->definitions ?? [] as $def) {
                            $strongDefinitions[] = [
                                'strong_number' => $strong->number,
                                'language' => $lang,
                                'level' => $def->level,
                                'sort_order' => $def->sort_order,
                                'definition_text' => $def->definition_text,
                            ];
                        }
                        if ($strong->description) {
                            $strongDefinitions[] = [
                                'strong_number' => $strong->number,
                                'language' => $lang,
                                'level' => 0,
                                'sort_order' => 0,
                                'definition_text' => $strong->description,
                            ];
                        }
                    }
                }
            }

            return ['segments' => $segments, 'strong_definitions' => $strongDefinitions];
        };

        if ($lexiconForever && config('core.ai.cache.enabled', true)) {
            $cached = Cache::rememberForever($cacheKey, $interlinearData);
            $segments = $cached['segments'];
            $strongDefinitions = $cached['strong_definitions'];
        } else {
            $cached = $interlinearData();
            $segments = $cached['segments'];
            $strongDefinitions = $cached['strong_definitions'];
        }

        $maxCommentaries = (int) config('core.ai.context.max_commentaries', 3);
        $maxCommentLength = (int) config('core.ai.context.max_comment_length', 1500);

        $commentaryQuery = BibleCommentary::query()
            ->where('status', 'published')
            ->where('chapter', $chapterNumber)
            ->where('verse_start', '<=', $verseNumber)
            ->where(function ($q) use ($verseNumber) {
                $q->whereNull('verse_end')->orWhere('verse_end', '>=', $verseNumber);
            })
            ->bookMatch($bookName);

        $commentaries = $commentaryQuery
            ->get(['id', 'title', 'content', 'book', 'chapter', 'verse_start', 'verse_end', 'is_official'])
            ->sortBy([fn ($c) => $c->is_official ? 0 : 1, fn ($c) => strlen((string) $c->content)])
            ->take($maxCommentaries)
            ->map(fn ($c) => [
                'title' => $c->title,
                'content' => Str::limit((string) $c->content, $maxCommentLength),
                'reference' => $c->book . ' ' . $c->chapter . ':' . $c->verse_start . ($c->verse_end && $c->verse_end !== $c->verse_start ? '-' . $c->verse_end : ''),
            ])
            ->values()
            ->all();

        $reference = trim(($bookName ?? '') . ' ' . $chapterNumber . ':' . $verseNumber);

        return [
            'segments' => $segments,
            'strong_definitions' => $strongDefinitions,
            'commentaries' => $commentaries,
            'reference' => $reference,
            'book_number' => $bookNumber,
            'chapter_number' => $chapterNumber,
            'verse_number' => $verseNumber,
        ];
    }

    private function emptyContext(): array
    {
        return [
            'segments' => [],
            'strong_definitions' => [],
            'commentaries' => [],
            'reference' => '',
            'book_number' => 0,
            'chapter_number' => 0,
            'verse_number' => 0,
        ];
    }

    private function bookNumberToName(int $bookNumber): string
    {
        $book = BibleBook::where('book_number', $bookNumber)->first();

        return $book?->name ?? (string) $bookNumber;
    }
}
