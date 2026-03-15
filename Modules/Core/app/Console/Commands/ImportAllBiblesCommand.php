<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

namespace VertexSolutions\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use VertexSolutions\Core\Models\BibleBook;
use VertexSolutions\Core\Models\BibleChapter;
use VertexSolutions\Core\Models\BibleVersion;
use VertexSolutions\Core\Models\BibleVerse;

class ImportAllBiblesCommand extends Command
{
    /** Arquivos/pastas excluídos (DB especial no futuro: hebraico taggado, Strong, grego Koiné). */
    protected const EXCLUDED_FILES = [
        'hebrew_tagged.json',
        'strongs.json',
    ];

    protected const EXCLUDED_PATH_CONTAINS = 'GRC-Κοινη';

    protected $signature = 'bible:import-all {--default= : Abreviação da versão padrão (ex: ARA)}';

    protected $description = 'Importa todas as versões da Bíblia do index.json para a DB (exclui hebrew_tagged, strongs e GRC-Κοινη).';

    public function handle(): int
    {
        $indexPath = storage_path('app/private/bible/offline/index.json');

        if (! file_exists($indexPath)) {
            $this->error("Arquivo index.json não encontrado em: {$indexPath}");

            return 1;
        }

        $indexContent = file_get_contents($indexPath);
        $indexData = json_decode($indexContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Erro ao decodificar index.json: '.json_last_error_msg());

            return 1;
        }

        if (! isset($indexData['versions']) || empty($indexData['versions'])) {
            $this->error('Nenhuma versão encontrada no index.json');

            return 1;
        }

        $versions = $indexData['versions'];
        $defaultAbbreviation = $this->option('default');
        $importedCount = 0;
        $failedCount = 0;
        $skippedCount = 0;

        $this->info('Importando versões do index (hebrew_tagged, strongs e GRC-Κοινη são excluídos).');
        $this->newLine();

        foreach ($versions as $key => $versionInfo) {
            $fileName = $versionInfo['file'] ?? null;
            $name = $versionInfo['name'] ?? '';
            $abbreviation = $versionInfo['abbreviation'] ?? strtoupper($key);

            if (! $fileName) {
                $this->warn("Versão '{$name}' não tem arquivo definido, pulando...");
                $failedCount++;

                continue;
            }

            if ($this->shouldExcludeFile($fileName)) {
                $this->line("⏭ Pulando (excluído): {$fileName}");
                $skippedCount++;

                continue;
            }

            $filePath = storage_path('app/private/bible/offline/'.$fileName);

            if (! file_exists($filePath)) {
                $this->warn("Arquivo não encontrado: {$fileName}, pulando versão '{$name}'...");
                $failedCount++;

                continue;
            }

            $isDefault = ($defaultAbbreviation && strtoupper($abbreviation) === strtoupper($defaultAbbreviation)) ||
                         (! $defaultAbbreviation && $key === array_key_first($versions));

            $this->info("Importando: {$name} ({$abbreviation})...");

            try {
                $exitCode = $this->importBibleVersion($filePath, $name, $abbreviation, $isDefault);

                if ($exitCode === 0) {
                    $this->info("✅ {$name} importada com sucesso!");
                    $importedCount++;
                } else {
                    $this->error("❌ Falha ao importar {$name}");
                    $failedCount++;
                }
            } catch (\Exception $e) {
                $this->error("❌ Erro ao importar '{$name}': ".$e->getMessage());
                $failedCount++;
            }

            $this->newLine();
        }

        $this->info('✅ Importação concluída!');
        $this->info("   - Versões importadas: {$importedCount}");
        if ($skippedCount > 0) {
            $this->line("   - Versões excluídas (hebrew/strongs/GRC): {$skippedCount}");
        }
        if ($failedCount > 0) {
            $this->warn("   - Versões com erro: {$failedCount}");
        }

        return 0;
    }

    private function shouldExcludeFile(string $fileName): bool
    {
        if (in_array($fileName, self::EXCLUDED_FILES, true)) {
            return true;
        }
        if (str_contains($fileName, self::EXCLUDED_PATH_CONTAINS)) {
            return true;
        }

        return false;
    }

    /**
     * Importa uma versão da Bíblia (batch: livros, capítulos, versículos).
     */
    private function importBibleVersion(string $filePath, string $name, string $abbreviation, bool $isDefault): int
    {
        if (! file_exists($filePath)) {
            $this->error("Arquivo não encontrado: {$filePath}");

            return 1;
        }

        DB::beginTransaction();

        try {
            $jsonContent = file_get_contents($filePath);
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Erro ao decodificar JSON: '.json_last_error_msg());
            }

            if (! is_array($data) || empty($data)) {
                throw new \Exception('Arquivo JSON inválido ou vazio');
            }

            $version = BibleVersion::updateOrCreate(
                ['abbreviation' => $abbreviation],
                [
                    'name' => $name,
                    'abbreviation' => $abbreviation,
                    'file_name' => basename($filePath),
                    'is_active' => true,
                    'is_default' => $isDefault,
                    'imported_at' => now(),
                ]
            );

            if ($isDefault) {
                BibleVersion::where('id', '!=', $version->id)->update(['is_default' => false]);
            }

            $version->books()->each(function (BibleBook $book) {
                $book->chapters()->each(function (BibleChapter $chapter) {
                    $chapter->verses()->delete();
                });
                $book->chapters()->delete();
            });
            $version->books()->delete();

            $booksCount = 0;
            $chaptersCount = 0;
            $versesCount = 0;
            $bookNumber = 0;
            $now = now();

            foreach ($data as $bookData) {
                $bookNumber++;
                $bookName = $bookData['name'] ?? '';
                $bookAbbrev = $bookData['abbrev'] ?? '';
                $chapters = $bookData['chapters'] ?? [];

                if (empty($bookName) || empty($chapters)) {
                    continue;
                }

                $testament = $bookNumber <= 39 ? 'old' : 'new';
                $totalChaptersForBook = count($chapters);
                $totalVersesForBook = 0;
                foreach ($chapters as $chapterVerses) {
                    if (is_array($chapterVerses)) {
                        $totalVersesForBook += count($chapterVerses);
                    }
                }

                $book = BibleBook::create([
                    'bible_version_id' => $version->id,
                    'name' => $bookName,
                    'book_number' => $bookNumber,
                    'abbreviation' => $bookAbbrev,
                    'testament' => $testament,
                    'order' => $bookNumber,
                    'total_chapters' => $totalChaptersForBook,
                    'total_verses' => $totalVersesForBook,
                ]);
                $booksCount++;

                $chaptersToInsert = [];
                $versesPayloads = [];
                $chapterNumber = 0;

                foreach ($chapters as $chapterVerses) {
                    $chapterNumber++;
                    if (! is_array($chapterVerses) || empty($chapterVerses)) {
                        continue;
                    }
                    $chaptersToInsert[] = [
                        'book_id' => $book->id,
                        'chapter_number' => $chapterNumber,
                        'total_verses' => count($chapterVerses),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    $chaptersCount++;
                    $versesPayloads[$chapterNumber] = $chapterVerses;
                }

                foreach (array_chunk($chaptersToInsert, 500) as $chunk) {
                    BibleChapter::insert($chunk);
                }

                $chapterMap = BibleChapter::where('book_id', $book->id)->pluck('id', 'chapter_number');
                $versesToInsert = [];

                foreach ($versesPayloads as $cNum => $versesList) {
                    if (! isset($chapterMap[$cNum])) {
                        continue;
                    }
                    $cId = $chapterMap[$cNum];
                    $verseNumber = 0;
                    foreach ($versesList as $verseText) {
                        $verseNumber++;
                        if (is_array($verseText)) {
                            $verseText = implode(' ', $verseText);
                        }
                        $verseText = (string) $verseText;
                        if (empty(trim($verseText))) {
                            continue;
                        }
                        $versesToInsert[] = [
                            'chapter_id' => $cId,
                            'verse_number' => $verseNumber,
                            'text' => trim($verseText),
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                        $versesCount++;
                    }
                }

                foreach (array_chunk($versesToInsert, 1000) as $chunk) {
                    BibleVerse::insert($chunk);
                }
            }

            $version->update([
                'total_books' => $booksCount,
                'total_chapters' => $chaptersCount,
                'total_verses' => $versesCount,
            ]);

            DB::commit();

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Erro na importação: '.$e->getMessage());

            return 1;
        }
    }
}
