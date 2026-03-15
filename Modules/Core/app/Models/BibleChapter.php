<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BibleChapter extends Model
{
    protected $table = 'bible_chapters';
    protected $fillable = [
        'book_id',
        'chapter_number',
        'total_verses',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(BibleBook::class, 'book_id');
    }

    public function verses(): HasMany
    {
        return $this->hasMany(BibleVerse::class, 'chapter_id')->orderBy('verse_number');
    }

    public function getFullReferenceAttribute(): string
    {
        return $this->book->name.' '.$this->chapter_number;
    }
}
