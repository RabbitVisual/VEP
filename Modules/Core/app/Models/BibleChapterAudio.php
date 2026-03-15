<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;

class BibleChapterAudio extends Model
{
    protected $table = 'bible_chapter_audio';

    protected $fillable = [
        'bible_version_id',
        'book_number',
        'chapter_number',
        'audio_url',
    ];

    public static function normalizeAudioUrl(string $url): string
    {
        // Simplificada para o exemplo, pode conter lógica do Google Drive
        return $url;
    }
}
