<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BibleMetadata extends Model
{
    protected $table = 'bible_metadata';

    protected $fillable = [
        'bible_version_id',
        'book_id',
        'chapter_number',
        'verse_count',
    ];

    public function bibleVersion(): BelongsTo
    {
        return $this->belongsTo(BibleVersion::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(BibleBook::class, 'book_id');
    }
}
