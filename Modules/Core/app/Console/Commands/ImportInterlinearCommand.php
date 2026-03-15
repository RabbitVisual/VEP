<?php
/**
 * Importa dados da Bíblia Interlinear para a DB: strongs.json, hebrew_tagged.json, GRC-Κοινη/trparsed.json.
 * Nível NEPE: Strong expandido, versos e segmentos hebraico/grego.
 *
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 */

namespace VertexSolutions\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use VertexSolutions\Core\Models\BibleInterlinearLexiconMetadata;
use VertexSolutions\Core\Models\BibleInterlinearSegment;
use VertexSolutions\Core\Models\BibleInterlinearSource;
use VertexSolutions\Core\Models\BibleInterlinearVerse;
use VertexSolutions\Core\Models\BibleStrong;
use VertexSolutions\Core\Models\BibleStrongDefinition;

class ImportInterlinearCommand extends Command
{
    protected $signature = 'bible:import-interlinear
                            {--strongs : Apenas strongs.json}
                            {--hebrew : Apenas hebrew_tagged.json}
                            {--grc : Apenas GRC-Κοινη/trparsed.json}
                            {--fresh : Limpar tabelas interlinear antes de importar}';

    protected $description = 'Importa Strong, hebrew_tagged e GRC trparsed para a DB da Bíblia Interlinear';

    /** Nome do livro (EN) → book_number 1-66 (canônico). */
    protected array $bookNameToNumber = [
        'Genesis' => 1, 'Exodus' => 2, 'Leviticus' => 3, 'Numbers' => 4, 'Deuteronomy' => 5,
        'Joshua' => 6, 'Judges' => 7, 'Ruth' => 8,
        'I Samuel' => 9, '1 Samuel' => 9, 'II Samuel' => 10, '2 Samuel' => 10,
        'I Kings' => 11, '1 Kings' => 11, 'II Kings' => 12, '2 Kings' => 12,
        'I Chronicles' => 13, '1 Chronicles' => 13, 'II Chronicles' => 14, '2 Chronicles' => 14,
        'Ezra' => 15, 'Nehemiah' => 16, 'Esther' => 17, 'Job' => 18, 'Psalms' => 19,
        'Proverbs' => 20, 'Ecclesiastes' => 21, 'Song of Solomon' => 22, 'Isaiah' => 23,
        'Jeremiah' => 24, 'Lamentations' => 25, 'Ezekiel' => 26, 'Daniel' => 27,
        'Hosea' => 28, 'Joel' => 29, 'Amos' => 30, 'Obadiah' => 31, 'Jonah' => 32,
        'Micah' => 33, 'Nahum' => 34, 'Habakkuk' => 35, 'Zephaniah' => 36, 'Haggai' => 37,
        'Zechariah' => 38, 'Malachi' => 39,
        'Matthew' => 40, 'Mark' => 41, 'Luke' => 42, 'John' => 43, 'Acts' => 44,
        'Romans' => 45, '1 Corinthians' => 46, '2 Corinthians' => 47, 'Galatians' => 48,
        'Ephesians' => 49, 'Philippians' => 50, 'Colossians' => 51,
        '1 Thessalonians' => 52, '2 Thessalonians' => 53,
        '1 Timothy' => 54, '2 Timothy' => 55, 'Titus' => 56, 'Philemon' => 57,
        'Hebrews' => 58, 'James' => 59, '1 Peter' => 60, '2 Peter' => 61,
        '1 John' => 62, '2 John' => 63, '3 John' => 64, 'Jude' => 65, 'Revelation' => 66,
    ];

    protected string $basePath;

    public function __construct()
    {
        parent::__construct();
        $this->basePath = storage_path('app/private/bible/offline');
    }

    public function handle(): int
    {
        $onlyStrongs = $this->option('strongs');
        $onlyHebrew = $this->option('hebrew');
        $onlyGrc = $this->option('grc');
        $fresh = $this->option('fresh');

        if (! $onlyStrongs && ! $onlyHebrew && ! $onlyGrc) {
            $onlyStrongs = $onlyHebrew = $onlyGrc = true;
        }

        if ($fresh) {
            $this->warn('Limpando tabelas interlinear...');
            $this->freshInterlinear();
        }

        if ($onlyStrongs) {
            if ($this->importStrongs() !== 0) {
                return 1;
            }
        }

        if ($onlyHebrew) {
            if ($this->importHebrewTagged() !== 0) {
                return 1;
            }
        }

        if ($onlyGrc) {
            if ($this->importGrcTrparsed() !== 0) {
                return 1;
            }
        }

        $this->info('Importação interlinear concluída.');

        return 0;
    }

    protected function freshInterlinear(): void
    {
        BibleInterlinearSegment::query()->delete();
        BibleInterlinearVerse::query()->delete();
        BibleInterlinearSource::query()->delete();
        BibleStrongDefinition::query()->delete();
        BibleStrong::query()->update(['lexicon_metadata_id' => null]);
        BibleStrong::query()->delete();
        BibleInterlinearLexiconMetadata::query()->delete();
    }

    protected function importStrongs(): int
    {
        $path = $this->basePath.'/strongs.json';
        if (! is_readable($path)) {
            $this->error("Arquivo não encontrado: {$path}");

            return 1;
        }

        $this->info('Importando strongs.json...');
        $raw = json_decode(file_get_contents($path), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Erro ao decodificar strongs.json');

            return 1;
        }

        $meta = $raw['metadados'] ?? [];
        $lexicon = BibleInterlinearLexiconMetadata::updateOrCreate(
            ['slug' => 'bsrtb'],
            [
                'title' => $meta['titulo'] ?? 'Bíblia Strong Hebraico/Grego',
                'version' => $meta['versao'] ?? null,
                'year' => $meta['ano'] ?? null,
                'author' => $meta['autor'] ?? null,
                'license' => $meta['licenca'] ?? null,
                'note' => $meta['nota'] ?? null,
                'extra' => $meta,
            ]
        );

        $itens = $raw['itens'] ?? [];
        $bar = $this->output->createProgressBar(count($itens));
        $bar->start();

        $now = now();
        $chunk = [];
        $count = 0;
        foreach ($itens as $item) {
            $num = $item['number'] ?? null;
            if (! $num || ! is_string($num)) {
                $bar->advance();
                continue;
            }
            $lang = (stripos($num, 'G') === 0) ? 'G' : 'H';
            $chunk[] = [
                'number' => $num,
                'lemma' => $item['lemma'] ?? null,
                'lemma_br' => $item['lemma_br'] ?? null,
                'transliteration' => $item['xlit'] ?? null,
                'pronunciation' => $item['pronounce'] ?? null,
                'description' => $item['description'] ?? null,
                'language' => $lang,
                'lexicon_metadata_id' => $lexicon->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $count++;
            if (count($chunk) >= 500) {
                BibleStrong::insert($chunk);
                $chunk = [];
            }
            $bar->advance();
        }
        if ($chunk) {
            BibleStrong::insert($chunk);
        }
        $bar->finish();
        $this->newLine();
        $this->info("Strong: {$count} entradas.");

        return 0;
    }

    protected function importHebrewTagged(): int
    {
        $path = $this->basePath.'/hebrew_tagged.json';
        if (! is_readable($path)) {
            $this->error("Arquivo não encontrado: {$path}");

            return 1;
        }

        $this->info('Importando hebrew_tagged.json...');
        $tagged = json_decode(file_get_contents($path), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Erro ao decodificar hebrew_tagged.json');

            return 1;
        }

        $source = BibleInterlinearSource::firstOrCreate(
            ['slug' => 'hebrew_tagged'],
            ['name' => 'Hebrew Tagged (OT)', 'language' => 'hebrew', 'testament' => 'old', 'metadata' => []]
        );

        $strongsByNumber = BibleStrong::pluck('id', 'number')->all();
        $versesCount = 0;
        $segmentsCount = 0;

        foreach ($tagged as $bookName => $chapters) {
            $bookNumber = $this->bookNameToNumber[$bookName] ?? null;
            if ($bookNumber === null || $bookNumber > 39) {
                continue;
            }
            $chapterNum = 0;
            foreach ($chapters as $verses) {
                $chapterNum++;
                $verseNum = 0;
                foreach ($verses as $segmentList) {
                    $verseNum++;
                    $verse = BibleInterlinearVerse::firstOrCreate(
                        [
                            'interlinear_source_id' => $source->id,
                            'book_number' => $bookNumber,
                            'chapter_number' => $chapterNum,
                            'verse_number' => $verseNum,
                        ],
                        ['raw_text' => null]
                    );
                    $versesCount++;

                    $position = 0;
                    $segmentsToInsert = [];
                    $now = now();
                    foreach ($segmentList as $seg) {
                        $word = is_array($seg) ? ($seg[0] ?? '') : '';
                        $strongRaw = is_array($seg) ? ($seg[1] ?? '') : '';
                        $tag = is_array($seg) ? ($seg[2] ?? '') : '';
                        $strongId = $this->resolveStrongId($strongRaw, $strongsByNumber);
                        $prefix = $this->extractCompoundPrefix($strongRaw);
                        $segmentsToInsert[] = [
                            'interlinear_verse_id' => $verse->id,
                            'position' => $position++,
                            'word_original' => $word,
                            'strong_id' => $strongId,
                            'morph_tag' => $tag ?: null,
                            'compound_prefix' => $prefix,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                    foreach (array_chunk($segmentsToInsert, 500) as $chunk) {
                        BibleInterlinearSegment::insert($chunk);
                        $segmentsCount += count($chunk);
                    }
                }
            }
        }

        $this->info("Hebraico: {$versesCount} versos, {$segmentsCount} segmentos.");

        return 0;
    }

    protected function importGrcTrparsed(): int
    {
        $path = $this->basePath.'/GRC-Κοινη/trparsed.json';
        if (! is_readable($path)) {
            $this->error("Arquivo não encontrado: {$path}");

            return 1;
        }

        $this->info('Importando GRC-Κοινη/trparsed.json...');
        $raw = json_decode(file_get_contents($path), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Erro ao decodificar trparsed.json');

            return 1;
        }

        $meta = $raw['metadata'] ?? [];
        $source = BibleInterlinearSource::firstOrCreate(
            ['slug' => 'grc_trparsed'],
            [
                'name' => $meta['name'] ?? 'Textus Receptus Parsed NT',
                'language' => 'greek',
                'testament' => 'new',
                'metadata' => $meta,
            ]
        );

        $strongsByNumber = BibleStrong::pluck('id', 'number')->all();
        $versesList = $raw['verses'] ?? [];
        $versesCount = 0;
        $segmentsCount = 0;
        $now = now();

        foreach ($versesList as $v) {
            $bookNumber = (int) ($v['book'] ?? 0);
            $chapterNumber = (int) ($v['chapter'] ?? 0);
            $verseNumber = (int) ($v['verse'] ?? 0);
            $text = $v['text'] ?? '';
            if ($bookNumber < 40 || $verseNumber < 1) {
                continue;
            }

            $verse = BibleInterlinearVerse::firstOrCreate(
                [
                    'interlinear_source_id' => $source->id,
                    'book_number' => $bookNumber,
                    'chapter_number' => $chapterNumber,
                    'verse_number' => $verseNumber,
                ],
                ['raw_text' => $text]
            );
            $versesCount++;

            // Formato: "word G976 N-NSF word2 G1078 N-GSF ..."
            preg_match_all('/([^\s]+)\s+(G\d+)\s+([^\s]+)/u', $text, $matches, PREG_SET_ORDER);
            $segmentsToInsert = [];
            $pos = 0;
            foreach ($matches as $m) {
                $strongId = $strongsByNumber[$m[2]] ?? null;
                $segmentsToInsert[] = [
                    'interlinear_verse_id' => $verse->id,
                    'position' => $pos++,
                    'word_original' => $m[1],
                    'strong_id' => $strongId,
                    'morph_tag' => $m[3],
                    'compound_prefix' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($segmentsToInsert) {
                BibleInterlinearSegment::insert($segmentsToInsert);
                $segmentsCount += count($segmentsToInsert);
            }
        }

        $this->info("Grego (TR Parsed): {$versesCount} versos, {$segmentsCount} segmentos.");

        return 0;
    }

    protected function resolveStrongId(string $raw, array $strongsByNumber): ?int
    {
        if (preg_match('/([HG]\d+)/', $raw, $m)) {
            $num = $m[1];
            if (isset($strongsByNumber[$num])) {
                return $strongsByNumber[$num];
            }
        }

        return null;
    }

    protected function extractCompoundPrefix(string $raw): ?string
    {
        // "Hb/H7225" -> "Hb", "Hd/H776" -> "Hd"
        if (preg_match('/^([A-Za-z]+\/)/', $raw, $m)) {
            return rtrim($m[1], '/');
        }

        return null;
    }
}
