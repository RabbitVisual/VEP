<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class BibleVersion extends Model
{
    protected $fillable = [
        'name',
        'abbreviation',
        'description',
        'language',
        'file_name',
        'is_active',
        'is_default',
        'total_books',
        'total_chapters',
        'total_verses',
        'imported_at',
        'audio_url_template',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'imported_at' => 'datetime',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(BibleBook::class);
    }

    public function chapterAudios(): HasMany
    {
        return $this->hasMany(BibleChapterAudio::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get the audio URL for a chapter (from template or chapter_audio table).
     */
    public function getChapterAudioUrl(int $bookNumber, int $chapterNumber): ?string
    {
        $audio = $this->chapterAudios()
            ->where('book_number', $bookNumber)
            ->where('chapter_number', $chapterNumber)
            ->first();

        if ($audio && ! empty($audio->audio_url)) {
            return BibleChapterAudio::normalizeAudioUrl($audio->audio_url);
        }

        if (! empty($this->audio_url_template)) {
            return str_replace(
                ['{book}', '{chapter}', '{version}'],
                [$bookNumber, $chapterNumber, $this->abbreviation],
                $this->audio_url_template
            );
        }

        return null;
    }
}
