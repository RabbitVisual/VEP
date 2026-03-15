<?php

declare(strict_types=1);

namespace VertexSolutions\Core\Services;

use VertexSolutions\Core\Models\BibleBook;
use VertexSolutions\Core\Models\BibleInterlinearVerse;
use VertexSolutions\Core\Models\BibleVerse;

/**
 * Resolves verse ID (bible_verses.id or bible_interlinear_verses.id) to canonical book_number, chapter_number, verse_number.
 */
final class BibleVerseResolver
{
    /**
     * @return array{book_number: int, chapter_number: int, verse_number: int, book_name: string}|null
     */
    public function toBookChapterVerse(int $verseId, string $verseType = 'bible'): ?array
    {
        if ($verseType === 'interlinear') {
            $iv = BibleInterlinearVerse::find($verseId);
            if (! $iv) {
                return null;
            }
            $bookName = BibleBook::where('book_number', $iv->book_number)->value('name');

            return [
                'book_number' => $iv->book_number,
                'chapter_number' => $iv->chapter_number,
                'verse_number' => $iv->verse_number,
                'book_name' => $bookName ?? '',
            ];
        }

        $verse = BibleVerse::with('chapter.book')->find($verseId);
        if (! $verse || ! $verse->chapter || ! $verse->chapter->book) {
            return null;
        }

        $book = $verse->chapter->book;

        return [
            'book_number' => $book->book_number,
            'chapter_number' => $verse->chapter->chapter_number,
            'verse_number' => $verse->verse_number,
            'book_name' => $book->name,
        ];
    }
}
